<?php

class Digidennis_WorkSlip_Block_Adminhtml_Material_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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
        $fieldset = $form->addFieldset('material_fieldset', array('legend'=> $this->__('Material')));
        $fieldset->addField('description', 'text',
            array(
                'label' => $this->__('Description'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'description',
            ));
        $fieldset->addField('price', 'text',
            array(
                'label' => $this->__('Price'),
                'class' => 'required-entry validate-number',
                'required' => true,
                'name' => 'price',
            ));
        $fieldset->addField('cost', 'text',
            array(
                'label' => $this->__('Cost'),
                'class' => 'validate-number',
                'required' => false,
                'name' => 'cost',
            ));
        $fieldset->addField('state', 'select',
            array(
                'label'     => $this->__('State'),
                'name'      => 'state',
                'values' => Mage::helper('digidennis_workslip')->getMaterialStates(),
            ));


        if ( Mage::registry('workslip_material') )
        {
            $form->setValues(Mage::registry('workslip_material')->getData());
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}