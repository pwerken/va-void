<?php

namespace App\View;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\View;

class ApiView extends View
{
	private $_aliases =
	[ 'Character'           => ['player_id' => 'plin']
	, 'Condition'           => ['id' => 'coin']
	, 'Player'              => ['id' => 'plin']
	, 'Power'               => ['id' => 'poin']
	, 'Item'                => ['id' => 'itin']
	];

	private $_compact =
    [ 'AttributesItem'      => ['attribute', 'item']
	, 'Character'           => ['player_id', 'chin', 'name']
    , 'CharactersCondition' => ['expiry', 'character', 'condition']
    , 'CharactersPower'     => ['expiry', 'character', 'power']
	, 'CharactersSkill'     => ['character','skill']
	, 'CharactersSpell'     => ['level', 'character', 'spell']
	, 'Item'                => ['id', 'name', 'expiry', 'character']
	, 'Player'              => ['id', 'full_name']
	, 'Skill'               => ['id', 'name', 'cost', 'mana_amount', 'manatype']
	, 'Spell'               => ['id', 'name', 'short']
	];

	public function render($view = null, $layout = null)
	{
		$var = $this->viewVars['viewVar'];
		$data = $this->_jsonData($this->viewVars[$var]);

        $jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
        if (Configure::read('debug')) {
            $jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;
        }
        return json_encode($data, $jsonOptions);
	}

	private function _class($value) {
		return join('', array_slice(explode('\\', get_class($value)), -1));
	}

	private function _jsonUrl($value = null) {
        if(is_null($value)) {
			return substr($this->request->url, 3);
		}

        $class = $this->_class($value);
        if($class == 'Character')
            return '/characters/'.$value->player_id.'/'.$value->chin;
		if($class == 'AttributesItem')
			return $this->_jsonUrlJoin($value
							, $value->item, $value->attribute);
		if($class == 'CharactersCondition')
			return $this->_jsonUrlJoin($value
							, $value->character, $value->condition);
		if($class == 'CharactersPower')
			return $this->_jsonUrlJoin($value
							, $value->character, $value->power);
		if($class == 'CharactersSkill')
			return $this->_jsonUrlJoin($value
							, $value->character, $value->skill);
		if($class == 'CharactersSpell')
			return $this->_jsonUrlJoin($value
							, $value->character, $value->spell);

        return '/'.strtolower(Inflector::pluralize($class)).'/'.$value->get('id');
	}
	private function _jsonUrlJoin($join, $a, $b) {
        $class = $this->_class($join);
		$aCls  = $this->_class($a);	$aUrl = $this->_jsonUrl($a);
		$bCls  = $this->_class($b); $bUrl = $this->_jsonUrl($b);
		switch($class) {
        case 'AttributesItem':
			if($aCls == 'Item' && $bCls == 'Attribute') return $aUrl.$bUrl;
			if($bCls == 'Item' && $aCls == 'Attribute') return $bUrl.$aUrl;
			break;
        case 'CharactersCondition':
			if($aCls == 'Character' && $bCls == 'Condition') return $aUrl.$bUrl;
			if($bCls == 'Character' && $aCls == 'Condition') return $bUrl.$aUrl;
			break;
        case 'CharactersPower':
			if($aCls == 'Character' && $bCls == 'Power') return $aUrl.$bUrl;
			if($bCls == 'Character' && $aCls == 'Power') return $bUrl.$aUrl;
			break;
        case 'CharactersSkill':
			if($aCls == 'Character' && $bCls == 'Skill') return $aUrl.$bUrl;
			if($bCls == 'Character' && $aCls == 'Skill') return $bUrl.$aUrl;
			break;
        case 'CharactersSpell':
			if($aCls == 'Character' && $bCls == 'Spell') return $aUrl.$bUrl;
			if($bCls == 'Character' && $aCls == 'Spell') return $bUrl.$aUrl;
			break;
		}
		return '/'.$class.'/';
	}

	private function _jsonData($obj) {
		if(!is_array($obj)
        && !($obj instanceof EntityInterface)
        && !($obj instanceof ResultSet)) {
			return $obj;
        }

		if(is_array($obj) || $obj instanceof ResultSet)
			return $this->_jsonList($obj, @$this->viewVars['parent']);

		$result = [];
		$result['url'] = $this->_jsonUrl($obj);
        $class = $this->_class($obj);

		foreach($obj->visibleProperties() as $key) {
			$value = $obj->get($key);
			if(is_array($value)) {
				$value = $this->_jsonList($value, $obj);
				$value['url'] = $result['url'].'/'.$key;
				unset($value['parent']);
			} else {
				$value = $this->_jsonCompact($value);
			}

			$camel = Inflector::camelize($key);
			if(method_exists($obj, 'label'.$camel))
				$value = call_user_func([$obj, 'label'.$camel], $value);

			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
			$result[$key] = $value;
		}
		return $result;
	}
	private function _jsonList($objs, $parent = null) {
		$result = [];
        $result['url'] = $this->_jsonUrl();

		$remove = '';
		if($parent) {
			$remove = strtolower($this->_class($parent));
			$result['parent'] = $this->_jsonCompact($parent);
		}

		$result['list'] = [];
		foreach($objs as $obj) {
			if($obj == $remove)
				continue;

			$value = $this->_jsonCompact($obj);
			unset($value[$remove]);

			if(!$obj->has('_joinData')) {
				$result['list'][] = $value;
				continue;
			}

			$nest = $this->_jsonCompact($obj->_joinData);
			$nest['url'] = $this->_jsonUrlJoin($obj->_joinData, $parent, $obj);
			unset($nest[$remove]);
			$nest[strtolower($this->_class($obj))] = $value;

			$result['list'][] = $nest;
		}
		return $result;
	}
	private function _jsonCompact($obj) {
		if(!($obj instanceof EntityInterface))
			return $obj;

        $class = $this->_class($obj);

		$properties = ['id', 'name'];
		if(isset($this->_compact[$class]))
			$properties = $this->_compact[$class];

        if(empty($properties))
			return $obj->get('name');

        $result = [];
        $result['url'] = $this->_jsonUrl($obj);
        foreach($properties as $key) {
            $value = $this->_jsonCompact($obj->get($key));
			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
            $result[$key] = $value;
        }

		return $result; #$this->_jsonData($value); #$value;
	}
}