<?php

class Figures_Dashboard_Block_Dashboard extends Mage_Core_Block_Template
{
    protected $_customer;

    protected $_salesModel;

    public function __construct(array $args = array())
    {
        $this->_customer = Mage::getSingleton('customer/session')->getCustomer();
        parent::__construct($args);
    }

    public function isLoggedIn()
    {
        return (bool)$this->_customer->getId();
    }

    public function getCustomerNickname()
    {
        return $this->_customer->getData('artist_nickname');
    }

    public function getArtistMoney()
    {
        return $this->_getMoneyModel()->getArtistMoney($this->_customer->getId());
    }

    public function getArtistComission()
    {
        return $this->_getComissionModel()->getArtistComission($this->_customer->getId());
    }

    /**
     * @return Figures_Artist_Model_Sales
     */
    protected function _getSalesModel()
    {
        if (!$this->_salesModel) {
            $this->_salesModel =  Mage::getModel('figures_artist/sales');
        }

        return $this->_salesModel;
    }

    /**
     * @return Figures_Artist_Model_Comission
     */
    protected function _getComissionModel()
    {
        return Mage::getModel('figures_artist/comission');
    }

    /**
     * @return Figures_Artist_Model_Money
     */
    protected function _getMoneyModel()
    {
        return Mage::getModel('figures_artist/money');
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}