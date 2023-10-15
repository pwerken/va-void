<?php
declare(strict_types=1);

namespace App\View;

use Cake\Core\Configure;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\View;

use App\Model\Entity\AppEntity;
use App\View\Helper\AuthorizeHelper;
use App\Utility\Json;

class ApiView
    extends View
{
    private $_aliases =
    [ 'Character'           =>  [ 'player_id' => 'plin' ]
    , 'Condition'           =>  [ 'id' => 'coin' ]
    , 'Item'                =>  [ 'id' => 'itin' ]
    , 'Lammy'               =>  [ 'lammy' => 'pdf_page' ]
    , 'Player'              =>  [ 'id' => 'plin' ]
    , 'Power'               =>  [ 'id' => 'poin' ]
    ];

    public function initialize(): void
    {
        $this->addHelper(AuthorizeHelper::class);
    }

    public function render(?string $view = null, $layout = null): string
    {
        $parent = $this->get('parent');
        $data = $this->get('_serialize', $this->viewVars);
        if($data instanceof AppEntity)
            $data = $this->_jsonData($data);
        if($data instanceof ResultSet)
            $data = $this->_jsonList($data->toArray(), $parent);

        if(isset($parent)) {
            $result = [];
            $result['class'] = $data['class'];
            $result['url'] = $data['url'];
            $result['parent'] = $this->_jsonCompact($parent);
            $result['list'] = $data['list'];
            $data = $result;
        }

        $this->response = $this->response->withType('json');
        return Json::encode($data);
    }

    private function _jsonData($obj)
    {
        if(!($obj instanceof AppEntity))
            return $obj;

        $class = $obj->getClass();
        $result = [];
        $result['class'] = $class;
        $result['url']   = $obj->getUrl();

        $this->authorize->applyScope('visible', $obj);
        foreach($obj->getVisible() as $key) {
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
    private function _jsonList(array $list, $parent = null, $key = null)
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

        $result = [];
        $result['class'] = $class;
        $result['url'] = $obj->getUrl($parent);

        foreach($obj->getCompact() as $key) {
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
