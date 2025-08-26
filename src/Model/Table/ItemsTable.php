<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

class ItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('itin');

        $this->belongsTo('Characters');
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        $array = $data->getArrayCopy();
        if (array_key_exists('plin', $array) && array_key_exists('chin', $array)) {
            if (is_null($data['plin']) || is_null($data['chin'])) {
                $data['character_id'] = null;
            } else {
                $char = $this->fetchTable('Characters')
                            ->findByPlinAndChin($data['plin'], $data['chin'])
                            ->first();
                $data['character_id'] = ($char ? $char['id'] : -1);
            }
        }

        parent::beforeMarshal($event, $data, $options);
    }

    public function findIndex(SelectQuery $query): SelectQuery
    {
        return $this->findWithContain($query);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->add($rules->existsIn('character_id', 'Characters'));

        $rules->addDelete([$this, 'ruleNoCharacter']);

        return $rules;
    }

    public function ruleNoCharacter(EntityInterface $entity, array $options): bool
    {
        if (!empty($entity->get('character_id'))) {
            $entity->setError('character_id', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function _newID(array $primary): ?string
    {
        $holes = [ 1980, 2201, 2300, 8001, 8888, 9000, 9999, -1 ];
        foreach ($holes as $max) {
            $query = $this->find()->enableHydration(false)->select(['itin' => 'MAX(itin)']);
            if ($max > 0) {
                $query->where(['itin <' => $max]);
            }

            $newID = $query->first()['itin'] + 1;
            if ($newID < $max || $max < 0) {
                return (string)$newID;
            }
        }

        return null;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['itin' => 'ASC'];
    }
}
