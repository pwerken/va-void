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
    [ 'Character'           => ['player_id', 'chin', 'name']
    , 'CharactersCondition' => ['expiry']
    , 'CharactersPower'     => ['expiry', 'character', 'power']
	, 'CharactersSkill'     => ['character', 'skill']
	, 'CharactersSpell'     => ['level', 'character', 'spell']
	, 'Player'              => ['id', 'full_name']
	, 'Item'                => ['id', 'name', 'expiry', 'character']
	, 'Skill'               => ['id', 'name', 'cost', 'mana_amount', 'manatype']
	, 'Spell'               => ['id', 'name', 'short']
	];

	public function render($view = null, $layout = null)
	{
#		echo __FUNCTION__
#			."(".var_export($view, true)
#			.", ".var_export($layout, true)
#			.")\n";

		//	'api' . DS . $this->name
		$var = $this->viewVars['viewVar'];
		if(is_array($var))
			$var = $var[0];

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
        if(!is_object($value))
            return "/"; # FIXME

        $class = $this->_class($value);
        if($class == 'Character')
            return '/api/characters/'.$value->player_id.'/'.$value->chin;

        $class = strtolower(Inflector::pluralize($class));
        return '/api/'.$class.'/'.$value->id;
	}

	private function _jsonData($value) {
		if(!is_array($value)
        && !($value instanceof EntityInterface)
        && !($value instanceof ResultSet)) {
			return $value;
        }

		if(is_array($value) || $value instanceof ResultSet) {
			return $this->_jsonList($value);
		}

		$result = [];
		$result['url'] = $this->_jsonUrl($value);
        $class = $this->_class($value);

		foreach($value->visibleProperties() as $key) {
			$property = $value->get($key);
			if(is_array($property)) {
				$property = $this->_jsonList($property);
				$property['url'] = $result['url'].'/'.$key;
#				foreach($property as $sub) {
#					if(!($sub instanceof JsonEntity)) {
#						$list['list'][] = $sub;
#						continue;
#					}
#
#					$suburl = $url.substr($sub->jsonUrl(), 4);
#					$subarr = self::_jsonCompactValue($sub);
#
#					$join = [];
#					if($sub->has('_joinData'))
#						$join = self::_jsonCompactValue($sub->_joinData);
#
#					if(empty($join)) {
#						$join = $subarr;
#						unset($join[Inflector::singularize($this->_getClassName())]);
#					} else {
#						$join['url'] = $suburl;
#						$join[Inflector::singularize($key)] = $subarr;
#					}
#					$list['list'][] = $join;
#				}
			} else {
				$property = $this->_jsonCompact($property);
			}

			$camel = Inflector::camelize($key);
			if(method_exists($value, 'label'.$camel))
				$property = call_user_func([$value, 'label'.$camel], $property);

			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
			$result[$key] = $property;
		}
		return $result;
	}
	private function _jsonList($values) {
		$result = [];
        $result['url'] = $this->_jsonUrl();
		$result['list'] = [];
		foreach($values as $sub) {
			$result['list'][] = $this->_jsonCompact($sub);
		}
		return $result;
	}
	private function _jsonCompact($value) {
		if(!($value instanceof EntityInterface))
			return $value;

        $class = $this->_class($value);
		$properties = @$this->_compact[$class] ?: ['id', 'name'];

        if($class == 'Manatype')
            return $value->get('name');

        $result = [];
        $result['url'] = $this->_jsonUrl($value);
        foreach($properties as $key) {
            $prop = $this->_jsonCompact($value->get($key));
			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
            $result[$key] = $prop;
        }

		return $result; #$this->_jsonData($value); #$value;
	}
}
