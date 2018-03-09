<?php

class Digidennis_WorkSlip_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Template
{
    protected $_form;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/workslip/dashboard.phtml');
    }

    public function getReadyToProcess(){
        return Mage::helper('digidennis_workslip')->getProcessingOrdersWithoutShipment()->getSize();
    }

    public function getButtonHtml($label, $path, $class)
    {
        $button = Mage::app()->getLayout()->createBlock('adminhtml/widget_button');
        $button->setData(array(
            'label' => $this->__($label),
            'onclick' => 'setLocation(\'' . Mage::helper("adminhtml")->getUrl($path) .'\');',
            'type' => 'submit',
            'class' => $class
        ));
        echo $button->toHtml();
    }

    public function getForm()
    {
        if( is_null($this->_form)){
            $this->_form = new Varien_Data_Form(array(
                'id'        => 'edit_form',
                'action'    => $this->getUrl('*/*/orderedstats'),
                'method'    => 'post'
            ));
            $this->addDate();
            $this->addDate(false);
        }
        return $this->_form;
    }

    public function getStatsButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button');
        $button->setType('button');
        $button->setLabel( $this->__('Update'));
        $button->setClass('search');
        $button->setOnclick('updatestats();');
        return $button->toHtml();

    }

    public function addDate($start=true, $date=null)
    {
        $strDateFormat = 'd-m-Y';
        if($date){
            $formatdate = date(
                $strDateFormat,
                $date
            );
        } else {
            $formatdate = date($strDateFormat, $start ? strtotime('-1month') : time() );
        }
        $element = new Varien_Data_Form_Element_Date(
            array(
                'name' => $start ? 'fromdate' : 'todate',
                'label' => $start ? $this->__('Start Date') : $this->__('End Date'),
                'tabindex' => $start ? 1 : 2,
                'time' => false,
                'format' => 'dd-MM-yyyy',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'value' => $formatdate
            )
        );
        $element->setForm($this->getForm());
        $element->setId($start ? 'fromdate' : 'todate');
        $this->getForm()->addElement($element);
    }
}