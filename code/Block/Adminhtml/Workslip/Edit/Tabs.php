<?php

class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('workslipTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('WorkSlip'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => $this->__('General'),
            'title' => $this->__('General'),
            'content' => $this->getLayout()
                ->createBlock('digidennis_workslip/adminhtml_workslip_edit_tab_form')
                ->toHtml()
        ));
        $this->addTab('form_section', array(
            'label' => $this->__('Materials'),
            'title' => $this->__('Materials'),
            'content' => $this->getLayout()
                ->createBlock('digidennis_workslip/adminhtml_workslip_edit_tab_materials')
                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}