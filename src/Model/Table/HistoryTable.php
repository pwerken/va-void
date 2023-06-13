<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Type\DateTimeType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

use App\Model\Entity\History;

class HistoryTable
    extends AppTable
{
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

        $this->belongsTo('Attributes')->setForeignKey('key1')
            ->setConditions(['History.entity' => 'AttributesItem']);
        $this->belongsTo('Items')->setForeignKey('key2')
            ->setConditions(['History.entity LIKE' => '%sItem']);
    }

    public function logChange(EntityInterface $entity)
    {
        $history = History::fromEntity($entity);
        $this->save($history);
        return $history;
    }

    public function logDeletion(EntityInterface $entity)
    {
        $lastChange = $this->logChange($entity);

        $history = $this->newEntity([]);
        $history->set('entity', $lastChange->get('entity'));
        $history->set('key1', $lastChange->get('key1'));
        $history->set('key2', $lastChange->get('key2'));
        $history->set('data', NULL);

        $this->save($history);

        return $history;
    }

    public function getAllLastModified($byPlin = NULL, $since = NULL, $what = NULL)
    {
        $where = [];
        if($since == NULL) {
            $where['modified IS NOT'] = 'NULL';
        } else {
            $where['modified >'] = $since;
        }
        if($byPlin != NULL) {
            $where['modifier_id'] = $byPlin;
        }

        $tbls =
            [ 'Players' =>
                [ 'key1' => 'id', 'key2' => 'NULL'
                , 'name' => 'NULL', 'first_name', 'insertion', 'last_name'
                , 'modified', 'modifier_id']
            , 'Characters' =>
                [ 'key1' => 'player_id', 'key2' => 'chin'
                , 'name', 'modified' , 'modifier_id']
            , 'Conditions' =>
                [ 'key1' => 'id', 'key2' => 'NULL'
                , 'name', 'modified', 'modifier_id']
            , 'Powers' =>
                [ 'key1' => 'id', 'key2' => 'NULL'
                , 'name', 'modified', 'modifier_id']
            , 'Items' =>
                [ 'key1' => 'id', 'key2' => 'NULL'
                , 'name', 'modified', 'modifier_id']
            ];
        if(empty($what) OR !array_key_exists($what, $tbls)) {
            $what = NULL;
        }
        $dateParser = new DateTimeType();
        $list = [];
        foreach($tbls as $tbl => $select) {
            if(!is_null($what) and $tbl != $what) {
                continue;
            }
            switch(strtolower($tbl)) {
            case 'players':     $entity = 'Player';     break;
            case 'characters':  $entity = 'Character';  break;
            case 'items':       $entity = 'Item';       break;
            case 'conditions':  $entity = 'Condition';  break;
            case 'powers':      $entity = 'Power';      break;
            default:            $entity = '';           break;
            }

            $result = TableRegistry::get($tbl)->find()
                ->select($select)->where($where)
                ->order(['modified' => 'DESC'])->enableHydration(false)->all();
            foreach($result as $row) {
                if(is_null($row['name']) && $tbl == 'Players') {
                    $name = [$row['first_name'], $row['insertion'], $row['last_name']];
                    $row['name'] = implode(' ', array_filter($name));
                }
                $row['entity'] = $entity;
                $row['modified'] = $dateParser->marshal($row['modified'])->jsonSerialize();
                $list[] = $row;
            }
        }
        usort($list, array($this, 'compare'));
        return $list;
    }

    public function getAllModificationsBy($byPlin = NULL, $since = NULL, $what = NULL)
    {
        $where = [];
        $all = ['player', 'character', 'item', 'condition', 'power'];
        switch(strtolower($what)) {
        case 'players':     $where['entity'] = 'player';    break;
        case 'characters':  $where['entity'] = 'character'; break;
        case 'items':       $where['entity'] = 'item';      break;
        case 'conditions':  $where['entity'] = 'condition'; break;
        case 'powers':      $where['entity'] = 'power';     break;
        default:            $where['entity IN'] = $all;     break;
        }
        if($since == NULL) {
            $where['modified IS NOT'] = 'NULL';
        } else {
            $where['modified >'] = $since;
        }
        if($byPlin != NULL) {
            $where['modifier_id'] = $byPlin;
        }

        $dateParser = new DateTimeType();

        $result = $this->find()
            ->where($where)
            ->all();
        $list = [];
        foreach($result as $obj)
        {
            $row = [];
            $row['entity'] = $obj->entity;
            $row['key1'] = $obj->key1;
            $row['key2'] = $obj->key2;
            $row['modified'] = $obj->modifiedString();
            $row['modifier_id'] = $obj->modifier_id;

            $data = json_decode($obj->data ?? '', true);
            if($obj->entity == 'Character') {
                $row['key1'] = $data['player_id'];
                $row['key2'] = $data['chin'];
            }

            if(is_null(@$data['name']) && $obj->entity == 'Player') {
                $name = [$data['first_name'], $data['insertion'], $data['last_name']];
                $row['name'] = implode(' ', array_filter($name));
            } else {
                $row['name'] = @$data['name'];
            }
            $list[] = $row;
        }

        usort($list, array($this, 'compare'));
        return $list;
    }

    public function getEntityHistory($entity, $key1, $key2)
    {
        switch(strtolower($entity)) {
        case 'player':      return $this->getPlayerHistory($key1);
        case 'character':   return $this->getCharacterHistory($key1, $key2);
        case 'condition':   return $this->getConditionHistory($key1);
        case 'power':       return $this->getPowerHistory($key1);
        case 'item':        return $this->getItemHistory($key1);
        default:            return [];
        }
    }

    protected function orderBy(): array
    {
        return [ 'modified' => 'DESC', 'id' => 'DESC' ];
    }

    private function getPlayerHistory($plin)
    {
        $entity = TableRegistry::get('Players')->find()
            ->where(['id' => $plin])
            ->first();

        if(is_null($entity))
            return [];

        $list = $this->find()
            ->where(['entity' => 'Player', 'key1' => $plin])
            ->all()->toList();

        $list[] = History::fromEntity($entity);

        usort($list, array("App\Model\Entity\History", "compare"));
        return $list;
    }

    private function getCharacterHistory($plin, $chin)
    {
        $entity = TableRegistry::get('Characters')->findWithContain()
            ->where(['Characters.player_id' => $plin])
            ->where(['Characters.chin' => $chin])
            ->first();

        if(is_null($entity))
            return [];

        $id = $entity->get('id');
        $list = $this->find()
            ->where(['entity LIKE' => 'Character%', 'key1' => $id])
            ->contain(['Conditions', 'Powers', 'Skills'])
            ->all()->toList();

        $list[] = History::fromEntity($entity);
        foreach($entity->get('conditions') as $condition) {
            $list[] = History::fromEntity($condition);
        }
        foreach($entity->get('powers') as $power) {
            $list[] = History::fromEntity($power);
        }
        foreach($entity->get('skills') as $skill) {
            $list[] = History::fromEntity($skill);
        }

        usort($list, array("App\Model\Entity\History", "compare"));
        return $list;
    }

    private function getConditionHistory($coin)
    {
        $entity = TableRegistry::get('Conditions')->findWithContain()
            ->where(['Conditions.id' => $coin])
            ->first();

        if(is_null($entity))
            return [];

        $list = $this->find()
            ->where(function(QueryExpression $exp) use ($coin) {
                $a = $exp->and(['entity' => 'Condition', 'key1' => $coin]);
                $b = $exp->and(['entity' => 'CharactersCondition', 'key2' => $coin]);
                return $exp->or([$a, $b]);
            })
            ->contain(['Characters'])
            ->all()->toList();

        $list[] = History::fromEntity($entity);
        foreach($entity->get('characters') as $character) {
            $list[] = History::fromEntity($character);
        }

        usort($list, array("App\Model\Entity\History", "compare"));
        return $list;
    }

    private function getPowerHistory($poin)
    {
        $entity = TableRegistry::get('Powers')->findWithContain()
            ->where(['Powers.id' => $poin])
            ->first();

        if(is_null($entity))
            return [];

        $list = $this->find()
            ->where(function(QueryExpression $exp) use ($poin) {
                $a = $exp->and(['entity' => 'Power', 'key1' => $poin]);
                $b = $exp->and(['entity' => 'CharactersPower', 'key2' => $poin]);
                return $exp->or([$a, $b]);
            })
            ->contain(['Characters'])
            ->all()->toList();

        $list[] = History::fromEntity($entity);
        foreach($entity->get('characters') as $character) {
            $list[] = History::fromEntity($character);
        }

        usort($list, array("App\Model\Entity\History", "compare"));
        return $list;
    }

    private function getItemHistory($itin)
    {
        $entity = TableRegistry::get('Items')->findWithContain()
            ->where(['Items.id' => $itin])
            ->first();

        if(is_null($entity))
            return [];

        $list = $this->find()
            ->where(function(QueryExpression $exp) use ($itin) {
                $a = $exp->and(['entity' => 'Item', 'key1' => $itin]);
                $b = $exp->and(['entity' => 'AttributesItem', 'key2' => $itin]);
                return $exp->or([$a, $b]);
            })
            ->contain(['Attributes'])
            ->all()->toList();

        $list[] = History::fromEntity($entity);
        foreach($entity->get('attributes') as $attribute) {
            $list[] = History::fromEntity($attribute);
        }

        usort($list, array("App\Model\Entity\History", "compare"));
        return $list;
    }

    public static function compare($a, $b)
    {
        if(is_null($a) && is_null($b))
            return 0;
        else if(is_null($a))
            return 1;
        else if(is_null($b))
            return -1;

        $cmp = strcmp($b['modified'], $a['modified']);
        if($cmp != 0)
            return $cmp;

        $cmp = strcmp($a['entity'], $b['entity']);
        if($cmp != 0)
            return -$cmp;

        $cmp = $a['key1'] - $b['key1'];
        if($cmp != 0)
            return $cmp;

        return $a['key2'] - $b['key2'];
    }
}
