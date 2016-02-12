<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Factions Controller
 *
 * @property App\Model\Table\FactionsTable $Factions
 */
class FactionsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);

		$this->Crud->mapAction('charactersIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters' ]
			]);
	}

	public function charactersIndex($plin, $chin) {
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->set('parent', $parent);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($parent) {
				$event->subject->query->where(['character_id' => $parent->id]);
		});
		return $this->Crud->execute();
	}
}
