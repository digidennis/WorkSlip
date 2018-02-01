<?php

class Digidennis_WorkSlip_Adminhtml_WorkslipController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('WorkSlip'))->_title($this->__('List'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/workslipgrid');
        $this->renderLayout();
        Mage::getSingleton('adminhtml/session')->unsWorkslipEditId();
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
        // this is the worsklip we are currently editing lets store the id in session so we can return
        if( $workslip->getWorkslipId() )
            Mage::getSingleton('adminhtml/session')->setWorkslipEditId($workslip->getWorkslipId());

        if ($workslip->getWorkslipId() || $id == 0)
        {
            Mage::register('workslip_data', $workslip);

            $this->loadLayout();
            $this->_setActiveMenu('digidennis/workslipgrid');
            $this->_addBreadcrumb('WorkSlip', 'WorkSlip');
            $this->_addBreadcrumb('Edit', 'Edit');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_workslip_edit'));
            $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_material'));
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
        if ($this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
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

    public function massStateAction()
    {
        $state = (int)$this->getRequest()->getParam('state') - 1; //due to array_unshift our index should be negated
        $workslip_ids = $this->getRequest()->getParam('mass_workslip_id');

        if(!is_array($workslip_ids)) {
            Mage::getSingleton('adminhtml/session')->addError( $this->__('Please select WorkSlips.'));
        } else {
            try {
                $workslipmodel = Mage::getModel('digidennis_workslip/workslip');
                foreach ($workslip_ids as $id) {
                    $workslipmodel->load($id);
                    $workslipmodel->setState($state)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tax')->__(
                        'Total of %d record(s) were changed.', count($workslip_ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}