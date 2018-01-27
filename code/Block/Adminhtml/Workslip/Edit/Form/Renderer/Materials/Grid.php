<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Form_Renderer_Materials_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('materialsGrid');
        $this->setDefaultSort('material_id');
        $this->setUseAjax(true);
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('digidennis_workslip/material')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('digidennis_workslip')->__('#'),
            'align'     =>'right',
            'width'     => '10px',
            'index'     => 'material_id',
            'sortable'  => false
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Description'),
            'align'     =>'center',
            'index'     => 'description',
            'type'     => 'text',
            'width'     => '150px',
            'sortable'  => false
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Price'),
            'index'     => 'price',
            'type'      => 'currency',
            'currency'  => 'currency_code',
            'align'     => 'center',
            'width'     => '80px',
            'sortable'  => false
        ));

        $this->addColumn('state', array(
            'header'    => Mage::helper('digidennis_workslip')->__('State'),
            'align'     =>'center',
            'index'     => 'state',
            'type'  => 'options',
            'width' => '100px',
            'options' => Mage::helper('digidennis_workslip')->getMaterialStates(),
            'sortable'  => false
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}