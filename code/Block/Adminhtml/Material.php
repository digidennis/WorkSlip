<?php
class Digidennis_WorkSlip_Block_Adminhtml_Material extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_material';
        $this->_blockGroup = 'digidennis_workslip';
        $this->_headerText = Mage::helper('digidennis_workslip')->__('Material List');
        if( !Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
            $this->_addButtonLabel = Mage::helper('digidennis_workslip')->__('Add Material');
        parent::__construct();

        if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
            $this->_removeButton('add');
    }
}