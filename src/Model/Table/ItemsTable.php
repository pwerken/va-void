<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\History;
use App\Model\Entity\Item;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

/**
 * @extends \App\Model\Table\Table<\App\Model\Entity\Item>
 *
 * Relations:
 * @property \App\Model\Table\CharactersTable $Characters;
 * @property \App\Model\Table\ManatypesTable  $Manatypes;
 */
class ItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('itin');

        $this->belongsTo('Manatypes');
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

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // store (fake) CharactersItem relation in History table
        $newCharId = $entity->get('character_id');
        $oldCharId = $entity->getOriginal('character_id');

        if ($newCharId == $oldCharId) {
            return;
        }

        if (!$entity->isNew() && !is_null($oldCharId)) {
            $unlinkItem = new History();
            $unlinkItem->set('entity', 'CharactersItem');
            $unlinkItem->set('key1', $oldCharId);
            $unlinkItem->set('key2', $entity->get('itin'));
            $unlinkItem->set('modified', $entity->get('modified'));
            $unlinkItem->set('modifier_id', $entity->get('modifier_id'));

            $this->fetchTable('History')->save($unlinkItem);
            $this->touchEntity('Characters', $oldCharId);
        }

        if (!is_null($newCharId)) {
            $linkItem = new History();
            $linkItem->set('entity', 'CharactersItem');
            $linkItem->set('key1', $newCharId);
            $linkItem->set('key2', $entity->get('itin'));
            $linkItem->set('data', '{}');
            $linkItem->set('modified', $entity->get('modified'));
            $linkItem->set('modifier_id', $entity->get('modifier_id'));

            $this->fetchTable('History')->save($linkItem);
            $this->touchEntity('Characters', $newCharId);
        }
    }

    public function findIndex(SelectQuery $query): SelectQuery
    {
        return $this->findWithContain($query);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoCharacter']);

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('manatype_id', 'Manatypes'));

        $rules->add([$this, 'ruleDisallowConceptCharacter']);
        $rules->add([$this, 'ruleManaConsistency']);

        return $rules;
    }

    public function ruleNoCharacter(Item $entity, array $options): bool
    {
        if ($entity->hasValue('character_id')) {
            $entity->setError('character_id', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function _newID(array $primary): ?string
    {
        $holes = [ 8001, 8888, 9000, 9999, -1 ];
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
        return ['Characters', 'Manatypes'];
    }

    protected function orderBy(): array
    {
        return ['itin' => 'ASC'];
    }
}
