<?php

class Digidennis_WorkSlip_Block_Adminhtml_Material_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId() ){
            $data = array(
                'label' =>  $this->__('Back'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/workslip/edit',
                        array(
                            'id'=>Mage::getSingleton('adminhtml/session')->getWorkslipEditId())
                    ) . '\')',
                'class'     =>  'back'
            );
            $this->addButton ('my_back', $data, 0, 100,  'header');
        }
        parent::__construct();
        $this->_objectId = 'material_id';
        //you will notice that assigns the same blockGroup the Grid Container
        $this->_blockGroup = 'digidennis_workslip';
        // and the same container
        $this->_controller = 'adminhtml_material';
        $this->_mode = 'edit';
        //we define the labels for the buttons save and delete
        $this->_updateButton('save', 'label', $this->__('Save') . ' ' . $this->__('Material'));
        $this->_updateButton('delete', 'label', $this->__('Delete') . ' ' . $this->__('Material'));

        if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId() ){
            $this->_removeButton('back');
        }
    }

    /* Here, we look at whether it was transmitted item to form
     * to put the right text in the header (Add or Edit)
     */

    public function getHeaderText()
    {
        if( Mage::registry('workslip_material') && Mage::registry('workslip_material')->getId() )
        {
            return $this->__('Edit') . ' ' . $this->__('Material');
        }
        else
        {
            return $this->__('New') . ' ' . $this->__('Material');
        }
    }
}
