<?php

class Aoe_Index_Block_Adminhtml_Index_Event extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'aoe_index';
        $this->_controller = 'adminhtml_process';
        $this->_headerText = Mage::helper('index')->__('Indexer Event Queue');
        parent::__construct();
		$this->setTemplate('aoe_index/grid/container.phtml');
		$this->_removeButton('add');
    }

	protected function _prepareLayout()
	{
		$eventGrid = $this->getLayout()->createBlock('aoe_index/adminhtml_index_event_grid');
		$this->setChild('eventGrid', $eventGrid);

		foreach ($this->_buttons as $level => $buttons) {
			foreach ($buttons as $id => $data) {
				$childId = $this->_prepareButtonBlockId($id);
				$this->_addButtonChildBlock($childId);
			}
		}
		return $this;
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
