<?php
/**
 *  Essive
 */
class Figures_Artist_Model_Observer extends Varien_Object
{
    public function trackNewOrder($observer)
    {
        try {
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
                    'artist_comission' => $this->_getComissionModel()->getArtistComission($artistData['artist_id']),
                    'artist_comission_status' => Figures_Artist_Model_Sales::COMISSION_NOT_PAID,
                    'artist_comission_net' => $orderItem->getPrice() * $orderItem->getQtyOrdered() * $this->_getComissionModel()->getArtistComission($artistData['artist_id']) / 100
                ]);
                $this->_getArtistModel()->increaseProductValues($orderItem->getProductId(), $orderItem->getQtyOrdered(), $orderItem->getPrice());
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'artist_critical.log', true);
        }
    }

    public function trackPaidOrder($observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        foreach ($order->getAllItems() as $orderItem) {
            $this->_getSalesModel()->updateArtistSoldItem(['order_status' => $order->getStatus()], $orderItem->getId());
        }
    }

    public function trackShippedOrder($observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();

        foreach ($order->getAllItems() as $orderItem) {
            $this->_getSalesModel()->updateArtistSoldItem(['order_status' => $order->getStatus(), 'artist_comission_status' => Figures_Artist_Model_Sales::COMISSION_PAID], $orderItem->getId());
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
