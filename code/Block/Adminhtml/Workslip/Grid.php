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
        $this->setNoFilterMassactionColumn(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('digidennis_workslip/workslip')
            ->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('mass_workslip_id');
        $this->getMassactionBlock()->setFormFieldName('mass_workslip_id');

        $statuses = Mage::helper('digidennis_workslip')->getStates();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('state', array(
            'label'=> $this->__('Change State'),
            'url'  => $this->getUrl('*/*/massState', array('_current'=>true)),
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
            'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT )
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('digidennis_workslip')->__('Name'),
            'index'     => 'name',
            'type'      => 'text',
            'align'     => 'center',
            'width'     => '150px',
        ));

        $this->addColumn('email', array(
            'header'    => $this->__('E-mail'),
            'index'     => 'email',
            'type'      => 'text',
        ));

        $this->addColumn('phone', array(
            'header'    => $this->__('Phone'),
            'index'     => 'phone',
            'type'      => 'text',
        ));

        $this->addColumn('offer_price', array(
            'header'    => $this->__('Tilbudspris'),
            'index'     => 'offer_price',
            'type'      => 'price',
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