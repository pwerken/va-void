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

		$this->mapMethod('add',              [ 'referee'         ], $char);
		$this->mapMethod('delete',           [ 'super'           ], $char);
		$this->mapMethod('edit',             [ 'referee'         ], $char);
		$this->mapMethod('index',            [ 'referee'         ], $char);
		$this->mapMethod('view',             [ 'referee', 'user' ], $contain);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], $contain);
	}

	public function add()
	{
		$itin = $this->request->data('itin') ?: $this->nextFreeItin();
		$this->request->data('itin', $itin);

		return $this->Crud->execute();
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

	protected function canDelete($entity)
	{
		$this->loadModel('Characters');
		$query = $this->Characters->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		$this->loadModel('AttributesItems');
		$query = $this->AttributesItems->find();
		$query->where(['character_id' => $entity->id]);
		if($query->count() > 0)
			return false;

		return true;
	}

	protected function hasAuthUser($id = null)
	{
		$itin = (int)$this->request->param('itin');
		$data = $this->Items->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id'] ?: -1);
	}

	private function nextFreeItin()
	{
		$ranges = [ 1980, 2201, 2300, 8001, 8888, 9000, 9999, 1000000 ];
		foreach($ranges as $max) {
			$query = $this->Items->find();
			$query->hydrate(false)->select('id')->order(['id' => 'DESC']);
			$next = $query->where(['id <' => $max])->first()['id'] + 1;
			if($next < $max) break;
		}
		return $next;
	}

}
