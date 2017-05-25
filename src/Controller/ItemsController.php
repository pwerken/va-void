<?php
namespace App\Controller;

class ItemsController
	extends AppController
{

	protected $searchFields =
		[ 'Items.name'
		, 'Items.description'
		, 'Items.player_text'
		, 'Items.cs_text'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',              [ 'referee'         ]);
		$this->mapMethod('delete',           [ 'super'           ]);
		$this->mapMethod('edit',             [ 'referee'         ]);
		$this->mapMethod('index',            [ 'referee'         ], true);
		$this->mapMethod('view',             [ 'referee', 'user' ], true);

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
	}

	public function add()
	{
		if(array_key_exists('plin', $this->request->data)
		|| array_key_exists('chin', $this->request->data))
		{
			$plin = $this->request->data('plin');
			$chin = $this->request->data('chin');

			if($plin || $chin) {
				$this->loadModel('Characters');
				$char = $this->Characters->findByPlayerIdAndChin($plin, $chin)->first();
				$this->request->data('character_id', $char ? $char->id : -1);
			} else {
				$this->request->data('character_id', null);
			}
		}
		unset($this->request->data['plin']);
		unset($this->request->data['chin']);

		return $this->Crud->execute();
	}

	public function edit($itin)
	{
		return $this->add();
	}

	public function index()
	{
		$query = 'SELECT `items`.`id`, `items`.`name`, `items`.`expiry`,'
					.' `characters`.`player_id`, `characters`.`chin`,'
					.' `characters`.`name`, `characters`.`status`'
				.' FROM `items`'
				.' LEFT JOIN `characters`'
					.' ON `characters`.`id` = `items`.`character_id`';
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
		$this->set('viewVar', $content);
	}

	protected function hasAuthUser($id = null)
	{
		$itin = $this->request->param('itin');
		$data = $this->Items->find()
					->hydrate(false)
					->select(['player_id' => 'Characters.player_id'])
					->where(['Items.id' => $itin])
					->contain('Characters')
					->first();
		return parent::hasAuthUser(@$data['player_id']);
	}

}
