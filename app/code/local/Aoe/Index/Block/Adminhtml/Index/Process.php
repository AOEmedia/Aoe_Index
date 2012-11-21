<?php

class Aoe_Index_Block_Adminhtml_Index_Process extends Mage_Index_Block_Adminhtml_Process
{
    public function __construct()
    {
        $this->_blockGroup = 'index';
        $this->_controller = 'adminhtml_process';
        $this->_headerText = Mage::helper('index')->__('Index Management');
        parent::__construct();
		$this->setTemplate('aoe_index/grid/container.phtml');
		$this->_removeButton('add');
    }

	protected function _prepareLayout()
	{
//		$this->setChild( 'grid',
//			$this->getLayout()->createBlock( $this->_blockGroup.'/' . $this->_controller . '_grid',
//				$this->_controller . '.grid')->setSaveParametersInSession(true) );

		$eventGrid = $this->getLayout()->createBlock('aoe_index/adminhtml_index_event_grid');
		$this->setChild('eventGrid', $eventGrid);
		return parent::_prepareLayout();
	}

	public function getCreateUrl()
	{
		return $this->getUrl('*/*/new');
	}

	public function getChildHtml($name = '', $useCache = true, $sorted = false)
	{
		return parent::getChildHtml($name , $useCache , $sorted );
	}
}
