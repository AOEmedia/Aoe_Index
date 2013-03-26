<?php

class Aoe_Index_Block_Adminhtml_Grid_Column_Renderer_Datetime
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    /**
     * Retrieve datetime format
     *
     * @return unknown
     */
    protected function _getFormat()
    {
        $format = $this->getColumn()->getFormat();
        if (!$format) {
            if (is_null(self::$_format)) {
                try {
                    self::$_format = Mage::app()->getLocale()->getDateTimeFormat(
                        Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
                    );
                }
                catch (Exception $e) {
                    Mage::logException($e);
                }
            }
            $format = self::$_format;
        }
        return $format;
    }

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
			$ageBase = NULL;
			if ($this->getColumn()->getData('ageBase')) {
				$ageBase = $row->getData($this->getColumn()->getData('ageBase'));
				$ageBase =  Mage::app()->getLocale()
					->date($ageBase, Varien_Date::DATETIME_INTERNAL_FORMAT);
			}
            $format = $this->_getFormat();
            try {
                $data = Mage::app()->getLocale()
                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT);
            }
            catch (Exception $e)
            {
                $data = Mage::app()->getLocale()
                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT);
            }
			$diff = $this->getDiff(clone $data, $ageBase);
			$descriptionString = $this->getColumn()->getData('ageDescription');
			$descriptionString = ($descriptionString) ? $descriptionString : '(%s ago)';
			$data = $data->toString($format) ."<br>". sprintf($descriptionString, $diff);
            return $data;
        }
        return $this->getColumn()->getDefault();
    }

	protected function getDiff(Zend_Date $timeThen, $timeNow=null) {

		if(!($timeNow instanceof Zend_Date)) {
			$timeNow = new Zend_Date();
		}

		$difference = $timeNow->isLater($timeThen) ? $timeNow->sub($timeThen): $timeThen->sub($timeNow);
		//$difference = $timeNow->sub($timeThen);
		$measure = new Zend_Measure_Time($difference->toValue(), Zend_Measure_Time::SECOND);
		if($measure->compare(new Zend_Measure_Time(0))==0) {
			return $measure->convertTo(Zend_Measure_Time::SECOND, 0);
		}
		$units = array(
			'SECOND'            => array('1', 's'),
			'MINUTE'            => array('60', 'min'),
			'HOUR'              => array('3600', 'h'),
			'DAY'               => array('86400', 'day'),
			'WEEK'              => array('604800', 'week'),
			'MONTH'             => array('2628600', 'month'),
			'YEAR'              => array('31536000', 'year'),
		);
		foreach ($units as $unitId => $unit) {
			if (is_array($unit)
				&& (($unit[0]/abs($measure->getValue(-1))) > 1)
			) {
				break;
			}
			$convertTo = $unitId;
		}
		return abs($measure->convertTo($convertTo, 0));

	}
}
