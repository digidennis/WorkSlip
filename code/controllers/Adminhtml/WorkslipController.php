<?php

class Digidennis_WorkSlip_Adminhtml_WorkslipController extends Mage_Adminhtml_Controller_action
{

    public function indexAction()
    {
        $this->_title($this->__('WorkSlip'))->_title($this->__('List'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/workslipgrid');
        $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_workslip'));
        $this->renderLayout();
    }
}