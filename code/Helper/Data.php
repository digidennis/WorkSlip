<?php

class Digidennis_WorkSlip_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getStates()
    {
        return [
            0 => $this->__('Processing'),
            1 => $this->__('Completed'),
            2 => $this->__('Paused')
        ];
    }
    public function getMaterialStates()
    {
        return [
            0 => $this->__('Must Order'),
            1 => $this->__('Ordered'),
            2 => $this->__('Ready'),
        ];
    }
}