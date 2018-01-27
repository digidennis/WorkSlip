<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Form_Renderer_Materials extends Varien_Data_Form_Element_Abstract
{
    protected $_element;

    public function getElementHtml()
    {
        return Mage::helper('core')->getLayout()->createBlock('digidennis_workslip/adminhtml_payment_edit_form_renderer_materials_grid')->toHtml();
    }
}