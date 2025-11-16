<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\History;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class HistoryTable extends Table
{
    use LocatorAwareTrait;

    public function initialize(array $config): void
    {
        parent::initialize($config);
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
        $tbls = [
            'Players',
            'Characters',
            'Imbues',
            'Powers',
            'Conditions',
            'Items',
        ];

        if (empty($what) || !in_array($what, $tbls)) {
            $what = null;
        }

        $where = [];
        if (is_null($since)) {
            $where['modified IS NOT'] = 'NULL';
        } else {
            $where['modified >'] = $since;
        }
        if (!is_null($byPlin)) {
            $where['modifier_id'] = $byPlin;
        }

        $list = [];
        foreach ($tbls as $tbl) {
            if (!is_null($what) && $tbl != $what) {
                continue;
            }

            $query = $this->fetchTable($tbl)->find()
                ->where($where)
                ->orderBy(['modified' => 'DESC'], true);

            foreach ($query->all() as $row) {
                $list[] = History::fromEntity($row);
            }
        }
        usort($list, [History::class, 'compare']);

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
        if (is_null($what)) {
            $what = '';
        }

        switch (strtolower($what)) {
            case 'players':
                $where['entity'] = 'player';
                break;
            case 'characters':
                $where['entity'] = 'character';
                break;
            case 'imbues':
                $where['entity'] = 'imbue';
                break;
            case 'powers':
                $where['entity'] = 'power';
                break;
            case 'conditions':
                $where['entity'] = 'condition';
                break;
            case 'items':
                $where['entity'] = 'item';
                break;
            default:
                $where['entity IN'] = ['player', 'character', 'imbue', 'power', 'condition', 'item'];
                break;
        }

        $list = $this->find()
            ->where($where)
            ->all()
            ->toList();

        usort($list, [History::class, 'compare']);

        return $list;
    }

    public function getEntityHistory(string $entity, int $k1, ?int $k2): array
    {
        $list = match (strtolower($entity)) {
            'player' => $this->getPlayerHistory($k1),
            'character' => $this->getCharacterHistory($k1, $k2),
            'imbue' => $this->getImbueHistory($k1),
            'power' => $this->getPowerHistory($k1),
            'condition' => $this->getConditionHistory($k1),
            'item' => $this->getItemHistory($k1),
            default => [],
        };

        usort($list, [History::class, 'compare']);

        return $list;
    }

    protected function orderBy(): array
    {
        return ['modified' => 'DESC', 'id' => 'DESC'];
    }

    private function getPlayerHistory(int $plin): array
    {
        $list = $this->find()
            ->where(['entity' => 'Player', 'key1' => $plin])
            ->all()
            ->toList();

        $entity = $this->fetchTable('Players')->find()
            ->where(['plin' => $plin])
            ->first();
        if (!is_null($entity)) {
            $list[] = History::fromEntity($entity);
        }

        return $list;
    }

    private function getCharacterHistory(int $plin, int $chin): array
    {
        $entity = $this->fetchTable('Characters')->find('withContain')
            ->where(['Characters.plin' => $plin])
            ->where(['Characters.chin' => $chin])
            ->first();

        if (is_null($entity)) {
            return [];
        }

        $related = [
            'Character',
            'CharactersSkill',
            'CharactersGlyphImbue',
            'CharactersRuneImbue',
            'CharactersPower',
            'CharactersCondition',
            'Teaching',
        ];
        $list = $this->find()
            ->where(['entity IN' => $related, 'key1' => $entity->id])
            ->all()
            ->toList();

        $list[] = History::fromEntity($entity);

        if ($entity->teacher) {
            $list[] = History::fromEntity($entity->teacher);
        }

        foreach ($entity->glyphimbues as $imbue) {
            $relation = $imbue->_joinData;
            $relation->imbue = $imbue;
            $list[] = History::fromEntity($relation);
        }

        foreach ($entity->runeimbues as $imbue) {
            $relation = $imbue->_joinData;
            $relation->imbue = $imbue;
            $list[] = History::fromEntity($relation);
        }

        foreach ($entity->skills as $skill) {
            $relation = $skill->_joinData;
            $relation->skill = $skill;
            $list[] = History::fromEntity($relation);
        }

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

        return $list;
    }

    private function getImbueHistory(int $id): array
    {
        $list = $this->find()
            ->where(function (QueryExpression $exp) use ($id) {
                $a = $exp->and(['entity' => 'Imbue', 'key1' => $id]);
                $b = $exp->and(['entity LIKE' => 'Characters%Imbue', 'key2' => $id]);

                return $exp->or([$a, $b]);
            })
            ->all()
            ->toList();

        $entity = $this->getTableLocator()->get('Imbues')->find('withContain')
            ->where(['Imbues.id' => $id])
            ->first();
        if (!is_null($entity)) {
            $list[] = History::fromEntity($entity);

            foreach ($entity->characters as $character) {
                $relation = $character->_joinData;
                $relation->character = $character;
                $list[] = History::fromEntity($relation);
            }
        }

        return $list;
    }

    private function getPowerHistory(int $poin): array
    {
        $list = $this->find()
            ->where(function (QueryExpression $exp) use ($poin) {
                $a = $exp->and(['entity' => 'Power', 'key1' => $poin]);
                $b = $exp->and(['entity' => 'CharactersPower', 'key2' => $poin]);

                return $exp->or([$a, $b]);
            })
            ->all()
            ->toList();

        $entity = $this->fetchTable('Powers')->find('withContain')
            ->where(['Powers.poin' => $poin])
            ->first();
        if (!is_null($entity)) {
            $list[] = History::fromEntity($entity);

            foreach ($entity->characters as $character) {
                $relation = $character->_joinData;
                $relation->character = $character;
                $list[] = History::fromEntity($relation);
            }
        }

        return $list;
    }

    private function getConditionHistory(int $coin): array
    {
        $list = $this->find()
            ->where(function (QueryExpression $exp) use ($coin) {
                $a = $exp->and(['entity' => 'Condition', 'key1' => $coin]);
                $b = $exp->and(['entity' => 'CharactersCondition', 'key2' => $coin]);

                return $exp->or([$a, $b]);
            })
            ->all()
            ->toList();

        $entity = $this->fetchTable('Conditions')->find('withContain')
            ->where(['Conditions.coin' => $coin])
            ->first();
        if (!is_null($entity)) {
            $list[] = History::fromEntity($entity);

            foreach ($entity->characters as $character) {
                $relation = $character->_joinData;
                $relation->character = $character;
                $list[] = History::fromEntity($relation);
            }
        }

        return $list;
    }

    private function getItemHistory(int $itin): array
    {
        $list = $this->find()
            ->where(['entity' => 'Item', 'key1' => $itin])
            ->all()
            ->toList();

        $entity = $this->fetchTable('Items')->find('withContain')
            ->where(['Items.itin' => $itin])
            ->first();
        if (!is_null($entity)) {
            $list[] = History::fromEntity($entity);
        }

        return $list;
    }
}
