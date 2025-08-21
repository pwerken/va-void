<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Closure;
use Psr\SimpleCache\CacheInterface;

/**
 * @property \App\Model\Table\CharactersTable $Characters;
 * @property \App\Model\Table\ImbuesTable $Imbues;
 */
class CharactersImbuesTable extends Table
{
    protected bool $allowSetPrimaryOnCreate = true;

    protected string $type = '';

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('characters_imbues');

        $this->setPrimaryKey(['character_id', 'imbue_id', 'type']);

        $this->belongsTo('Characters');
        $this->belongsTo('Imbues');
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addCreate($rules->isUnique(['character_id', 'imbue_id', 'type']));
        $rules->addCreate([$this, 'ruleDisallowDeprecated']);
        $rules->addCreate([$this, 'ruleLimitTimesToMax']);

        $rules->addUpdate([$this, 'ruleLimitTimesToMax']);

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('imbue_id', 'Imbues'));

        return $rules;
    }

    public function ruleDisallowDeprecated(EntityInterface $entity, array $options): bool
    {
        $imbue = $this->Imbues->getMaybe($entity->get('imbue_id'));
        if ($imbue?->get('deprecated')) {
            $entity->setError('imbue_id', ['deprecated' => 'Imbue is deprecated']);

            return false;
        }

        return true;
    }

    public function ruleLimitTimesToMax(EntityInterface $entity, array $options): bool
    {
        $imbue = $this->Imbues->getMaybe($entity->get('imbue_id'));
        if (!is_null($imbue) && $entity->get('times') > $imbue->get('times_max')) {
            $entity->setError('times', ['limit' => 'Value exceeds imbue times_max']);

            return false;
        }

        return true;
    }

    public function get(
        mixed $primaryKey,
        array|string $finder = 'all',
        CacheInterface|string|null $cache = null,
        Closure|string|null $cacheKey = null,
        mixed ...$args,
    ): EntityInterface {
        // if set append imbue 'type' to the query operation
        if (is_array($primaryKey) && !empty($this->type)) {
            $primaryKey[] = $this->type;
        }

        return parent::get($primaryKey, $finder, $cache, $cacheKey, ...$args);
    }

    public function findAll(SelectQuery $query): SelectQuery
    {
        $query = parent::findAll($query);

        if (!empty($this->type)) {
            $query->where(['type' => $this->type]);
        }

        return $query;
    }

    protected function contain(): array
    {
        return ['Characters', 'Imbues'];
    }
}
