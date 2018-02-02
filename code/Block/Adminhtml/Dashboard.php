<?php

class Digidennis_WorkSlip_Block_Adminhtml_Dashboard extends Mage_Core_Block_Template
{

    protected  $startdate;
    protected  $enddate;

    protected function _construct()
    {
        $this->startdate = date('Y-m-01 00:00:00',strtotime('this month'));
        $this->enddate = date('Y-m-t 23:59:59',strtotime('this month'));
        parent::_construct();
        $this->setTemplate('digidennis/workslip/dashboard.phtml');
    }

    public function getFoamStats()
    {
        return Mage::helper('digidennis_workslip')->getFoamStats($this->startdate, $this->enddate)->count();
    }

    public function changeDateRange($strdate)
    {
        $this->startdate = date('Y-m-01 00:00:00', strtotime($strdate));
        $this->enddate = date('Y-m-t 23:59:00', strtotime($strdate));
    }
}