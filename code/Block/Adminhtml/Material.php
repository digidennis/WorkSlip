<?php
class Digidennis_WorkSlip_Block_Adminhtml_Material extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_material';
        $this->_blockGroup = 'digidennis_workslip';
        $this->_headerText = Mage::helper('digidennis_workslip')->__('Material List');
        $this->setPagerVisibility(false);
        parent::__construct();
        $this->_removeButton('add');

    }

    /**
     * Get header HTML
     *
     * @return string
     */
    public function getHeaderHtml()
    {
        if( Mage::getSingleton('adminhtml/session')->getWorkslipEditId() )
        {
            $html = "<div class=\"entry-edit-head\"><h5 class='icon-head head-edit-form fieldset-legend'>{$this->getHeaderText()}</h5></div>";
            return $html;
        }
        return '<h3 class="' . $this->getHeaderCssClass() . '">' . $this->getHeaderText() . '</h3>';

    }
}