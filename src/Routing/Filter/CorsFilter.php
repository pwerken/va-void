<?php
namespace App\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;

class CorsFilter extends DispatcherFilter {

	public function beforeDispatch(Event $event)
	{
		$request = $event->data['request'];
		$event->data['response']->cors($request)
			->allowOrigin(['*.the-vortex.nl', 'yvo.muze.nl'])
			->allowCredentials(true)
			->allowMethods(['GET','PUT','DELETE','POST','OPTIONS'])
			->allowHeaders(['x-requested-with', 'Content-Type', 'origin'
					, 'authorization', 'accept', 'client-security-token'])
			->exposeHeaders(['Location'])
			->build();

		if($request->is('options'))
			return $event->data['response'];
	}

}
