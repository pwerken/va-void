<?php
namespace App\Model\Entity;

use Cake\Utility\Inflector;
use Cake\ORM\Entity;
use Cake\I18n\Time;

class JsonEntity extends Entity {

	protected $_json_aliases = [ ];
	protected $_json_short = [ ];

	public function jsonUrl() {
		$array = explode('\\', Inflector::tableize(get_class($this)));
		$className = array_pop($array);
		return '/api/'.$className.'/'.$this->get('id');
	}

	public function jsonFull() {
		return $this->_jsonArray($this->visibleProperties());
	}

	public function jsonShort() {
		return $this->_jsonArray($this->_json_short);
	}

	private function _jsonArray($properties) {
		$result = [];
		$result['url'] = $this->jsonUrl();
		foreach($properties as $property) {
			$value = $this->get($property);

			if(is_array($value)) {
				$list = [];
				$list['url'] = $result['url'].'/'.$property;
				foreach($value as &$sub)
					$list['list'][] = $this->_jsonSubValue($sub);

				$value = $list;
			} else {
				$value = $this->_jsonSubValue($value);
			}

			if($value instanceof Time) {
				if($property == 'created' || $property == 'modified')
					$value = $value->format('Y-m-d H:i:s');
				else
					$value = $value->format('Y-m-d');
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
	private function _jsonSubValue($value) {
		if($value instanceof EntityInterface)
			return NULL;
		if($value instanceof JsonEntity)
			return $value->jsonShort();

		return $value;
	}

}