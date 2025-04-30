<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\TableRegistry;

class Item extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'deprecated'], true);
        $this->setVirtual(['plin', 'chin']);
        $this->setHidden(['character_id'], true);
    }

    protected function _getPlin(): ?int
    {
        return $this->get('character')?->player_id;
    }

    protected function _getChin(): ?int
    {
        return $this->get('character')?->chin;
    }

    public function codes(): array
    {
        $seed = $this->id;
        $attr = [];
        foreach ($this->get('attributes') as $relation) {
            $attribute = $relation->attribute;
            $seed *= $attribute->id;
            $attr[] = $attribute->code;
        }

        mt_srand((int)$seed);
        $order = $this->randomOrder();

        $key = -1;
        $output = [];
        foreach ($attr as $key => $val) {
            $output[$order[$key]] = $val;
        }
        $key++;

        $table = TableRegistry::getTableLocator()->get('Attributes');
        $max = $table->find()->enableHydration(false)
                    ->select(['max' => 'COUNT(*)'])
                    ->where(['category LIKE' => 'random'])
                    ->order(['id' => 'ASC'])
                    ->toArray()[0]['max'];

        for (; $key < 12; $key++) {
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

    private function randomOrder(): array
    {
        $order = $taken = [];
        for ($i = 11; $i >= 0; $i--) {
            $x = mt_rand(0, $i);
            while (isset($taken[$x])) {
                $x++;
            }
            $taken[$x] = true;
            $order[] = $x;
        }

        return $order;
    }
}
