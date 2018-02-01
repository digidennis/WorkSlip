<?php

class Digidennis_WorkSlip_Block_Adminhtml_Dashboard extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/workslip/dashboard.phtml');
    }
}