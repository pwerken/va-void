<?php
namespace App\Controller;

use App\Controller\AppController;
use Crud\Event\Subject;

/**
 * Characters Controller
 *
 * @property App\Model\Table\CharactersTable $Characters
 */
class CharactersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->action('index')->config(
			[ 'contain' =>
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
			]	]);

		$this->Crud->action('view')->config(
			[ 'contain' =>
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills' => [ 'Manatypes' ]
				, 'Spells'
				, 'Items'
			]	]);

		$this->Crud->action('add')->config(
			[ 'relatedModels' =>
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
			]	]);

		$this->Crud->action('edit')->config(
			[ 'contain' =>
				[ 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
				]
			, 'relatedModels' =>
				[ 'Players'
				, 'Factions'
				, 'Believes'
				, 'Groups'
				, 'Worlds'
				, 'Conditions'
				, 'Powers'
				, 'Skills'
				, 'Spells'
			]	]);

		$this->Crud->on('beforeHandle', function(\Cake\Event\Event $event) {
			$args = $event->subject->args;
			if(count($args) >= 2) {

				$plin = array_shift($args);
				$chin = array_shift($args);
				$char = $this->Characters->find()
							->where(['player_id' => $plin, 'chin' => $chin])
							->select(['id'])
							->first();

				array_unshift($args, $char ? $char->id : null);
				$event->subject->args = $args;
			}
		});
	}

}
