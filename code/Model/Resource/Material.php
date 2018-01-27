<?php

class Digidennis_WorkSlip_Model_Resource_Material extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_workslip/material', 'material_id');
    }
}