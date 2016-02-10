<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * AttributesItems Controller
 *
 * @property App\Model\Table\AttributesItemsTable $AttributesItems
 */
class AttributesItemsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('itemIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Attributes', 'Items' => [ 'Characters' ] ]
			]);
		$this->Crud->mapAction('itemView',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Attributes', 'Items' => [ 'Characters' ] ]
			]);
		$this->Crud->mapAction('itemEdit',
			[ 'className' => 'Crud.Edit'
			, 'contain' => [ 'Attributes', 'Items' => [ 'Characters' ] ]
			]);

		$this->Crud->mapAction('attributeIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Attributes', 'Items' => [ 'Characters' ] ]
			]);
	}

	public function itemIndex($itin) {
		$this->loadModel('Items');
		$parent = $this->Items->get($itin);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($itin) {
				$event->subject->query->where(['item_id' => $itin]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
	public function itemView($itin, $id) {
		$this->Crud->on('beforeHandle', function(Event $event) {
			$args = $this->argsOrder("ab", "ba", $event->subject->args);
			$event->subject->args = $args;
		});
		return $this->Crud->execute();
	}
	public function itemEdit($itin, $id) {
		return $this->itemView($itin);
	}

	public function attributeIndex($id) {
		$this->loadModel('Attributes');
		$parent = $this->Attributes->get($id);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['attribute_id' => $id]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
