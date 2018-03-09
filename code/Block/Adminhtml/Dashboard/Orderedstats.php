<?php

class Digidennis_WorkSlip_Block_Adminhtml_Dashboard_Orderedstats extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/workslip/dashboard/orderedstats.phtml');
    }

    public function getOrderedStats()
    {
        $stats = Mage::helper('digidennis_workslip')->getOrderedStats($this->getFromDate(), $this->getToDate());
        return $stats;
    }

}