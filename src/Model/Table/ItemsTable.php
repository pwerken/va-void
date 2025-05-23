<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\AttributesItemsTable $AttributesItems;
 */
class ItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Characters');

        $this->hasMany('AttributesItems')->setProperty('attributes');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('character_id', 'Characters'));

        $rules->addDelete([$this, 'ruleNoAttributes']);
        $rules->addDelete([$this, 'ruleNoCharacter']);

        return $rules;
    }

    public function ruleNoAttributes(EntityInterface $entity, array $options): bool
    {
        $query = $this->AttributesItems->find();
        $query->where(['item_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('attributes', $this->consistencyError);

            return false;
        }

        return true;
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
            $query = $this->find()->enableHydration(false)->select(['id' => 'MAX(id)']);
            if ($max > 0) {
                $query->where(['id <' => $max]);
            }

            $newID = $query->first()['id'] + 1;
            if ($newID < $max || $max < 0) {
                return (string)$newID;
            }
        }

        return null;
    }

    protected function contain(): array
    {
        return ['Characters', 'AttributesItems.Attributes'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
