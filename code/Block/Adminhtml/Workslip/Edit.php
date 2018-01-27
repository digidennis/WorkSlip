<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Material'),
            'onclick' => "editForm.submit();",
            'class'   => 'add',
            'name'    => 'addMaterialSubmit'
        ));
        parent::__construct();
        $this->_objectId = 'workslip_id';
        //you will notice that assigns the same blockGroup the Grid Container
        $this->_blockGroup = 'digidennis_workslip';
        // and the same container
        $this->_controller = 'adminhtml_workslip';
        //we define the labels for the buttons save and delete
        $this->_updateButton('save', 'label', $this->__('Save') . ' ' . $this->__('WorkSlip'));
        $this->_updateButton('delete', 'label', $this->__('Delete') . ' ' . $this->__('WorkSlip'));
    }

    /* Here, we look at whether it was transmitted item to form
     * to put the right text in the header (Add or Edit)
     */

    public function getHeaderText()
    {
        if( Mage::registry('workslip_data') && Mage::registry('workslip_data')->getId() )
        {
            return $this->__('Edit') . ' ' . $this->__('WorkSlip');
        }
        else
        {
            return $this->__('New') . ' ' . $this->__('WorkSlip');
        }
    }
}
