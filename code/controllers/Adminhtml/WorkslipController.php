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

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_workslip_edit'));
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('WorkSlip does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function editmaterialAction()
    {
        //save edit data if present
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $workslipModel = Mage::getModel('digidennis_workslip/workslip');
                if( $this->getRequest()->getParam('id') )
                    $workslipModel->load($this->getRequest()->getParam('id'));

                $workslipModel->addData($postData);
                $workslipModel->save();

                Mage::getSingleton('adminhtml/session')->setWorkslipEditId($workslipModel->getWorkslipId());

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkslipData($this->getRequest()->getPost());
                Mage::getSingleton('adminhtml/session')->setWorkslipEditId(false);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $id = $this->getRequest()->getParam('id');
        if( Mage::registry('workslip_data') )
        {
            $workslipdata = Mage::registry('workslip_data')->getData();
        }
        $material = Mage::getModel('digidennis_workslip/material')->load($id);

        if ($material->getMaterialId() || $id == 0)
        {
            // new material defaults
            if( $id == 0 )
            {
                $material->setState(0);
                $material->setWorkslipId($workslipdata['workslip_id']);
                $material->setPrice('0');
            }

            Mage::register('workslip_material', $material);

            $this->loadLayout();
            $this->_setActiveMenu('digidennis/workslipgrid');
            $this->_addBreadcrumb('WorkSlip', 'WorkSlip');
            $this->_addBreadcrumb('Edit', 'Edit');
            $this->_addBreadcrumb('Edit Material', 'Edit Material');

            $this->getLayout()->getBlock('head')
                ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()
                ->createBlock('digidennis_workslip/adminhtml_workslip_edit_material'));
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('Material does not exist');
            $this->_redirect('*/*/');
        }

    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                //$this->_filterDates($postData, array('estimateddone_date')); // all date fields in array
                $workslipModel = Mage::getModel('digidennis_workslip/workslip');

                if( $this->getRequest()->getParam('id') )
                    $workslipModel->load($this->getRequest()->getParam('id'));

                if( $workslipModel->getCreateDate() === null ){
                    $workslipModel->setCreateDate(Mage::getSingleton('core/date')->gmtDate(now()));
                }

                $workslipModel->addData($postData);
                $workslipModel->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('WorkSlip'). ' ' . $this->__('saved'));
                Mage::getSingleton('adminhtml/session')->setWorkslipData(false);
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkslipData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function newmaterialAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        if($this->getRequest()->getParam('id') > 0)
        {
            try
            {
                $model = Mage::getModel('digidennis_workslip/workslip');
                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess('successfully deleted');
                $this->_redirect('*/*/');
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
}