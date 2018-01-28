<?php

class Digidennis_WorkSlip_Model_Workslip extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_workslip/workslip');
    }

    protected function _afterLoad()
    {
        if (is_null($this->getData('create_date'))) {
            $this->setCreateDate(Mage::getSingleton('core/date')->gmtDate(now()));
        }
        return parent::_afterLoad();
    }
}