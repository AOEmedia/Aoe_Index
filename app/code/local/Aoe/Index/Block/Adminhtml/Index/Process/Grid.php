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

        if (Mage::getConfig()->getModuleConfig('Aoe_Scheduler')->is('active', 'true'))
        {
            $this->removeColumn('action');
            $this->addColumn('action',
                array(
                    'header'    =>  Mage::helper('index')->__('Action'),
                    'width'     => '100',
                    'type'      => 'action',
                    'getter'    => 'getId',
                    'actions'   => array(
                        array(
                            'caption'   => Mage::helper('index')->__('Reindex Data'),
                            'url'       => array('base'=> '*/*/reindexProcess'),
                            'field'     => 'process'
                        ),
                        array(
                            'caption'   => Mage::helper('index')->__('Queue Reindex'),
                            'url'       => array('base'=> '*/*/queueProcess'),
                            'field'     => 'process'
                        ),
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'is_system' => true,
            ));
        }

		$this->getColumn('ended_at')->setData('ageBase','started_at' );
		$this->getColumn('ended_at')->setData('ageDescription','(Took %s)' );
		$this->getColumn('ended_at')->setData('renderer','Aoe_Index_Block_Adminhtml_Grid_Column_Renderer_Datetime' );
		return $this;
	}


    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        if (Mage::getConfig()->getModuleConfig('Aoe_Scheduler')->is('active', 'true'))
        {
            $this->getMassactionBlock()->addItem('queue', array(
                'label'    => Mage::helper('index')->__('Queue Reindex'),
                'url'      => $this->getUrl('*/*/massQueue'),
                'selected' => true,
            ));
        }

        return $this;
    }
}
