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

    public function getFoamStats($fromdate, $todate)
    {
        $invoices = Mage::getModel('sales/order_invoice')->getCollection()
            ->addAttributeToFilter('created_at', array('from'=>$fromdate, 'to'=>$todate));
        foreach ($invoices as $invoice )
        {
            $order = Mage::getModel('sales/order')->load($invoice->getOrderId());
            foreach ($order->getAllItems() as $item)
            {
                $options = $item->getProductOptions();
                if(key_exists('info_buyrequest', $options))
                {
                    $buyrequest = $options['info_buyrequest'];
                }
            }
        }
        return $invoices;
    }
}