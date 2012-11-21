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
}
