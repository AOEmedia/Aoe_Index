<?php

class Aoe_Index_Block_Adminhtml_Index_Event_Grid extends Mage_Adminhtml_Block_Widget_Grid
	//Mage_Index_Block_Adminhtml_Process_Grid
{
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
		$this->_filterVisibility = false;
		$this->_pagerVisibility  = false;
	}

	/**
	 * Prepare grid collection
	 */
	protected function _prepareCollection()
	{  /* @var $collection Aoe_Index_Model_Resource_Event_Collection */
		$collection = Mage::getResourceModel('aoe_index/event_collection');
		//$collection->joinProcessEventTable();

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
			'width'     => '180',
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


		return parent::_prepareColumns();
	}

	/**
	 * Decorate status column values
	 *
	 * @param string $value
	 * @param Mage_Index_Model_Process $row
	 * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
	 * @param bool $isExport
	 * @return string
	 */
	public function decorateStatus($value, $row, $column, $isExport)
	{
		$class = '';
		switch ($row->getStatus()) {
			case Mage_Index_Model_Process::STATUS_PENDING :
				$class = 'grid-severity-notice';
				break;
			case Mage_Index_Model_Process::STATUS_RUNNING :
				$class = 'grid-severity-major';
				break;
			case Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX :
				$class = 'grid-severity-critical';
				break;
		}
		return '<span class="'.$class.'"><span>'.$value.'</span></span>';
	}

	/**
	 * Decorate "Update Required" column values
	 *
	 * @param string $value
	 * @param Mage_Index_Model_Process $row
	 * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
	 * @param bool $isExport
	 * @return string
	 */
	public function decorateUpdateRequired($value, $row, $column, $isExport)
	{
		$class = '';
		switch ($row->getUpdateRequired()) {
			case 0:
				$class = 'grid-severity-notice';
				break;
			case 1:
				$class = 'grid-severity-critical';
				break;
		}
		return '<span class="'.$class.'"><span>'.$value.'</span></span>';
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
		return $this->getUrl('*/*/edit', array('process'=>$row->getId()));
	}

	/**
	 * Add mass-actions to grid
	 */
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('process_id');
		$this->getMassactionBlock()->setFormFieldName('process');

		$modeOptions = Mage::getModel('index/process')->getModesOptions();

		$this->getMassactionBlock()->addItem('change_mode', array(
			'label'         => Mage::helper('index')->__('Change Index Mode'),
			'url'           => $this->getUrl('*/*/massChangeMode'),
			'additional'    => array(
				'mode'      => array(
					'name'      => 'index_mode',
					'type'      => 'select',
					'class'     => 'required-entry',
					'label'     => Mage::helper('index')->__('Index mode'),
					'values'    => $modeOptions
				)
			)
		));

		$this->getMassactionBlock()->addItem('reindex', array(
			'label'    => Mage::helper('index')->__('Reindex Data'),
			'url'      => $this->getUrl('*/*/massReindex'),
			'selected' => true,
		));

		return $this;
	}
}
