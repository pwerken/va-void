<?php
namespace App\Controller;

use App\Model\Entity\Lammy;
use App\Utility\AuthState;
use Cake\Event\Event;

class CharactersController
	extends AppController
{

	protected $searchFields =
		[ 'Characters.name'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',           [ 'referee'           ]);
		$this->mapMethod('delete',        [ 'super'             ]);
		$this->mapMethod('edit',          [ 'referee'           ]);
		$this->mapMethod('index',         [ 'players'           ], false);
		$this->mapMethod('view',          [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'infobalie' ]
			, 'findMethod' => 'withContain'
			]);

		$this->mapMethod('believesIndex', [ 'read-only'         ]);
		$this->mapMethod('factionsIndex', [ 'read-only'         ]);
		$this->mapMethod('groupsIndex',   [ 'read-only'         ]);
		$this->mapMethod('playersIndex',  [ 'read-only', 'user' ]);
		$this->mapMethod('worldsIndex',   [ 'read-only'         ]);
	}

	public function add($plin)
	{
		$plin = $plin ?: $this->request->getData('plin');
		$this->request = $this->request->withData('player_id', $plin);
		$this->request = $this->request->withData('plin', NULL);

		$next = $this->Characters->find()
					->select(['nextChin' => 'MAX(chin)'])
					->where(['player_id' => $plin])
					->enableHydration(false)
					->first();
		$next = $next ? $next['nextChin'] + 1: 1;
		$chin = $this->request->getData('chin') ?: $next;
		$this->request = $this->request->withData('chin', $chin);

		$this->edit(NULL);
	}

	public function edit($id)
	{
		// make the virtual fields assignable
		$this->dataNameToId('factions', 'faction');
		$this->dataNameToIdAndAddIfMissing('believes', 'belief');
		$this->dataNameToIdAndAddIfMissing('groups', 'group');
		$this->dataNameToIdAndAddIfMissing('worlds', 'world');

		$this->Crud->execute();
	}

	public function index()
	{
		$query = $this->Characters->find()
					->select([], true)
					->select('Characters.player_id')
					->select('Characters.chin')
					->select('Characters.name')
					->select('Characters.status');

		if(!AuthState::hasRole('read-only')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"]);
		} else {
			if($this->setResponseModified())
				return $this->response;
		}

		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$content[] =
				[ 'class' => 'Character'
				, 'url' => '/characters/'.$row[0].'/'.$row[1]
				, 'plin' => (int)$row[0]
				, 'chin' => (int)$row[1]
				, 'name' => $row[2]
				, 'status' => $row[3]
				];
		}
		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => rtrim($this->request->getPath(), '/')
			, 'list' => $content
			]);
	}

	public function queue($id)
	{
		$this->Crud->on('beforeRender', [$this, 'queueBeforeRender']);
		$this->Crud->execute();
	}

	public function queueBeforeRender(Event $event)
	{
		$table = $this->loadModel('lammies');

		$character = $event->getSubject()->entity;
		$table->save($table->newEntity()->set('target', $character));
		$count = 1;

		if(!is_null($this->request->getData('all'))) {
			foreach($character->powers as $p) {
				$table->save($table->newEntity()->set('target', $p));
				$count++;
			}
			foreach($character->conditions as $c) {
				$table->save($table->newEntity()->set('target', $c));
				$count++;
			}
			foreach($character->items as $i) {
				$table->save($table->newEntity()->set('target', $i));
				$count++;
			}
		}

		$event->getSubject()->entity = $count;
	}
}
