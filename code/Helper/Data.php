<?php

class Digidennis_WorkSlip_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getStates()
    {
        return [
            0 => $this->__('Processing'),
            1 => $this->__('Completed'),
            2 => $this->__('Paused')
        ];
    }
    public function getMaterialStates()
    {
        return [
            0 => $this->__('Must Order'),
            1 => $this->__('Ordered'),
            2 => $this->__('Ready'),
        ];
    }

    public function getOrderedStats($fromdate = null, $todate = null )
    {
        $now = new \DateTime("now");
        $past = new \DateTime("now");
        $past->sub(new DateInterval('P1M'));

        $itemhash = array();
        $invoices = Mage::getModel('sales/order_invoice')->getCollection()
            ->addAttributeToFilter('created_at', array(
                'from'=>$past->format('Y-m-d H:i:s'),
                'to'=>$now->format('Y-m-d H:i:s'))
            );
        foreach ($invoices as $invoice )
        {
            $order = Mage::getModel('sales/order')->load($invoice->getOrderId());
            //FOR ALL ITEMS
            foreach ($order->getAllItems() as $item)
            {
                $qty = intval($item->getQtyInvoiced());
                $id = $item->getProductId();
                $globalvolume = Mage::helper('digidennis_dimensionit')
                    ->getGlobalVolumeOfOrderItem($item);

                //HVIS IKKE ITEM HASHED
                if( !key_exists($id, $itemhash) ) {
                    //OPRET NY
                    $itemhash[$id] = [
                        'qty' => $qty,
                        'name' => $item->getName(),
                        'options' => array(),
                    ];
                    if( $globalvolume ){
                        $itemhash[$id]['volume'] = $globalvolume['volume'];
                        $itemhash[$id]['unit'] = $globalvolume['unit'];
                    }
                } else {
                    //ELLERS TÆL OP
                    $itemhash[$id]['qty'] += $qty;
                    if( $globalvolume ){
                        $itemhash[$id]['volume'] += $globalvolume['volume'];
                    }
                }

                //ITEM HAS OPTIONS
                if(key_exists('options', $item->getProductOptions())){

                    $options = $item->getProductOptions()['options'];
                    foreach ($options as $option ) {
                        //OPTION NOT HASHED? ADD IT
                        if( !key_exists($option['option_id'], $itemhash[$id]['options'] )){
                            $itemhash[$id]['options'][$option['option_id']] = [
                                'values' => array(),
                                'label' => $option['label']
                            ];
                        }
                        //AND ADD SELECTED OPTION VALUES
                        $values = explode(',', $option['option_value']);
                        foreach ($values as $value ) {

                            $typevolume = Mage::helper('digidennis_dimensionit')
                                ->getOptionTypeVolumeOfOrderItem($item,$value);

                            $type = Mage::getModel('catalog/product_option')
                                ->load($option['option_id'])
                                ->getValuesCollection()
                                ->addFieldToFilter('main_table.option_type_id', $value)
                                ->getFirstItem();

                            if( key_exists($type->getOptionTypeId(), $itemhash[$id]['options'][$option['option_id']]['values'])){
                                $itemhash[$id]['options'][$option['option_id']]['values'][$type->getOptionTypeId()]['qty']++;
                                if($typevolume)
                                    $itemhash[$id]['options'][$option['option_id']]['values'][$type->getOptionTypeId()]['volume'] += $typevolume['volume'];
                            } else {
                                $optionvalue = array();
                                $itemhash[$id]['options'][$option['option_id']]['values'][$type->getOptionTypeId()] = [
                                    'name' => $type->getTitle(),
                                    'qty' => 1,
                                    ];
                                if($typevolume){
                                    $itemhash[$id]['options'][$option['option_id']]['values'][$type->getOptionTypeId()]['volume'] = $typevolume['volume'];
                                    $itemhash[$id]['options'][$option['option_id']]['values'][$type->getOptionTypeId()]['volumeunit'] = $typevolume['unit'];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $itemhash;
    }

    public function getProcessingOrdersWithoutShipment()
    {
        $collection = Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_PROCESSING)
            ->addFieldToFilter('shipment.entity_id',  array('null' => true));
        $collection->getSelect()->joinLeft( array(
            'shipment'=> sales_flat_shipment),
            'shipment.order_id = main_table.entity_id',
            array('shipment.entity_id')
        );
        return $collection;
    }
}