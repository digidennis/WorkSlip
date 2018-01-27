<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tab_Materials extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('materialsGrid');
        $this->setDefaultSort('material_id');
        $this->setUseAjax(true);
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('digidennis_workslip/material')
            ->getCollection();
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
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Description'),
            'align'     =>'center',
            'index'     => 'description',
            'type'     => 'text',
            'width'     => '150px',
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Price'),
            'index'     => 'price',
            'type'      => 'currency',
            'currency'  => 'currency_code',
            'align'     => 'center',
            'width'     => '80px',
        ));

        $this->addColumn('state', array(
            'header'    => Mage::helper('digidennis_workslip')->__('State'),
            'align'     =>'center',
            'index'     => 'state',
            'type'  => 'options',
            'width' => '100px',
            'options' => Mage::helper('digidennis_workslip')->getMaterialStates(),
        ));

        return parent::_prepareColumns();
    }

    protected function _nameFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $collection->getSelect()->where("CONCAT(main_table.firstname, \" \", main_table.lastname )like ?", "%$value%");
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}