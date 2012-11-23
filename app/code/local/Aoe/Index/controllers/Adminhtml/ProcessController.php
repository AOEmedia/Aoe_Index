<?php
require("app/code/core/Mage/Index/controllers/Adminhtml/ProcessController.php");

class Aoe_Index_Adminhtml_ProcessController extends Mage_Index_Adminhtml_ProcessController
{


    /**
     * Display processes grid action
     */
    public function listAction()
    {
        $this->_title($this->__('System'))->_title($this->__('Index Management'));

        $this->loadLayout();
        $this->_setActiveMenu('system/index');
        $this->renderLayout();
    }
}
