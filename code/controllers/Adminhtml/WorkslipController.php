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
        $id = $this->getRequest()->getParam('id');
        $workslip = Mage::getModel('digidennis_workslip/workslip')->load($id);

        if ($workslip->getWorkslipId() || $id == 0)
        {
            Mage::register('workslip_data', $workslip);

            $this->loadLayout();
            $this->_setActiveMenu('digidennis/workslipgrid');
            $this->_addBreadcrumb('WorkSlip', 'WorkSlip');
            $this->_addBreadcrumb('Edit', 'Edit');

            $this->getLayout()->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()
                ->createBlock('digidennis_workslip/adminhtml_workslip_edit'))
                ->_addLeft($this->getLayout()
                    ->createBlock('digidennis_workslip/adminhtml_workslip_edit_tabs')
                );
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('WorkSlip does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost())
        {
            try {
                $postData = $this->getRequest()->getPost();
                $workslipModel = Mage::getModel('digidennis_workslip/workslip');

                if( $this->getRequest()->getParam('id') <= 0 ) {
                    $workslipModel->setCreateDate( Mage::getSingleton('core/date')->gmtDate() );
                    $workslipModel
                        ->addData($postData)
                        ->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess('successfully saved');
                    Mage::getSingleton('adminhtml/session')->setWorkslipData(false);
                    $this->_redirect('*/*/');
                    return;
                }

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkslipData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }
}