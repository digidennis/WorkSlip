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
        $order_ids = $this->getRequest()->getPost()['order_ids'];
        $toprint = array();
        foreach($order_ids as $order_id)
        {
            $order = Mage::getModel('sales/order')->load($order_id);
            if( $order->canShip() && !$order->getShipmentsCollection()->count())
            {
                try {
                    $convertor = Mage::getModel('sales/convert_order');
                    $shipment = $convertor->toShipment($order);
                    foreach ($order->getAllItems() as $orderItem) {
                        if ($orderItem->getQtyToShip() && !$orderItem->getIsVirtual()) {
                            $item = $convertor->itemToShipmentItem($orderItem);
                            $item->setQty($orderItem->getQtyToShip());
                            $shipment->addItem($item);
                        }
                    }
                    $shipment->register();
                    $order->setIsInProcess(true);

                    $shipment->getOrder()->setCustomerNoteNotify(false);
                    $shipment->setShipmentStatus(1);
                    $shipment->getOrder()->setIsInProcess(true);
                    Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($order)
                        ->save();
                    $toprint[] = $shipment;

                } catch (Mage_Core_Exception $e)
                {
                    $this->_getSession()->addError($e->getMessage());
                    $this->_redirect('*/sales_order/index');

                } catch (Exception $e)
                {
                    Mage::logException($e);
                    $this->_getSession()->addError($this->__('Cannot save shipment.'));
                    $this->_redirect('*/sales_order/index');
                }
            }
        }
        if (count($toprint)) {
            $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($toprint);
            $this->_prepareDownloadResponse('ShippingLabels.pdf', $pdf->render(), 'application/pdf');
            return;
        }
        $this->_redirect('*/sales_order/index');
    }

    public function orderedstatsAction()
    {
        $block = $this->getLayout()->createBlock('digidennis_workslip/adminhtml_dashboard_orderedstats');
        $block->setFromDate(new DateTime($this->getRequest()->getParam('fromdate') . '00:00:00'));
        $block->setToDate(new DateTime($this->getRequest()->getParam('todate') . '00:00:00' ));
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($block->toHtml());
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