<?php

class Digidennis_WorkSlip_Adminhtml_MaterialController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('Material'))->_title($this->__('List'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/workslipgrid');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('digidennis_workslip/adminhtml_material_grid')->toHtml()
        );
    }

    public function editAction()
    {
        //save workslip edit data if present
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $workslipModel = Mage::getModel('digidennis_workslip/workslip');

                if(Mage::getSingleton('adminhtml/session')->getWorkslipEditId())
                    $workslipModel->load(Mage::getSingleton('adminhtml/session')->getWorkslipEditId());
                else{
                    if (is_null($workslipModel->getData('create_date'))) {
                        $workslipModel->setCreateDate(Mage::getSingleton('core/date')->gmtDate(now()));
                    }
                }

                $workslipModel->addData($this->_filterDates($postData, array("estimateddone_date")));
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
                ->createBlock('digidennis_workslip/adminhtml_material_edit'));
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError('Material does not exist');
            $this->_redirect('*/workslip/');
        }

    }


    public function saveAction()
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
                if(Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
                    $this->_redirect('*/workslip/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
                else
                    $this->_redirect('*/workslip/');
                return;
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setMaterialData($this->getRequest()->getPost());
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
                $model = Mage::getModel('digidennis_workslip/material');
                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess('successfully deleted');
                if(Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
                    $this->_redirect('*/workslip/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
                else
                    $this->_redirect('*/workslip/');
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/workslip/');
    }

    
    public function massStateAction()
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
        if(Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
            $this->_redirect('*/workslip/edit', array('id' => Mage::getSingleton('adminhtml/session')->getWorkslipEditId()));
        else
            $this->_redirect('*/workslip/');
    }
}