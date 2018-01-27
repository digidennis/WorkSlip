<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_LONG
        );
        $customerfieldset = $form->addFieldset('customerFieldset', array('legend'=> $this->__('Customer')));

        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('core')->__('Import from customer'),
                'onclick' =>"setLocation('{$this->getUrl('*/*/importcustomer')}');",
                'class'   => 'add',
            ));
        $button->setName('import_customer');
        $customerfieldset->setHeaderBar($button->toHtml());

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
        $workfieldset->addType('material_grid', 'Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Form_Renderer_Materials');

        $workfieldset->addField('select', 'select', array(
            'label'     => $this->__('State'),
            'name'      => 'state',
            'values' => Mage::helper('digidennis_workslip')->getMaterialStates(),
        ));
        $workfieldset->addField('estimateddone_date', 'date',
            array(
                'class' => 'validate-date required-entry',
                'align'=>"Bl",
                'format' => $dateFormatIso,
                'label' => $this->__('Estimated Done Date'),
                'required' => true,
                'time' => false,
                'name' => 'estimateddone_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
            ));
        $workfieldset->addField('whattodo', 'textarea',
            array(
                'label' => $this->__('What To Do'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'whattodo',
            ));
        $workfieldset->addField('materials', 'material_grid', array(
            'label'     => Mage::helper('digidennis_workslip')->__('Materials'),
            'name'      => 'materials',
            'onclick' => "",
            'onchange' => "",
            'disabled' => false,
            'readonly' => false,
        ));

        if ( Mage::registry('workslip_data') )
        {
            $form->setValues(Mage::registry('workslip_data')->getData());
        }

        return parent::_prepareForm();
    }
}