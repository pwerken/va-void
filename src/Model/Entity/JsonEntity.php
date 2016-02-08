<?php
namespace App\Model\Entity;

use Cake\Utility\Inflector;
use Cake\ORM\Entity;

class JsonEntity extends Entity {

	protected $_json_aliases = [ ];
	protected $_json_short = [ ];

	public function jsonUrl() {
		return '/api/'.$this->_getClassName().'/'.$this->get('id');
	}

	public function jsonFull() {
		return $this->_jsonArray($this->visibleProperties());
	}

	public function jsonShort() {
		if(empty($this->_json_short))
			return [];

		return $this->_jsonArray($this->_json_short);
	}

	private function _jsonArray($properties) {
		$url = $this->jsonUrl();

		$result = [];
		if(!empty($url))
			$result['url'] = $url;

		foreach($properties as $property) {
			$value = $this->get($property);

			if(is_array($value)) {
				$list = [];
				$list['url'] = $url.'/'.$property;
				$list['list'] = [];
				foreach($value as $sub) {
					if(!($sub instanceof JsonEntity)) {
						$list['list'][] = $sub;
						continue;
					}

					$suburl = $url.substr($sub->jsonUrl(), 4);
					$subarr = self::_jsonSubValue($sub);

					$join = [];
					if($sub->has('_joinData'))
						$join = self::_jsonSubValue($sub->_joinData);

					if(empty($join)) {
						$join = $subarr;
						unset($join[Inflector::singularize($this->_getClassName())]);
					} else {
						$join['url'] = $suburl;
						$join[Inflector::singularize($property)] = $subarr;
					}
					$list['list'][] = $join;
				}
				$value = $list;
			} else {
				$value = self::_jsonSubValue($value);
			}

			$camel = Inflector::camelize($property);
			if(method_exists($this, 'label'.$camel))
				$value = call_user_func([$this, 'label'.$camel], $value);

			if(isset($this->_json_aliases[$property]))
				$property = $this->_json_aliases[$property];
			$result[$property] = $value;
		}
		return $result;
	}

	private function _getClassName() {
		$array = explode('\\', Inflector::tableize(get_class($this)));
		return array_pop($array);
	}
	private static function _jsonSubValue($value) {
		if($value instanceof JsonEntity)
			return $value->jsonShort();
		if($value instanceof EntityInterface)
			return NULL;
		return $value;
	}

}
