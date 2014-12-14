<?php
namespace App\View\Helper;

use Cake\View\Helper\TimeHelper;

/**
 * Date helper
 */
class DateHelper extends TimeHelper {

/**
 * Default configuration.
 *
 * @var array
 */
	protected $_defaultConfig = [];

	public function dmy($date) {
		return $this->_formatHelper($date, 'd-m-Y');
	}

	public function full($date) {
		return $this->_formatHelper($date, 'd-m-Y H:i:s');
	}

	private function _formatHelper($date, $format) {
		if($date === null)
			return null;

		return $date->format($format);
	}

}
