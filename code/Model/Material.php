<?php

class Digidennis_WorkSlip_Model_Material extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_workslip/material');
    }

    protected function _afterLoad()
    {
        if (is_null($this->getData('state'))) {
            $this->setState(0);
        }
        return parent::_afterLoad();
    }
}