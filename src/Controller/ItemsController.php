<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Items Controller
 *
 * @property App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' => [ 'Characters' ] ]);

		$this->Crud->action('view')->config(
			[ 'contain' => [ 'Characters', 'Attributes' ] ]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' => [ 'Characters', 'Attributes' ] ]);

		$this->Crud->action('edit')->config(
			[ 'contain' => [ 'Attributes' ]
			, 'relatedModels' => [ 'Characters' ]
			]);

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters' ]
			]);
	}

	public function characterIndex($plin, $chin) {
		$this->Crud->on('beforePaginate',
			function(Event $event) use ($plin, $chin) {
				$this->loadModel('Characters');
				$event->subject->query->where(['character_id' =>
					$this->Characters->plinChin($plin, $chin)]);
		});
		return $this->Crud->execute();
	}
}
