<?php


class Aoe_Index_Block_Adminhtml_Index_Process_Grid extends Mage_Index_Block_Adminhtml_Process_Grid
{
    /**
     * Add name and description to collection elements
     */
    protected function _afterLoadCollection()
    {
        /** @var $item Mage_Index_Model_Process */
        foreach ($this->_collection as $key => $item) {
            if (!$item->getIndexer()->isVisible()) {
                $this->_collection->removeItemByKey($key);
                continue;
            }
            $item->setName($item->getIndexer()->getName());
            $item->setDescription($item->getIndexer()->getDescription());
            $item->setUpdateRequired($item->getUnprocessedEventsCollection()->count() > 0 ? 1 : 0);
            if ($item->isLocked()) {
                $item->setStatus(Mage_Index_Model_Process::STATUS_RUNNING);
            }
        }
        return $this;
    }

    /**
     * Prepare grid columns
     */
    protected function _prepareColumns()
    {
		$this->addColumn('started_at', array(
			'header'    => Mage::helper('index')->__('Started At'),
			'type'      => 'datetime',
			'width'     => '180',
			'align'     => 'left',
			'index'     => 'started_at',
			'after' => 'update_required',
			'renderer' => 'Aoe_Index_Block_Adminhtml_Grid_Column_Renderer_Datetime',
			'frame_callback' => array($this, 'decorateDate')
		));
		$this->_columnsOrder['started_at'] = 'update_required';


		parent::_prepareColumns();
		$this->getColumn('ended_at')->setData('ageBase','started_at' );
		$this->getColumn('ended_at')->setData('ageDescription','(Took %s)' );
		$this->getColumn('ended_at')->setData('renderer','Aoe_Index_Block_Adminhtml_Grid_Column_Renderer_Datetime' );
		return $this;
	}


}
