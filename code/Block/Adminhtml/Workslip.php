<?php
class Digidennis_WorkSlip_Block_Adminhtml_Workslip extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_workslip';
        $this->_blockGroup = 'digidennis_workslip';
        $this->_headerText = Mage::helper('digidennis_workslip')->__('WorkSlip List');
        $this->_addButtonLabel = Mage::helper('digidennis_workslip')->__('Add WorkSlip');
        parent::__construct();
    }
}