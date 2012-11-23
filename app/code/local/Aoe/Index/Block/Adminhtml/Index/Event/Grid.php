<?php

class Aoe_Index_Block_Adminhtml_Index_Event_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	/**
	 * Process model
	 *
	 * @var Mage_Index_Model_Event
	 */
	protected $_eventModel;

	/**
	 * Collection object
	 *
	 * @var Mage_Index_Model_Resource_Event_Collection
	 */
	protected $_collection = null;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_eventModel = Mage::getSingleton('index/event');
		$this->setId('indexer_events_grid');
		$this->_filterVisibility = true;
		$this->_pagerVisibility  = true;
	}

	/**
	 * Prepare grid collection
	 */
	protected function _prepareCollection()
	{
		/* @var $collection Aoe_Index_Model_Resource_Event_Collection */
		$collection = Mage::getResourceModel('aoe_index/event_collection');
		$collection->joinProcessEventTable();
		$collection->joinProcessTable();

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	/**
	 * Prepare grid columns
	 */
	protected function _prepareColumns()
	{
		$baseUrl = $this->getUrl();
		$this->addColumn('event_id', array(
			'header'    => Mage::helper('index')->__('Event Id'),
			'width'     => '50',
			'align'     => 'left',
			'index'     => 'event_id',
			//'sortable'  => false,
		));

		$this->addColumn('type', array(
			'header'    => Mage::helper('index')->__('Type'),
			'align'     => 'left',
			'index'     => 'type',
		//'sortable'  => false,
		));

		$this->addColumn('entity', array(
			'header'    => Mage::helper('index')->__('Entity'),
			'width'     => '150',
			'align'     => 'left',
			'index'     => 'entity',
			'type'      => 'text',
		));

		$this->addColumn('entity_pk', array(
			'header'    => Mage::helper('index')->__('entity PK'),
			'width'     => '120',
			'align'     => 'left',
			'index'     => 'entity_pk',
		));

		$this->addColumn('created_at', array(
			'header'    => Mage::helper('index')->__('Created At'),
			'type'      => 'datetime',
			'width'     => '180',
			'align'     => 'left',
			'index'     => 'created_at',
		));

		$this->addColumn('process_indexer_code', array(
			'header'    => Mage::helper('index')->__('Indexer code'),
			'type'      => 'text',
			'width'     => '180',
			'align'     => 'left',
			'index'     => 'process_indexer_code',
		));


		$this->addColumn('process_event_status', array(
			'header'    => Mage::helper('index')->__('Status'),
			'type'      => 'text',
			'width'     => '180',
			'align'     => 'left',
			'index'     => 'process_event_status',
		));



		return parent::_prepareColumns();
	}

	/**
	 * Decorate last run date coumn
	 *
	 * @return string
	 */
	public function decorateDate($value, $row, $column, $isExport)
	{
		if(!$value) {
			return $this->__('Never');
		}
		return $value;
	}

	/**
	 * Get row edit url
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return '';
	}
}
