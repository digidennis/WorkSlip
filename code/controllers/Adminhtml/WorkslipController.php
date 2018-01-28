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

            // this is the worsklip we are currently editing lets store the id in session so we can retun
            //it is currently not cleared anywhere
            if( $workslip->getWorkslipId() )
                Mage::getSingleton('adminhtml/session')->setWorkslipEditId($workslip->getWorkslipId());
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('WorkSlip does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function editmaterialAction()
    {
        //save workslip edit data if present
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $workslipModel = Mage::getModel('digidennis_workslip/workslip');

                if(Mage::getSingleton('adminhtml/session')->getWorkslipEditId())
                    $workslipModel->load(Mage::getSingleton('adminhtml/session')->getWorkslipEditId());

                $workslipModel->addData($postData);
                $workslipModel->save();
                Mage::getSingleton('adminhtml/session')->setWorkslipEditId($workslipModel->getWorkslipId());

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkslipData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
                return;
            }
        }

        $id = $this->getRequest()->getParam('id');
        $material = Mage::getModel('digidennis_workslip/material')->load($id);

        if ($material->getMaterialId() || $id == 0)
        {
            // new material defaults
            if( $id == 0 )
            {
                $material->setState(0);
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

    public function savematerialAction()
    {
        if ($this->getRequest()->getPost())
        {
            try
            {
                $postData = $this->getRequest()->getPost();
                $material = Mage::getModel('digidennis_workslip/material');

                if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId())
                    $material->setWorkslipId(Mage::getSingleton('adminhtml/session')->getWorkslipEditId());

                if( $this->getRequest()->getParam('id') )
                    $material->load($this->getRequest()->getParam('id'));

                $material->addData($postData);
                $material->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Material'). ' ' . $this->__('saved'));
                Mage::getSingleton('adminhtml/session')->setMaterialData(false);
                $this->_redirect('*/*/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
                return;
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setMaterialData($this->getRequest()->getPost());
                $this->_redirect('*/*/editmaterial', array('id' => $this->getRequest()->getParam('id')));
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

    public function massStatusAction()
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
    
    public function massMaterialStatusAction()
    {
        $state = (int)$this->getRequest()->getParam('state') - 1; //due to array_unshift our index should be negated
        $material_ids = $this->getRequest()->getParam('mass_material_id');

        if(!is_array($material_ids)) {
            Mage::getSingleton('adminhtml/session')->addError( $this->__('Please select Materials.'));
        } else {
            try {
                $materialmodel = Mage::getModel('digidennis_workslip/material');
                foreach ($material_ids as $id) {
                    $materialmodel->load($id);
                    $materialmodel->setState($state)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tax')->__(
                        'Total of %d record(s) were changed.', count($material_ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
    }
}