<?php
namespace App\Model\Entity;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\TableRegistry;

class Item
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['expiry', 'character'], true);
		$this->setVirtual(['plin', 'chin']);
		$this->addHidden(['character_id']);

		$this->showFieldAuth('attributes', ['read-only']);
		$this->showFieldAuth('referee_notes', ['read-only']);
		$this->showFieldAuth('notes', ['read-only']);
	}

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
		foreach($this->attributes as $relation) {
			$attribute = $relation->attribute;
			$seed *= $attribute->id;
			$attr[] = $attribute->code;
		}

		mt_srand((int)$seed);
		$order = $this->randomOrder();

		$key = -1;
		$output = [];
		foreach($attr as $key => $val)
			$output[$order[$key]] = $val;

		$key++;

		$table = TableRegistry::get('Attributes');
		$max = $table->find()->enableHydration(false)
					->select(['max' => 'COUNT(*)'])
					->where(['category LIKE' => 'random'])
					->order(['id' => 'ASC'])
					->toArray()[0]['max'];

		for( ; $key < 12; $key++) {
			$code = $table->find()->enableHydration(false)
					->select(['code'])
					->where(['category LIKE' => 'random'])
					->order(['id' => 'ASC'])
					->limit(1)->page(mt_rand(1, $max))
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
