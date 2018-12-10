<?php

class Figures_Dashboard_Block_Dashboard extends Mage_Core_Block_Template
{
    protected $_customer;

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
}