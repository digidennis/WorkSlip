<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))
                ),
                'method' => 'post',
            )
        );
        $this->setForm($form);
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_LONG
        );
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
        $workfieldset->addField('state', 'select', array(
            'label'     => $this->__('State'),
            'name'      => 'state',
            'values'    => Mage::helper('digidennis_workslip')->getStates()
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
                'name' => 'whattodo',
            ));

        $materialsFieldset = $form->addFieldset('materialsFieldset', array('legend'=> $this->__('Materials')));
        $materialsFieldset->addType('material_grid', 'Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Form_Renderer_Materials');
        $materialsFieldset->addField('materials', 'material_grid', array(
            'name'      => 'materials',
            'onclick' => "",
            'onchange' => "",
            'disabled' => false,
            'readonly' => false,
            'required' => false
        ));

        if ( Mage::registry('workslip_data') )
        {
            $form->setValues(Mage::registry('workslip_data')->getData());
        }
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}