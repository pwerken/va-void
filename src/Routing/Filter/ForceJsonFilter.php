<?php
namespace App\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;

class ForceJsonFilter extends DispatcherFilter {

	public function __construct($config = []) {
		parent::__construct($config);
	}

	public function beforeDispatch(Event $event) {
		$request = $event->data['request'];
		$request->env('HTTP_ACCEPT', 'application/json');
	}
}
