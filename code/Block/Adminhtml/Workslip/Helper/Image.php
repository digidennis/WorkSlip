<?php
class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Helper_Image extends Varien_Data_Form_Element_Image{
    //make your renderer allow "multiple" attribute
    public function getHtmlAttributes(){
        return array_merge(parent::getHtmlAttributes(), array('multiple'));
    }

    public function getElementHtml()
    {
        $html = parent::getElementHtml();
        return $html;
    }

}