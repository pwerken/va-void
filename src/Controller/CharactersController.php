<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Characters Controller
 *
 * @property App\Model\Table\CharactersTable $Characters
 */
class CharactersController extends AppController {

	public function index() {
		$this->Crud->on('beforePaginate', function(Event $event) {
			$this->paginate =
				[ 'contain' =>
					[ 'Players'
					, 'Factions'
					, 'Believes'
					, 'Groups'
					, 'Worlds'
					]
				];
		});
		return $this->Crud->execute();
	}

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain(
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				, 'Items'
				]);
		});
		return $this->Crud->execute();
	}

	public function add() {
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				]);
		$this->Crud->execute();
	}

	public function edit($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain(
				[ 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				]);
		});
		$this->Crud->listener('relatedModels')->relatedModels(
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				]);
		$this->Crud->execute();
	}

}
