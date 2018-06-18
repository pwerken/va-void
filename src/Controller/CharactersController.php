<?php
namespace App\Controller;

use App\Model\Entity\Lammy;
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

		if(!$this->hasAuth('read-only')) {
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
			, 'url' => '/' . rtrim($this->request->url, '/')
			, 'list' => $content
			]);
	}

	public function queue($id)
	{
		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel('lammies');

			$character = $event->subject()->entity;
			$table->save($table->newEntity()->set('target', $character));
			$count = 1;

			if(isset($this->request->data['all'])) {
				foreach($character->powers as $power) {
					$table->save($table->newEntity()->set('target', $power));
					$count++;
				}
				foreach($character->conditions as $condition) {
					$table->save($table->newEntity()->set('target', $condition));
					$count++;
				}
				foreach($character->items as $item) {
					$table->save($table->newEntity()->set('target', $item));
					$count++;
				}
			}

			$event->subject()->entity = $count;
		});

		$this->Crud->execute();
	}

}
