<?php

require_once Mage::getModuleDir('controllers', 'Digidennis_WorkSlip') . DS . 'Adminhtml/AbstractController.php';

class Digidennis_WorkSlip_Adminhtml_WorkslipController extends Digidennis_WorkSlip_Adminhtml_AbstractController
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
           // $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_workslip_edit'));
           // $this->_addContent($this->getLayout()->createBlock('digidennis_workslip/adminhtml_material'));
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
                Mage::getSingleton('adminhtml/session')->unsFineuploadFiles();

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    switch( $backparam = $this->getRequest()->getParam('back') ){
                        case 'edit':
                            $this->_redirect('*/*/edit', array('id' => $workslipModel->getWorkslipId()));
                            return;
                        case 'print':
                            $this->_redirect('*/*/print', array('id' => $workslipModel->getWorkslipId()));
                            return;
                        case 'makeorder':
                            Mage::getSingleton('core/session')->setWorkslipedOrder($workslipModel->getWorkslipId());
                            $this->_redirect('*/sales_order_create/index/');
                            return;
                    }
                }

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
                $model->load($this->getRequest()->getParam('id'));
                $mediafiles = unserialize($model->getMediafiles());
                $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
                foreach ($mediafiles as $file){
                    unlink($path . $file['path']);
                }
                $model->delete();
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

    public function imageuploadAction()
    {
        $type = 'qqfile';
        $jsondata = array(
            'success' => true,
        );
        if(isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader->setAllowedExtensions(['jpg', 'png', 'jpeg']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
                $workslip = Mage::getModel('digidennis_workslip/workslip')->load(
                    Mage::getSingleton('adminhtml/session')->getWorkslipEditId()
                );
                if($workslip->getWorkslipId()){
                    $uploader->save($path, $_FILES[$type]['name'] );
                    $object = array(
                        'uuid' => $this->getRequest()->getParams()['qquuid'],
                        'name' => $this->getRequest()->getParams()['qqfilename'],
                        'size' => $this->getRequest()->getParams()['qqtotalfilesize'],
                        'path' => $uploader->getUploadedFileName(),
                    );
                    $mediafiles = unserialize($workslip->getMediafiles());
                    if(is_null($mediafiles) || !is_array($mediafiles))
                        $mediafiles = array();
                    $mediafiles[] = $object;
                    $workslip->setMediafiles(serialize($mediafiles));
                    $workslip->save();
                }
            } catch (Exception $e) {
                $jsondata['success'] = false;
                $jsondata['message'] = $e->getMessage();
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsondata));
    }

    public function imagedeleteAction()
    {
        $uuid = $this->getRequest()->getParam('qquuid');
        $workslip = Mage::getModel('digidennis_workslip/workslip')->load(
            Mage::getSingleton('adminhtml/session')->getWorkslipEditId()
        );
        if($uuid && $workslip->getWorkslipId()){
            $mediafiles = unserialize($workslip->getMediafiles());
            $keep = array();
            $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
            foreach ($mediafiles as $file){
                if( $file['uuid'] === $uuid ){
                    unlink($path . $file['path']);
                } else {
                    $keep[] = $file;
                }
            }
            $workslip->setMediafiles(serialize($keep));
            $workslip->save();
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode(['success'=>true]));
    }

    public function imageinitAction()
    {
        $jsondata = array();
        if( $id = Mage::getSingleton('adminhtml/session')->getWorkslipEditId()){
            $workslip = Mage::getModel('digidennis_workslip/workslip')->load($id);
            if( $workslip->getWorkslipId() ){
                $mediafiles = unserialize($workslip->getMediafiles());
                foreach ($mediafiles as $file){
                    $object = new StdClass();
                    $object->name = $file['name'];
                    $object->uuid = $file['uuid'];
                    $object->size = $file['size'];
                    $jsondata[] = $object;
                }
            }
        };
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsondata));
    }

    public function printAction()
    {
        if ($workslipId = $this->getRequest()->getParam('id')) {
            if ($workslip = Mage::getModel('digidennis_workslip/workslip')->load($workslipId)) {
                $pdf = Mage::getModel('digidennis_workslip/workslip_pdf')->getPdf(array($workslip));
                $this->_prepareDownloadResponse('arbejdsseddel_' . $workslipId .'.pdf', $pdf->render(), 'application/pdf');
            }
        }
        else {
            $this->_forward('noRoute');
        }
    }

}