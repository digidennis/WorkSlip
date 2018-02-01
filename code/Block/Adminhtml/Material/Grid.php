<?php

class Digidennis_WorkSlip_Block_Adminhtml_Material_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
            $collection->addFieldToFilter( 'workslip_id', Mage::getSingleton('adminhtml/session')->getWorkslipEditId() );
        else
            $collection->addFieldToFilter( 'workslip_id', 0 );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('mass_material_id');
        $this->getMassactionBlock()->setFormFieldName('mass_material_id');

        $statuses = Mage::helper('digidennis_workslip')->getMaterialStates();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('state', array(
            'label'=> $this->__('Change State'),
            'url'  => $this->getUrl('*/material/massState', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'state',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => $this->__('State'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
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
        return $this->getUrl('*/material/edit', array('id' => $row->getId()));
    }
}