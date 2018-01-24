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
        $customerfieldset->addField('lastname', 'text',
            array(
                'label' => $this->__('Surname'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'lastname',
            ));
        $customerfieldset->addField('address', 'text',
            array(
                'label' => $this->__('Address'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'address',
            ));
        $customerfieldset->addField('zip', 'text',
            array(
                'label' => $this->__('Zip'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'zip',
            ));
        $customerfieldset->addField('city', 'text',
            array(
                'label' => $this->__('City'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'city',
            ));
        $customerfieldset->addField('email', 'text',
            array(
                'label' => $this->__('E-mail'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'email',
            ));
        $customerfieldset->addField('phone', 'text',
            array(
                'label' => $this->__('Phone'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'phone',
            ));

        $workfieldset = $form->addFieldset('theWorkFieldset', array('legend'=> $this->__('The Work')));
        $workfieldset->addField('estimateddone_date', 'date',
            array(
                'label' => $this->__('Estimated Done Date'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'estimateddone_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' =>  Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
            ));
        $workfieldset->addField('whattodo', 'textarea',
            array(
                'label' => $this->__('What To Do'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'whattodo',
            ));

        if ( Mage::registry('workslip_data') )
        {
            $form->setValues(Mage::registry('workslip_data')->getData());
        }

        return parent::_prepareForm();
    }
}