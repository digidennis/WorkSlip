<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('workslipGrid');
        $this->setDefaultSort('workslip_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('digidennis_workslip/workslip')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('workslip_id', array(
            'header'    => Mage::helper('digidennis_workslip')->__('#'),
            'align'     =>'right',
            'width'     => '10px',
            'index'     => 'workslip_id',
        ));

        $this->addColumn('create_date', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Created'),
            'align'     =>'center',
            'index'     => 'create_date',
            'type'      => 'datetime',
            'width'     => '50px',
        ));

        $this->addColumn('estimateddone_date', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Estimated to'),
            'align'     =>'center',
            'index'     => 'estimateddone_date',
            'type'     => 'date',
            'width'     => '50px',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Customer'),
            'align'     => 'left',
            'type'      => 'concat',
            'index'     => array('firstname', 'lastname'),
            'separator'    => ' ',
            'filter_index' => "CONCAT(main_table.firstname, ' ', main_table.lastname)",
            'width'     => '150px',
        ));

        $this->addColumn('state', array(
            'header'    => Mage::helper('digidennis_workslip')->__('State'),
            'align'     =>'center',
            'index'     => 'state',
            'type'  => 'options',
            'width' => '100px',
            'options' => Mage::helper('digidennis_workslip')->getStates(),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}