<?php
namespace App\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class History
    extends Entity
{
    public static function fromEntity(EntityInterface $entity)
    {
        if(is_null($entity))
            return NULL;

        $table = TableRegistry::get($entity->getSource());
        $columns = $table->getSchema()->columns();
        $data = $entity->extractOriginal($columns);

        $primary = $table->getPrimaryKey();
        if(!is_array($primary))
            $primary = [$primary];

        $history = new History();
        $history->set('entity', $entity->getClass());
        foreach($primary as $key => $field) {
            $history->set("key".($key+1), $data[$field]);
            unset($data[$field]);
        }
        $history->set('modified', $data['modified']);
        $history->set('modifier_id', @$data['modifier_id']);
        unset($data['modified']);
        unset($data['modifier_id']);

        if($history->get('entity') == 'SocialProfile') {
            $history->set('key12', $data['user_id']);
        }

        if(empty($data)) {
            $history->set('data', '{}');
        } else {
            $history->set('data', json_encode($data));
        }

        if($history->get('entity') != 'Item' && $entity->get('character'))
            $history->set('character', $entity->get('character'));
        if($entity->get('condition'))
            $history->set('condition', $entity->get('condition'));
        if($entity->get('power'))
            $history->set('power', $entity->get('power'));
        if($entity->get('skill'))
            $history->set('skill', $entity->get('skill'));
        if($entity->get('attribute'))
            $history->set('attribute', $entity->get('attribute'));
        if($entity->get('item'))
            $history->set('item', $entity->get('item'));

        return $history;
    }

    public static function compare($a, $b)
    {
        if(is_null($a) && is_null($b))
            return 0;
        else if(is_null($a))
            return 1;
        else if(is_null($b))
            return -1;

        $cmp = strcmp($b->modifiedString(), $a->modifiedString());
        if($cmp != 0)
            return $cmp;

        $aE = $a->get('entity');
        $bE = $b->get('entity');
        $cmp = strcmp($aE, $bE);
        if($cmp != 0) {
            // count upper-case letters
            $aE = strlen(preg_replace('![^A-Z]+!', '', $aE));
            $bE = strlen(preg_replace('![^A-Z]+!', '', $bE));
            return $aE != $bE ? $bE - $aE : -$cmp;
        }

        if(!is_null($a->get('id')) && !is_null($b->get('id')))
            return $b->get('id') - $a->get('id');
        if(!is_null($a->get('id')))
            return 1;
        if(!is_null($b->get('id')))
            return -1;

        $cmp = $a->get('key1') - $b->get('key1');
        if($cmp != 0)
            return $cmp;

        return $a->get('key2') - $b->get('key2');
    }

    public function keyString()
    {
        $key = $this->get('entity').'/'.$this->get('key1');
        if(!is_null($this->get('key2'))) {
            $key .= '/'.$this->get('key2');
        }
        return $key;
    }

    public function modifiedString()
    {
        $modified = $this->get('modified');
        if(is_null($modified))
            return '(??)';

        return $modified->jsonSerialize();
    }

    public function modifierString()
    {
        $modifier = $this->get('modifier_id');
        if(is_null($modifier))
            return '(??)';
        if($modifier < 0)
            return '_cli';

        return sprintf('%04d', $modifier);
    }

    public function relation()
    {
        $relation = $this->get('character');
        if(!is_null($relation)) {
            return $relation->get('player_id')
                    . '/' . $relation->get('chin')
                    . ' ' . $relation->get('name');
        }

        switch($this->get('entity')) {
        case 'AttributesItem':
            $relation = $this->get('attribute');
            if(is_null($relation))
                $relation = $this->get('item');
            break;
        case 'CharactersCondition':
            $relation = $this->get('condition');
            break;
        case 'CharactersPower':
            $relation = $this->get('power');
            break;
        case 'CharactersSkill':
            $relation = $this->get('skill');
            break;
        }
        if(is_null($relation))
            return NULL;

        return $relation->get('name');
    }
}
