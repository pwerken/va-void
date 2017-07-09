<?php
namespace App\Controller;

use Cake\Event\Event;

class AttributesItemsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('attributesIndex', [ 'referee' ], true);

		$this->mapMethod('itemsAdd',        [ 'infobalie' ]);
		$this->mapMethod('itemsDelete',     [ 'infobalie' ]);
# There are no properties on this relation to edit
#		$this->mapMethod('itemsEdit',       [ 'referee' ]);
		$this->mapMethod('itemsIndex',      [ 'referee' ], true);
		$this->mapMethod('itemsView',       [ 'referee' ], true);
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
