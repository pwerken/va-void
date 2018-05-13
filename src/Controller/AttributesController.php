<?php
namespace App\Controller;

class AttributesController
	extends AppController
{

	protected $searchFields =
		[ 'Attributes.name'
		, 'Attributes.code'
		];

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('add',    [ 'super'     ]);
		$this->mapMethod('delete', [ 'super'     ]);
		$this->mapMethod('edit',   [ 'super'     ]);
		$this->mapMethod('index',  [ 'read-only' ]);
		$this->mapMethod('view',   [ 'read-only' ]);
	}

	public function index()
	{
		$query = $this->Attributes->find()
					->select([], true)
					->select('Attributes.id')
					->select('Attributes.name')
					->select('Attributes.code');
		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$content[] =
				[ 'class' => 'Attribute'
				, 'url' => '/attributes/'.$row[0]
				, 'id' => (int)$row[0]
				, 'name' => $row[1]
				, 'code' => $row[2]
				];
		}
		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => '/' . rtrim($this->request->url, '/')
			, 'list' => $content
			]);
	}

}
