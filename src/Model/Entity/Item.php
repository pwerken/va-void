<?php
namespace App\Model\Entity;

use Cake\ORM\TableRegistry;

class Item
	extends AppEntity
{

	protected $_showAuth =
			[ 'cs_text'         => 'referee'
			, 'attributes'      => 'referee'
			];

	protected $_compact = [ 'id', 'name', 'expiry', 'character' ];

	protected $_hidden = [ 'character_id' ];

	protected function _getPlin()
	{
		return @$this->character->player_id;
	}

	protected function _getChin()
	{
		return @$this->character->chin;
	}

	public function codes()
	{
		$seed = $this->id;
		$attr = [];
		foreach($this->attributes as $attribute) {
			$seed *= $attribute->id;
			$attr[] = $attribute->code;
		}

		mt_srand($seed);
		$order = $this->randomOrder();

		$key = -1;
		$output = [];
		foreach($attr as $key => $val)
			$output[$order[$key]] = $val;

		$key++;

		$table = TableRegistry::get('Attributes');
		$max = $table->find()->hydrate(false)
					->select(['max' => 'COUNT(*)'])
					->where(['category LIKE' => 'random'])
					->order(['id' => 'ASC'])
					->toArray()[0]['max'];

		for( ; $key < 12; $key++) {
			$code = $table->find()->hydrate(false)
					->select(['code'])
					->where(['category LIKE' => 'random'])
					->order(['id' => 'ASC'])
					->limit(1)->page(mt_rand(0, $max - 1))
					->toArray()[0]['code'];
			$output[$order[$key]] = $code;
		}
		return $output;
	}

	private function randomOrder()
	{
		$order = $taken = [];
		for($i = 11; $i >= 0; $i--) {
			$x = mt_rand(0, $i);
			while(isset($taken[$x]))
				$x++;

			$taken[$x] = true;
			$order[] = $x;
		}
		return $order;
	}

}
