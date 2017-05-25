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

		$this->mapMethod('add',    [ 'super'   ]);
		$this->mapMethod('delete', [ 'super'   ]);
		$this->mapMethod('edit',   [ 'super'   ]);
		$this->mapMethod('index',  [ 'referee' ]);
		$this->mapMethod('view',   [ 'referee' ]);
	}

	public function index()
	{
		$query = 'SELECT `attributes`.`id`, `attributes`.`name`,'
					.' `attributes`.`code`'
				.' FROM `attributes`'
				.' ORDER BY `attributes`.`name` ASC, `attributes`.`id` ASC';
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
		$this->set('viewVar', $content);
	}

}
