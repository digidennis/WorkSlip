<?php

class Digidennis_WorkSlip_Adminhtml_WorkslipController extends Mage_Adminhtml_Controller_action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}