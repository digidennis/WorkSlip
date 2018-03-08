<?php

require_once Mage::getModuleDir('controllers', 'Digidennis_WorkSlip') . DS . 'Adminhtml/AbstractController.php';

class Digidennis_WorkSlip_Adminhtml_BackenderController extends Digidennis_WorkSlip_Adminhtml_AbstractController
{

    public function indexAction()
    {
        $this->_title($this->__('Dashboard'))->_title($this->__('Dashboard'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/dashboard');
        $this->renderLayout();
    }

    public function shipmentmakeprintAction()
    {
        $ordercollection = $this->getHelper()->getProcessingOrdersWithoutShipment();
        $toprint = array();
        foreach ($ordercollection as $order) {
            $order = $order->loadByIncrementId($order->getIncrementId());
            if( $order->canShip()) {
                try {
                    $shipment = Mage::getModel('sales/service_order', $order)
                        ->prepareShipment($this->_getItemQtyArray($order));
                    $shipment->getOrder()->setCustomerNoteNotify(false);
                    $shipment->setShipmentStatus(1);
                    $shipment->getOrder()->setIsInProcess(true);
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();
                    $toprint[] = $shipment;

                } catch (Mage_Core_Exception $e) {

                    $this->_getSession()->addError($e->getMessage());
                    $this->_redirect('*/*/index');

                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($this->__('Cannot save shipment.'));
                    $this->_redirect('*/*/index');
                }
            }
        }
        if (count($toprint)) {
            $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($toprint);
            $this->_prepareDownloadResponse('ShippingLabels.pdf', $pdf->render(), 'application/pdf');
            return;
        }

    }

    public function orderedstatsAction()
    {
        $block = $this->getLayout()->createBlock('digidennis_workslip/adminhtml_dashboard_orderedstats');
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($block->toHtml());
    }
    private function _getItemQtyArray( Mage_Sales_Model_Order $order)
    {
        $array = array();
        foreach ($order->getAllVisibleItems() as $item ) {
            $array[ "{$item->getItemId()}" ] = $item->getQtyToShip();
        }
        return $array;
    }

    /**
     * Combine array of labels as instance PDF
     *
     * @param array $labelsContent
     * @return Zend_Pdf
     * @throws
     */
    protected function _combineLabelsPdf(array $labelsContent)
    {
        $outputPdf = new Zend_Pdf();
        foreach ($labelsContent as $content) {
            if (stripos($content, '%PDF-') !== false) {
                $pdfLabel = Zend_Pdf::parse($content);
                foreach ($pdfLabel->pages as $page) {
                    $outputPdf->pages[] = clone $page;
                }
            }
        }
        return $outputPdf;
    }
}