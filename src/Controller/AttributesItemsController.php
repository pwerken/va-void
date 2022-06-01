<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;

class AttributesItemsController
	extends AppController
{

	public function initialize(): void
	{
		parent::initialize();

		$this->mapMethod('attributesIndex', [ 'read-only' ], true);

		$this->mapMethod('itemsAdd',        [ 'referee'   ]);
		$this->mapMethod('itemsDelete',     [ 'referee'   ]);
# There are no properties on this relation to edit
#		$this->mapMethod('itemsEdit',       [ 'referee'   ]);
		$this->mapMethod('itemsIndex',      [ 'read-only' ], true);
		$this->mapMethod('itemsView',       [ 'read-only' ], true);
	}

	public function itemsAdd($itin)
	{
		$this->request = $this->request->withData('item_id', $itin);
		return $this->Crud->execute();
	}

	public function CrudBeforeHandle(Event $event)
	{
		switch($this->request->getParam('action')) {
		case 'itemsDelete':
		case 'itemsView':
			$temp = $event->getSubject()->args[0];
			$event->getSubject()->args[0] = $event->getSubject()->args[1];
			$event->getSubject()->args[1] = $temp;
			break;
		default:
			break;
		}

		parent::CrudBeforeHandle($event);
	}
}
