<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\History;
use App\Utility\Json;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorInterface;
use Cake\ORM\TableRegistry;

class HistoryTable extends Table
{
    private LocatorInterface $locator;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Characters')->setForeignKey('key1')
            ->setConditions(['History.entity LIKE' => 'Characters%']);
        $this->belongsTo('Conditions')->setForeignKey('key2')
            ->setConditions(['History.entity' => 'CharactersCondition']);
        $this->belongsTo('Powers')->setForeignKey('key2')
            ->setConditions(['History.entity' => 'CharactersPower']);
        $this->belongsTo('Skills')->setForeignKey('key2')
            ->setConditions(['History.entity' => 'CharactersSkill']);

        $this->belongsTo('Items')->setForeignKey('key2')
            ->setConditions(['History.entity LIKE' => '%sItem']);

        $this->locator = TableRegistry::getTableLocator();
    }

    public function logChange(EntityInterface $entity): History
    {
        $history = History::fromEntity($entity);
        $this->save($history);

        return $history;
    }

    public function logDeletion(EntityInterface $entity): History
    {
        $lastChange = $this->logChange($entity);

        $history = $this->newEntity([]);
        $history->set('entity', $lastChange->get('entity'));
        $history->set('key1', $lastChange->get('key1'));
        $history->set('key2', $lastChange->get('key2'));
        $history->set('data', null);

        $this->save($history);

        return $history;
    }

    public function getAllLastModified(?int $byPlin = null, ?string $since = null, ?string $what = null): array
    {
        $where = [];
        if (is_null($since)) {
            $where['modified IS NOT'] = 'NULL';
        } else {
            $where['modified >'] = $since;
        }
        if (!is_null($byPlin)) {
            $where['modifier_id'] = $byPlin;
        }

        $tbls = [
            'Players' => [
                'key1' => 'id',
                'key2' => 'id',
                'name' => 'first_name', 'first_name', 'insertion', 'last_name', 'modified', 'modifier_id',
            ],
            'Characters' => [
                'key1' => 'player_id',
                'key2' => 'chin',
                'name', 'modified', 'modifier_id',
            ],
            'Conditions' => [
                'key1' => 'id',
                'key2' => 'id',
                'name', 'modified', 'modifier_id',
            ],
            'Powers' => [
                'key1' => 'id',
                'key2' => 'id',
                'name', 'modified', 'modifier_id',
            ],
            'Items' => [
                'key1' => 'id',
                'key2' => 'id',
                'name', 'modified', 'modifier_id',
            ],
        ];
        if (empty($what) || !array_key_exists($what, $tbls)) {
            $what = null;
        }

        $list = [];
        foreach ($tbls as $tbl => $select) {
            if (!is_null($what) && $tbl != $what) {
                continue;
            }

            switch (strtolower($tbl)) {
                case 'players':
                    $entity = 'Player';
                    break;
                case 'characters':
                    $entity = 'Character';
                    break;
                case 'items':
                    $entity = 'Item';
                    break;
                case 'conditions':
                    $entity = 'Condition';
                    break;
                case 'powers':
                    $entity = 'Power';
                    break;
                default:
                    $entity = '';
                    break;
            }

            $result = $this->locator->get($tbl)->find()
                ->select($select)
                ->where($where)
                ->order(['modified' => 'DESC'])
                ->enableHydration(false)
                ->all();

            foreach ($result as $row) {
                if ($select['key1'] == $select['key2']) {
                    $row['key2'] = null;
                }
                if ($tbl == 'Players') {
                    $name = [$row['first_name'], $row['insertion'], $row['last_name']];
                    $row['name'] = implode(' ', array_filter($name));
                }
                $row['entity'] = $entity;
                $row['modified'] = (string)(new DateTime($row['modified']));
                $list[] = $row;
            }
        }
        usort($list, [$this, 'compare']);

        return $list;
    }

    public function getAllModificationsBy(?int $byPlin = null, ?string $since = null, ?string $what = null): array
    {
        $where = [];
        if (is_null($since)) {
            $where['modified IS NOT'] = 'NULL';
        } else {
            $where['modified >'] = $since;
        }
        if (!is_null($byPlin)) {
            $where['modifier_id'] = $byPlin;
        }

        switch (strtolower($what)) {
            case 'players':
                $where['entity'] = 'player';
                break;
            case 'characters':
                $where['entity'] = 'character';
                break;
            case 'items':
                $where['entity'] = 'item';
                break;
            case 'conditions':
                $where['entity'] = 'condition';
                break;
            case 'powers':
                $where['entity'] = 'power';
                break;
            default:
                $where['entity IN'] = ['player', 'character', 'item', 'condition', 'power'];
                break;
        }

        $result = $this->find()
            ->where($where)
            ->all();

        $list = [];
        foreach ($result as $obj) {
            $row = [];
            $row['entity'] = $obj->entity;
            $row['key1'] = $obj->key1;
            $row['key2'] = $obj->key2;
            $row['modified'] = $obj->modifiedString();
            $row['modifier_id'] = $obj->modifier_id;

            $data = Json::decode($obj->data);
            if ($obj->entity == 'Character') {
                $row['key1'] = $data['player_id'];
                $row['key2'] = $data['chin'];
            }

            if (isset($data['name'])) {
                if (is_null($data['name']) && $obj->entity == 'Player') {
                    $name = [$data['first_name'], $data['insertion'], $data['last_name']];
                    $row['name'] = implode(' ', array_filter($name));
                } else {
                    $row['name'] = $data['name'];
                }
            } else {
                $row['name'] = null;
            }
            $list[] = $row;
        }
        usort($list, [$this, 'compare']);

        return $list;
    }

    public function getEntityHistory(string $entity, int $key1, ?int $key2): array
    {
        switch (strtolower($entity)) {
            case 'player':
                return $this->getPlayerHistory($key1);
            case 'character':
                return $this->getCharacterHistory($key1, $key2);
            case 'condition':
                return $this->getConditionHistory($key1);
            case 'power':
                return $this->getPowerHistory($key1);
            case 'item':
                return $this->getItemHistory($key1);
            default:
                return [];
        }
    }

    protected function orderBy(): array
    {
        return ['modified' => 'DESC', 'id' => 'DESC'];
    }

    private function getPlayerHistory(int $plin): array
    {
        $entity = $this->locator->get('Players')->find()
            ->where(['id' => $plin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $history = $this->find()
            ->where(['entity' => 'Player', 'key1' => $plin])
            ->all()
            ->toList();

        $history[] = History::fromEntity($entity);
        usort($history, ['App\Model\Entity\History', 'compare']);

        return $history;
    }

    private function getCharacterHistory(int $plin, int $chin): array
    {
        $entity = $this->locator->get('Characters')->find('withContain')
            ->where(['Characters.player_id' => $plin])
            ->where(['Characters.chin' => $chin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $list = [History::fromEntity($entity)];

        foreach ($entity->conditions as $condition) {
            $relation = $condition->_joinData;
            $relation->condition = $condition;
            $list[] = History::fromEntity($relation);
        }

        foreach ($entity->powers as $power) {
            $relation = $power->_joinData;
            $relation->power = $power;
            $list[] = History::fromEntity($relation);
        }

        foreach ($entity->skills as $skill) {
            $relation = $skill->_joinData;
            $relation->skill = $skill;
            $list[] = History::fromEntity($relation);
        }

        $history = $this->find()
            ->where(['entity LIKE' => 'Character%', 'key1' => $entity->id])
            ->contain(['Conditions', 'Powers', 'Skills'])
            ->all()
            ->toList();

        $list = array_merge($list, $history);
        usort($list, ['App\Model\Entity\History', 'compare']);

        return $list;
    }

    private function getConditionHistory(int $coin): array
    {
        $entity = $this->locator->get('Conditions')->find('withContain')
            ->where(['Conditions.id' => $coin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $list = [History::fromEntity($entity)];

        foreach ($entity->characters as $character) {
            $relation = $character->_joinData;
            $relation->character = $character;
            $list[] = History::fromEntity($relation);
        }

        $history = $this->find()
            ->where(function (QueryExpression $exp) use ($coin) {
                $a = $exp->and(['entity' => 'Condition', 'key1' => $coin]);
                $b = $exp->and(['entity' => 'CharactersCondition', 'key2' => $coin]);

                return $exp->or([$a, $b]);
            })
            ->contain(['Characters'])
            ->all()
            ->toList();

        $list = array_merge($list, $history);
        usort($list, ['App\Model\Entity\History', 'compare']);

        return $list;
    }

    private function getPowerHistory(int $poin): array
    {
        $entity = $this->locator->get('Powers')->find('withContain')
            ->where(['Powers.id' => $poin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $list = [History::fromEntity($entity)];

        foreach ($entity->characters as $character) {
            $relation = $character->_joinData;
            $relation->character = $character;
            $list[] = History::fromEntity($relation);
        }

        $history = $this->find()
            ->where(function (QueryExpression $exp) use ($poin) {
                $a = $exp->and(['entity' => 'Power', 'key1' => $poin]);
                $b = $exp->and(['entity' => 'CharactersPower', 'key2' => $poin]);

                return $exp->or([$a, $b]);
            })
            ->contain(['Characters'])
            ->all()
            ->toList();

        $list = array_merge($list, $history);
        usort($list, ['App\Model\Entity\History', 'compare']);

        return $list;
    }

    private function getItemHistory(int $itin): array
    {
        $entity = $this->locator->get('Items')->find('withContain')
            ->where(['Items.id' => $itin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $list = [History::fromEntity($entity)];

        $history = $this->find()
            ->where(['entity' => 'Item', 'key1' => $itin])
            ->all()
            ->toList();

        $list = array_merge($list, $history);
        usort($list, ['App\Model\Entity\History', 'compare']);

        return $list;
    }

    public static function compare(History|array|null $a, History|array|null $b): int
    {
        if (is_null($a) && is_null($b)) {
            return 0;
        } elseif (is_null($a)) {
            return 1;
        } elseif (is_null($b)) {
            return -1;
        }

        $cmp = strcmp($b['modified'], $a['modified']);
        if ($cmp != 0) {
            return $cmp;
        }

        $cmp = strcmp($a['entity'], $b['entity']);
        if ($cmp != 0) {
            return -$cmp;
        }

        $cmp = $a['key1'] - $b['key1'];
        if ($cmp != 0) {
            return $cmp;
        }

        return $a['key2'] - $b['key2'];
    }
}
