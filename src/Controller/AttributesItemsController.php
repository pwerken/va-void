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

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($itin) {
				$event->subject->query->where(['item_id' => $itin]);
		});
		return $this->Crud->execute();
	}

	public function attributesIndex($id)
	{
		$this->loadModel('Attributes');
		$this->set('parent', $this->Attributes->get($id));

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['attribute_id' => $id]);
		});
		return $this->Crud->execute();
	}

	public function CrudBeforeHandle(Event $event)
	{
		switch($this->request->action) {
		case 'itemsDelete':
		case 'itemsView':
			$args = $this->argsOrder("ab", "ba", $event->subject->args);
			$event->subject->args = $args;
			break;
		default:
			break;
		}
	}

}
