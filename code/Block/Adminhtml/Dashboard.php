<?php

class Digidennis_WorkSlip_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/workslip/dashboard.phtml');
    }

    public function getReadyToProcess(){
        return Mage::helper('digidennis_workslip')->getProcessingOrdersWithoutShipment()->getSize();
    }

    public function getButtonHtml($label, $path, $class)
    {
        $button = Mage::app()->getLayout()->createBlock('adminhtml/widget_button');
        $button->setData(array(
            'label' => $this->__($label),
            'onclick' => 'setLocation(\'' . Mage::helper("adminhtml")->getUrl($path) .'\');',
            'type' => 'submit',
            'class' => $class
        ));
        echo $button->toHtml();
    }
}