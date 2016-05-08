<?php
namespace App\View;

use App\Model\Entity\AppEntity;
use Cake\Core\Configure;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\View;

class ApiView
	extends View
{

	private $_aliases =
	[ 'Attribute'			=>	[ 'attributes_items' => 'items' ]
	, 'Character'           =>	[ 'player_id' => 'plin'
								, 'characters_conditions' => 'conditions'
								, 'characters_powers'     => 'powers'
								, 'characters_skills'     => 'skills'
								, 'characters_spells'     => 'spells'
								]
	, 'Condition'           =>	[ 'id' => 'coin'
								, 'characters_conditions' => 'characters'
								]
	, 'Item'                =>	[ 'id' => 'itin'
								, 'attributes_items' => 'attributes'
								]
	, 'Lammy'               =>	[ 'lammy' => 'pdf_page' ]
	, 'Player'              =>	[ 'id' => 'plin' ]
	, 'Power'               =>	[ 'id' => 'poin'
								, 'characters_powers' => 'characters'
								]
	, 'Skill'	            =>	[ 'characters_skills' => 'characters' ]
	, 'Spell'	            =>	[ 'characters_spells' => 'characters' ]
	];

	private $_compact =
	[ 'Attribute'           => ['id', 'name', 'code']
	, 'AttributesItem'      => ['attribute', 'item']
	, 'Character'           => ['player_id', 'chin', 'name']
    , 'CharactersCondition' => ['expiry', 'character', 'condition']
    , 'CharactersPower'     => ['expiry', 'character', 'power']
	, 'CharactersSkill'     => ['character', 'skill']
	, 'CharactersSpell'     => ['level', 'character', 'spell']
	, 'Item'                => ['id', 'name', 'expiry', 'character']
	, 'Lammy'               => ['entity', 'key1', 'key2', 'job', 'printed', 'lammy' ]
	, 'Player'              => ['id', 'full_name']
	, 'Skill'               => ['id', 'name', 'cost', 'mana_amount', 'manatype']
	, 'Spell'               => ['id', 'name', 'short']
	];

	public function render($view = null, $layout = null)
	{
		$data = $this->get($this->get('viewVar'));
		if(is_null($data)) {
			$data = $this->get('_serialize', $this->viewVars);
		} elseif(is_array($data) || $data instanceof ResultSet) {
			$data = $this->_jsonList($data, $this->get('parent'));
			$data['url'] = '/'.$this->request->url;
		} else {
			$data = $this->_jsonData($data);
		}

		$jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
		if(Configure::read('debug'))
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;

		$this->response->type('json');
		return json_encode($data, $jsonOptions);
	}

	private function _jsonData($obj)
	{
		if(!($obj instanceof AppEntity))
			return $obj;

        $class = $obj->getClass();
		$result = [];
		$result['class'] = $class;
		$result['url']   = $obj->getUrl();

		foreach($obj->visibleProperties() as $key) {
			$value = $obj->get($key);

			$label = Inflector::camelize("label_".$key);
			if(method_exists($obj, $label))
				$value = call_user_func([$obj, $label], $value);

			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];

			if(is_array($value)) {
				$value = $this->_jsonList($value, $obj, $key);
				unset($value['parent']);
			} else {
				$value = $this->_jsonCompact($value, $obj);
			}

			$result[$key] = $value;
		}
		return $result;
	}
	private function _jsonList($list, $parent = null, $key = null)
	{
		$result = [];
		$result['class'] = 'List';
		$result['url'] = $parent->getUrl().'/'.$key;

		$remove = '';
		if($parent) {
			$remove = strtolower($parent->getClass());
			$result['parent'] = $this->_jsonCompact($parent);
		}

		$result['list'] = [];
		foreach($list as $obj) {
			$value = $this->_jsonCompact($obj, $parent);
			unset($value[$remove]);
			$result['list'][] = $value;
		}
		return $result;
	}
	private function _jsonCompact($obj, $parent = null)
	{
		if(!($obj instanceof AppEntity))
			return $obj;

		$class = $obj->getClass();

		$properties = ['id', 'name'];
		if(isset($this->_compact[$class]))
			$properties = $this->_compact[$class];

		if(empty($properties))
			return $obj->get('name');

		$result = [];
		$result['class'] = $class;
		$result['url'] = $obj->getUrl($parent);
		foreach($properties as $key) {
			$value = $this->_jsonCompact($obj->get($key), $obj);
			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
			$result[$key] = $value;
		}
		return $result;
	}

}
