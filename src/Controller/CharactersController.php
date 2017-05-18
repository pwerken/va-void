<?php
namespace App\Controller;

use Cake\Event\Event;
use App\Model\Entity\Lammy;

class CharactersController
	extends AppController
{

	protected $searchFields =
		[ 'Characters.name'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',           [ 'infobalie'       ]);
		$this->mapMethod('delete',        [ 'super'           ]);
		$this->mapMethod('edit',          [ 'referee'         ]);
		$this->mapMethod('index',         [ 'referee'         ]);
		$this->mapMethod('view',          [ 'referee', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);

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

	public function edit($plin, $chin)
	{
		// make the virtual fields assignable
		$this->dataNameToId('factions', 'faction');
		$this->dataNameToIdAndAddIfMissing('believes', 'belief');
		$this->dataNameToIdAndAddIfMissing('groups', 'group');
		$this->dataNameToIdAndAddIfMissing('worlds', 'world');

		$this->Crud->execute();
	}

	public function queue($plin, $chin)
	{
		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel('lammies');

			$character = $event->subject()->entity;
			$table->save($table->newEntity()->set('target', $character));
			$count = 1;
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

			$event->subject()->entity = $count;
		});

		$this->Crud->execute();
	}

	private function dataNameToId($table, $field)
	{
		if(!array_key_exists($field, $this->request->data)) {
			return null;
		}

		$name = $this->request->data($field);
		unset($this->request->data[$field]);
		if(empty($name)) {
			$name = "-";
		}

		$model = $this->loadModel($table);
		$ids = $model->findByName($name)->select('id', true)
					->hydrate(false)->all();
		if($ids->count() == 0) {
			$this->request->data($field.'_id', -1);
		} else {
			$this->request->data($field.'_id', $ids->first()['id']);
		}
		return $name;
	}

	private function dataNameToIdAndAddIfMissing($table, $field)
	{
		$name = $this->dataNameToId($table, $field);
		$id = $this->request->data($field.'_id');
		if($id < 0) {
			$model = $this->loadModel($table);
			$obj = $model->newEntity();
			$obj->name = $name;
			$model->save($obj);
			$this->request->data($field.'_id', $obj->id);
		}
		return $name;
	}

}
