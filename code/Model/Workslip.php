<?php

class Digidennis_WorkSlip_Model_Workslip extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_workslip/workslip');
    }

    public function getName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}