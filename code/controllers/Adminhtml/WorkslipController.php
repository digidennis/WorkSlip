<?php

class Digidennis_WorkSlip_Adminhtml_WorkslipController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('WorkSlip'))->_title($this->__('List'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/workslipgrid');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('digidennis_workslip/adminhtml_workslip_grid')->toHtml()
        );
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('workslip_id');
        $workslip = Mage::getModel('digidennis_workslip/workslip')->load($id);

        if ($workslip->getWorkslipId() || $id == 0)
        {
            Mage::register('films_data', $workslip);

            $this->loadLayout();
            $this->_setActiveMenu('digidennis/workslipgrid');
            $this->_addBreadcrumb('WorkSlip', 'WorkSlip');
            $this->_addBreadcrumb('Edit', 'Edit');

            $this->getLayout()->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()
                ->createBlock('digidennis_workslip/adminhtml_workslip_edit'))
                ->_addLeft($this->getLayout()
                    ->createBlock('pfay_films/adminhtml_films_edit_tabs')
                );
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('WorkSlip does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }
}