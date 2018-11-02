<?php


class Digidennis_WorkSlip_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('created_at');
        $this->addColumnAfter('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'filter_index' => 'main_table.created_at',
            'type' => 'datetime',
            'width' => '100px',
        ), 'real_order_id');

        $this->addColumnAfter('shipment_action', array(
            'header'=> Mage::helper('sales')->__('Shipment'),
            'width' => '50px',
            'type'      => 'action',
            'getter'    => 'getShipmentId',
            'actions'   => array(
                array(
                    'caption' => $this->__('View'),
                    'url'     => array('base'=>'*/sales_order_shipment/view'),
                    'field'   => 'shipment_id',
                    'data-column' => 'action'
                )
            ),
            'index' => 'shipment_id',
            'sortable' => false,
            'frame_callback' => array($this, 'decorateRow'),
            'header_css_class'=>'a-center',
            'filter' => false,
        ), 'created_at');
    }

    public function decorateRow($value, $row, $column, $isExport){
        if(is_null($row->getData('shipment_id')))
            return '';
        return '<center>' . $value . '</center>';
    }

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        $this->getMassactionBlock()->addItem(
            'workslip',
            array('label' => $this->__('Prepare Shipments'),
                'url'   => $this->getUrl('*/backender/shipmentmakeprint') //this should be the url where there will be mass operation
            )
        );
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->getSelect()->joinLeft( array(
            'shipment'=> sales_flat_shipment),
            'main_table.entity_id = shipment.order_id',
            array('shipment_id' => 'shipment.entity_id')
        );
        $collection->getSelect()->group('main_table.entity_id');
        //$collection->setOrder('shipment_id', 'DESC');

        $this->setCollection($collection);
        if ($this->getCollection()) {

            $this->_preparePage();

            $columnId = $this->getParam($this->getVarNameSort(), $this->_defaultSort);
            $dir      = $this->getParam($this->getVarNameDir(), $this->_defaultDir);
            $filter   = $this->getParam($this->getVarNameFilter(), null);
            if (is_null($filter)) {
                $filter = $this->_defaultFilter;
            }

            if (is_string($filter)) {
                $data = $this->helper('adminhtml')->prepareFilterString($filter);
                $this->_setFilterValues($data);
            }
            else if ($filter && is_array($filter)) {
                $this->_setFilterValues($filter);
            }
            else if(0 !== sizeof($this->_defaultFilter)) {
                $this->_setFilterValues($this->_defaultFilter);
            }

            if (isset($this->_columns[$columnId]) && $this->_columns[$columnId]->getIndex()) {
                $dir = (strtolower($dir)=='desc') ? 'desc' : 'asc';
                $this->_columns[$columnId]->setDir($dir);
                $this->_setCollectionOrder( $this->_columns[$columnId]);
            }

            if (!$this->_isExport) {
                $this->getCollection()->load();
                $this->_afterLoadCollection();
            }
        }

        return $this;
        //return parent::_prepareCollection();
    }
}