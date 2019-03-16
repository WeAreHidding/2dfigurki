<?php

class Figures_Dashboard_Block_Dashboard_Dashboard extends Figures_Dashboard_Block_Dashboard
{
    public function getCurrentComissions()
    {
        return [
            'sum' => $this->_getSalesModel()->getMoneySumByBind(),
            'sum_paid' => $this->_getSalesModel()->getMoneySumByBind("artist_comission_status = 'paid'") ?: '0.00',
            'qty' => $this->_getSalesModel()->getSoldQtySumByBind()
        ];
    }

    public function getTotalComissions()
    {
        return [
            'sum' => $this->_getSalesModel()->getMoneySumByBind(),
            'sum_paid' => $this->_getSalesModel()->getMoneySumByBind("artist_comission_status = 'paid'") ?: '0.00',
            'qty' => $this->_getSalesModel()->getSoldQtySumByBind()
        ];
    }
}