<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class ItemsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$char    = [ 'Characters' ];
		$contain = [ 'Characters', 'Attributes' ];

		$this->mapMethod('index',            [ 'referee'         ], $char);
		$this->mapMethod('view',             [ 'referee', 'user' ], $contain);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
	}

	public function charactersIndex($plin, $chin)
	{
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$this->set('parent', $parent);

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($parent) {
				$event->subject->query->where(['character_id' => $parent->id]);
		});
		return $this->Crud->execute();
	}

	protected function hasAuthUser($id = null)
	{
		$itin = (int)$this->request->param('itin');
		$data = $this->Items->find()
					->hydrate(false)
					->select(['Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['Characters']['player_id'] ?: -1);
	}
}
