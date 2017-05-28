<?php
namespace App\Controller;

class PowersController
	extends AppController
{

	protected $searchFields =
		[ 'Powers.name'
		, 'Powers.player_text'
		, 'Powers.cs_text'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'referee'         ]);
		$this->mapMethod('delete', [ 'super'           ]);
		$this->mapMethod('edit',   [ 'referee'         ]);
		$this->mapMethod('index',  [ 'referee'         ]);
		$this->mapmethod('view',   [ 'referee', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Powers->find()
					->select(['Powers.id', 'Powers.name'], true);
		$this->doRawIndex($query, 'Power', '/powers/', 'poin');
	}

	public function queue($poin)
	{
		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel('lammies');
			$entity = $event->subject()->entity;
			$table->save($table->newEntity()->set('target', $entity));
			$event->subject()->entity = 1;
		});

		$this->Crud->execute();
	}

	protected function hasAuthUser($id = null)
	{
		$poin = $this->request->param('poin');
		$this->loadModel('CharactersPowers');
		$data = $this->CharactersPowers->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['CharactersPowers.power_id' => $poin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
