<?php
/**
 * Essive
 */
class Figures_Artist_Model_Frontend_Dashboard extends Mage_Core_Model_Abstract
{
    /**
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_connection = null;

    protected function _construct()
    {
        $this->_connection = $this->_getConnection();
        parent::_construct();
    }

    public function getSalesDataForDashboard($artistId, $from)
    {
        $fromDate = $balance = false;
        switch ($from) {
            case 'actual':
                $fromDate = $this->_connection->fetchOne(
                    $this->_connection->select()
                        ->from('artist_money_log', 'created_at')
                        ->where('description = ?', 'Payout')
                        ->where('artist_id = ?', $artistId)
                        ->order('created_at DESC')
                        ->limit(1)
                );
                $balance = $this->_connection->fetchOne(
                    $this->_connection->select()
                        ->from('artist_money', 'value')
                        ->where('artist_id = ?', $artistId)
                ) ?: '0.00';

                break;
            case '30':
                $fromDate = date('Y-m-d H:i:s', strtotime('-30 days'));
                break;
            case '90':
                $fromDate = date('Y-m-d H:i:s', strtotime('-90 days'));
                break;
            default:
                break;
        }
        if ($fromDate) {
            $where = "created_at > '$fromDate' AND artist_id = $artistId";
        } else {
            $where = "artist_id = $artistId";
        }
        if (!$balance) {
            $balance = $this->_connection->fetchOne(
                $this->_connection->select()
                    ->from('artist_sales', ['SUM(artist_comission_net)'])
            ) ?: '0.00';
        }

        $qtyOrdered = $this->_connection->fetchOne(
            $this->_connection->select()
                ->from('artist_sales', 'SUM(qty_sold)')
                ->where($where)
        ) ?: 0;

        return [
            'qty'     => $qtyOrdered,
            'balance' => $balance
        ];
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}