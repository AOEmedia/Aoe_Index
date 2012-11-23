<?php

class Aoe_Index_Model_Resource_Event_Collection extends Mage_Index_Model_Resource_Event_Collection
{
    /**
     * Join index_process_event table to event table
     *
     * @return Mage_Index_Model_Resource_Event_Collection
     */
    public function joinProcessEventTable()
    {
		$this->_joinProcessEventTable();
        return $this;
	}

	/**
	 * Join index_process_event table to event table
	 *
	 * @return Mage_Index_Model_Resource_Event_Collection
	 */
	public function joinProcessTable()
	{
		if (!$this->getFlag('process_table_joined')) {
			$this->getSelect()->join(array('process' => $this->getTable('index/process')),
				'process.process_id=process_event.process_id',
				array('process_indexer_code' => 'indexer_code',
					'process_table_id' => 'process_id'
				)
			);
			$this->setFlag('process_table_joined', true);
		}
		return $this;
	}

}
