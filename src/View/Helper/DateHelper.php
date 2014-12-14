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

	public function dmy($obj, $field) {
		if(!$obj->has($field))
			return null;

		return $obj->$field->format('d-m-Y');
	}

}
