<?php
namespace App\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;

class NoExtensionsFilter extends DispatcherFilter {

	public function __construct($config = []) {
		parent::__construct($config);
	}

	public function beforeDispatch(Event $event) {
		$request = $event->data['request'];
		if(!empty($request['_ext'])) {
			$request['controller'] .= '.'.$request['_ext'];
			$request['_ext'] = null;
		}
	}
}
