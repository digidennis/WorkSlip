<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $customerfieldset = $form->addFieldset('customerFieldset', array('legend'=> $this->__('Customer')));
        $customerfieldset->addField('firstname', 'text',
            array(
                'label' => $this->__('Firstname'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'firstname',
            ));

        if ( Mage::registry('workslip_data') )
        {
            $form->setValues(Mage::registry('workslip_data')->getData());
        }

        return parent::_prepareForm();
    }
}