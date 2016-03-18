<?php
namespace App\Controller;

use Cake\Event\Event;

class AttributesItemsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain = [ 'Attributes', 'Items' => [ 'Characters' ] ];

		$this->mapMethod('attributesIndex', [ 'referee' ], $contain);

		$this->mapMethod('itemsAdd',        [ 'referee' ]);
		$this->mapMethod('itemsDelete',     [ 'referee' ]);
# There are no properties on this relation to edit
#		$this->mapMethod('itemsEdit',       [ 'referee' ]);
		$this->mapMethod('itemsIndex',      [ 'referee' ], $contain);
		$this->mapMethod('itemsView',       [ 'referee' ], $contain);
	}

	public function itemsAdd($itin)
	{
		$this->request->data('item_id', $itin);
		return $this->Crud->execute();
	}
	public function itemsIndex($itin)
	{
		$this->loadModel('Items');
		$this->set('parent', $this->Items->get($itin));
		return $this->Crud->execute();
	}

	public function attributesIndex($id)
	{
		$this->loadModel('Attributes');
		$this->set('parent', $this->Attributes->get($id));
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
