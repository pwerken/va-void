<?php
namespace App\Controller;

use App\Controller\AppController;

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
	}

}
