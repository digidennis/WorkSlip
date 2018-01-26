<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tab_Materials extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('materialsGrid');
        $this->setDefaultSort('workslip_id');
        $this->setUseAjax(true);
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('digidennis_workslip/workslip')
            ->getCollection();
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
            'index'     => array('firstname', 'lastname'),
            'type'      => 'concat',
            'separator' => ' ',
            'align'     => 'center',
            'filter_condition_callback' => array($this, '_nameFilter'),
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