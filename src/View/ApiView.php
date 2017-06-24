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
	[ 'Character'           =>	[ 'player_id' => 'plin' ]
	, 'Condition'           =>	[ 'id' => 'coin' ]
	, 'Item'                =>	[ 'id' => 'itin' ]
	, 'Lammy'               =>	[ 'lammy' => 'pdf_page' ]
	, 'Player'              =>	[ 'id' => 'plin' ]
	, 'Power'               =>	[ 'id' => 'poin' ]
	];

	public function render($view = null, $layout = null)
	{
		$data = $this->get($this->get('viewVar'));
		if(is_null($data)) {
			$data = $this->get('_serialize', $this->viewVars);
		} elseif(is_array($data) || $data instanceof ResultSet) {
			$data = $this->_jsonList($data, $this->get('parent'));
			$data['url'] = '/' . rtrim($this->request->url, '/');
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
				$value = $this->_jsonCompact($value, $obj, $obj->getUrl());
			}

			$result[$key] = $value;
		}
		return $result;
	}
	private function _jsonList($list, $parent = null, $key = null)
	{
		$result = [];
		$result['class'] = 'List';
		$result['url'] = '';

		$parentUrl = null;
		$remove = '';
		if($parent) {
			$parentUrl = $parent->getUrl();
			$remove = strtolower($parent->getClass());
			$result['url'] = $parentUrl.'/'.$key;
			$result['parent'] = $this->_jsonCompact($parent);
		}

		$result['list'] = [];
		foreach($list as $obj) {
			$value = $this->_jsonCompact($obj, $parent, $parentUrl);
			unset($value[$remove]);
			$result['list'][] = $value;
		}
		return $result;
	}
	private function _jsonCompact($obj, $parent = null, $url = null)
	{
		if(!($obj instanceof AppEntity))
			return $obj;

		$class = $obj->getClass();

		$properties = $obj->compactProperties();
		if(empty($properties))
			return [ 'name' => $obj->get('name') ];

		$result = [];
		$result['class'] = $class;
		$result['url'] = $obj->getUrl($parent);
		foreach($properties as $key) {
			$value = $this->_jsonCompact($obj->get($key), $obj);
			if(isset($this->_aliases[$class][$key]))
				$key = $this->_aliases[$class][$key];
			if(isset($url) && is_array($value) && isset($value['url'])) {
				if($url == $value['url'])
					continue;
			}
			$result[$key] = $value;
		}
		return $result;
	}

}
