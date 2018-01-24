<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('films_form', array('legend'=>'ref information'));
        $fieldset->addField('name', 'text',
            array(
                'label' => 'Name',
                'class' => 'required-entry',
                'required' => true,
                'name' => 'name',
            ));

        if ( Mage::registry('workslip_data') )
        {
            $form->setValues(Mage::registry('workslip_data')->getData());
        }

        return parent::_prepareForm();
    }
}