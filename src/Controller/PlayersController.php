<?php
namespace App\Controller;

use App\Utility\AuthState;

class PlayersController
	extends AppController
{

	protected $searchFields =
		[ 'Players.first_name'
		, 'Players.insertion'
		, 'Players.last_name'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'             ]);
		$this->mapMethod('edit',   [ 'infobalie', 'user' ]);
		$this->mapMethod('delete', [ 'super'             ]);
		$this->mapMethod('index',  [ 'players'           ]);
		$this->mapMethod('view',   [ 'read-only', 'user' ], true);
	}

	public function index()
	{
		if($this->setResponseModified())
			return $this->response;

		$query = $this->Players->find()
					->select([], true)
					->select('Players.id')
					->select('Players.first_name')
					->select('Players.insertion')
					->select('Players.last_name');

		if(!AuthState::hasRole('read-only')) {
			$plin = $this->Auth->user('id');
			$query->where(["Players.id = $plin"]);
		}

		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$name = $row[1];
			if(!empty($row[2]))
				$name .= ' '.$row[2];
			$name .= ' '.$row[3];

			$content[] =
				[ 'class' => 'Player'
				, 'url' => '/players/'.$row[0]
				, 'plin' => (int)$row[0]
				, 'full_name' => $name
				];
		}
		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => rtrim($this->request->getPath(), '/')
			, 'list' => $content
			]);
	}

	public function add()
	{
		$plin = $this->request->getData('plin');
		$this->request = $this->request->withData('id', $plin);
		$this->Crud->execute();
	}

}
