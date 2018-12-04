<?php
/**
 *  Essive
 */
class Figures_Artist_Model_Observer extends Varien_Object
{
    public function trackNewOrder($observer)
    {
        $order = $observer->getOrder();
        foreach ($order->getAllItems() as $orderItem) {
            if (!$artistData = $this->_getArtistModel()->getArtistProductByProductId($orderItem->getProductId())) {
                continue;
            }
            $this->_getSalesModel()->saveArtistSoldItem([
                'artist_id' => $artistData['artist_id'],
                'product_id' => $orderItem->getProductId(),
                'work_id' => $artistData['work_id'],
                'order_item_id' => $orderItem->getId(),
                'qty_sold' => $orderItem->getQtyOrdered(),
                'price' => $orderItem->getPrice(),
                'artist_comission' => $this->_getComissionModel()->getArtistComission($artistData['artist_id'])
            ]);
        }
    }

    public function trackPaidOrder($observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        foreach ($order->getAllItems() as $orderItem) {
            $this->_getSalesModel()->updateArtistSoldItem(['order_status' => $order->getStatus(), 'artist_comission_status' => 'order_paid'], $orderItem->getId());
        }
    }

    public function trackShippedOrder($observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();

        foreach ($order->getAllItems() as $orderItem) {
            $this->_getSalesModel()->updateArtistSoldItem(['order_status' => $order->getStatus(), 'artist_comission_status' => 'order_shipped'], $orderItem->getId());
        }
    }

    /**
     * @return Figures_Artist_Model_Comission
     */
    protected function _getComissionModel()
    {
        return Mage::getModel('figures_artist/comission');
    }

    /**
     * @return Figures_Artist_Model_Sales
     */
    protected function _getSalesModel()
    {
        return Mage::getModel('figures_artist/sales');
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}
