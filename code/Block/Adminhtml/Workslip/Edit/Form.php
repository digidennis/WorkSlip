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
                'enctype' => 'multipart/form-data'
            )
        );
        $this->setForm($form);
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $customerfieldset = $form->addFieldset('customerFieldset', array('legend'=> $this->__('Customer')));
        $customerfieldset->addField('name', 'text',
            array(
                'label' => $this->__('Name'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'name',
            ));
        $customerfieldset->addField('address', 'text',
            array(
                'label' => $this->__('Address'),
                'class' => '',
                'required' => false,
                'name' => 'address',
            ));
        $customerfieldset->addField('zip', 'text',
            array(
                'label' => $this->__('Zip'),
                'class' => '',
                'required' => false,
                'name' => 'zip',
            ));
        $customerfieldset->addField('city', 'text',
            array(
                'label' => $this->__('City'),
                'class' => '',
                'required' => false,
                'name' => 'city',
            ));
        $customerfieldset->addField('email', 'text',
            array(
                'label' => $this->__('E-mail'),
                'class' => 'required-entry validate-email',
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
                'class' => 'required-entry',
                'align'=>"Bl",
                'format' => $dateFormatIso,
                'label' => $this->__('Estimated Done Date'),
                'required' => true,
                'name' => 'estimateddone_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
            ));
        $workfieldset->addField('offer_price', 'text',
            array(
                'class' => '',
                'label' => $this->__('Tilbudspris'),
                'required' => false,
                'name' => 'offer_price'
            ));
        $workfieldset->addField('estimated_hours', 'text',
            array(
                'class' => 'validate-number',
                'label' => $this->__('Systue Timer'),
                'required' => false,
                'name' => 'estimated_hours'
            ));
        $workfieldset->addField('whattodo', 'textarea',
            array(
                'label' => $this->__('What To Do'),
                'name' => 'whattodo',
            ));

        $uploadFieldset = $form->addFieldset('uploadFieldset', array('legend'=> $this->__('Filer')));
        $uploadFieldset->addType('fineupload', 'Digidennis_WorkSlip_Block_Adminhtml_Workslip_Helper_Image');
        $uploadFieldset->addField('workslipfiles', 'fineupload', array(
            'name'      => 'mediafiles',
            'label'     => $this->__('Filer'),
            'title'     => $this->__('Filer'),
            'required'  => false,
            'disabled'  => false
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