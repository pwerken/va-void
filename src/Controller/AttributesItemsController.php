<?php
namespace App\Controller;

use Cake\Event\Event;

class AttributesItemsController
	extends AppController
{

	public function initialize()
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
		$this->request->data('item_id', $itin);
		return $this->Crud->execute();
	}

	public function CrudBeforeHandle(Event $event)
	{
		switch($this->request->action) {
		case 'itemsDelete':
		case 'itemsView':
			$temp = $event->subject->args[0];
			$event->subject->args[0] = $event->subject->args[1];
			$event->subject->args[1] = $temp;
			break;
		default:
			break;
		}

		parent::CrudBeforeHandle($event);
	}

}
