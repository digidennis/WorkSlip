<?php

class Digidennis_WorkSlip_Model_Resource_Material_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_workslip/material');
    }
}