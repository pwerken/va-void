<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\AuthState;

class ItemsController
	extends AppController
{

	protected $searchFields =
		[ 'Items.name'
		, 'Items.description'
		, 'Items.player_text'
		, 'Items.referee_notes'
		, 'Items.notes'
		];

	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('QueueLammy');

		$this->mapMethod('add',              [ 'referee'           ]);
		$this->mapMethod('delete',           [ 'super'             ]);
		$this->mapMethod('edit',             [ 'referee'           ]);
		$this->mapMethod('index',            [ 'players'           ], true);
		$this->mapMethod('view',             [ 'read-only', 'user' ], true);

		$this->mapMethod('charactersIndex',  [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('queue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function add()
	{
		$plin = $this->request->getData('plin');
		$chin = $this->request->getData('chin');
		$this->request = $this->request->withoutData('plin');
		$this->request = $this->request->withoutData('chin');

		if($plin || $chin) {
			$this->loadModel('Characters');
			$char = $this->Characters->findByPlayerIdAndChin($plin, $chin)->first();
			$char_id = $char ? $char->id : -1;
			$this->request = $this->request->withData('character_id', $char_id);
		} else {
			$this->request = $this->request->withData('character_id', null);
		}

		return $this->Crud->execute();
	}

	public function edit($itin)
	{
		return $this->add();
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Items->find()
					->select([], true)
					->select('Items.id')
					->select('Items.name')
					->select('Items.expiry')
					->select('Characters.player_id')
					->select('Characters.chin')
					->select('Characters.name')
					->select('Characters.status')
					->leftJoin(['Characters' => 'characters'],
						    ['Characters.id = Items.character_id']);

		if(!AuthState::hasRole('read-only')) {
			$plin = $this->Auth->user('id');
			$query->where(["Characters.player_id = $plin"]);
		}

		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$char = NULL;
			if(!is_null($row[3])) {
				$char = [ 'class' => 'Character'
						, 'url' => '/characters/'.$row[3].'/'.$row[4]
						, 'plin' => (int)$row[3]
						, 'chin' => (int)$row[4]
						, 'name' => $row[5]
						, 'status' => $row[6]
						];
			}

			$content[] =
				[ 'class' => 'Item'
				, 'url' => '/items/'.$row[0]
				, 'itin' => (int)$row[0]
				, 'name' => $row[1]
				, 'expiry' => $row[2]
				, 'character' => $char
				];
		}
		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => rtrim($this->request->getPath(), '/')
			, 'list' => $content
			]);
	}

	public function queue($itin)
	{
		$this->QueueLammy->execute();
	}

	protected function wantAuthUser(): ?int
	{
		$itin = $this->request->getParam('itin');
		if (is_null($itin))
			return parent::wantAuthUser();

		$data = $this->Items->find()
					->enableHydration(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();

		if (is_null($data))
			return parent::wantAuthUser();

		return $data['player_id'];
	}
}
