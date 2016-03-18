<?php
namespace App\Controller;

use Cake\Event\Event;

class CharactersController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$contain =  [ 'Believes', 'Factions', 'Groups', 'Players', 'Worlds'
					, 'Items', 'Skills' => [ 'Manatypes' ]
					, 'Conditions', 'Powers', 'Spells'
					];

		$this->mapMethod('add',           [ 'infobalie'       ]);
		$this->mapMethod('delete',        [ 'super'           ]);
		$this->mapMethod('edit',          [ 'referee'         ]);
		$this->mapMethod('index',         [ 'referee'         ]);
		$this->mapMethod('view',          [ 'referee', 'user' ], $contain);

		$this->mapMethod('believesIndex', [ 'referee'         ]);
		$this->mapMethod('factionsIndex', [ 'referee'         ]);
		$this->mapMethod('groupsIndex',   [ 'referee'         ]);
		$this->mapMethod('playersIndex',  [ 'referee', 'user' ]);
		$this->mapMethod('worldsIndex',   [ 'referee'         ]);
	}

	public function add($plin)
	{
		$plin = $plin ?: $this->request->data('plin');
		$this->request->data('player_id', $plin);
		unset($this->request->data['plin']);

		$next = $this->Characters->find()
					->select(['nextChin' => 'MAX(chin)'])
					->where(['player_id' => $plin])
					->hydrate(false)
					->first();
		$next = $next ? $next['nextChin'] + 1: 1;
		$this->request->data('chin', $this->request->data('chin') ?: $next);

		$this->Crud->execute();
	}

	public function CrudBeforeHandle(Event $event)
	{
		$event->subject->args = $this->argsCharId($event->subject->args);
		parent::CrudBeforeHandle($event);
	}

}
