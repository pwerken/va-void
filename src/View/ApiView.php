<?php
declare(strict_types=1);

namespace App\View;

use App\Model\Entity\Character;
use App\Model\Entity\Condition;
use App\Model\Entity\Entity;
use App\Model\Entity\Power;
use App\Model\Entity\Skill;
use App\Model\Entity\Teaching;
use App\Utility\Json;
use App\View\Helper\AuthorizeHelper;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\View;

/**
 * @property \App\View\Helper\AuthorizeHelper $authorize
 */
class ApiView extends View
{
    protected array $aliases = [
        'Lammy' => ['lammy' => 'pdf_page'],
    ];

    public function initialize(): void
    {
        $this->addHelper(AuthorizeHelper::class);
    }

    public function render(?string $template = null, string|false|null $layout = null): string
    {
        $this->setResponse($this->getResponse()->withType('json'));

        $data = $this->get('_serialize', $this->viewVars);
        if ($data instanceof Entity) {
            $data = $this->jsonEntity($data);
        } elseif ($data instanceof ResultSet) {
            $parent = $this->get('parent');
            $data = $this->jsonArray($data, $parent);
            $data['url'] = $this->getRequest()->getPath();
        }

        return Json::encode($data);
    }

    protected function jsonEntity(Entity $obj): array
    {
        $class = getShortClassName($obj);

        $result = [];
        $result['class'] = $class;
        $result['url'] = $obj->getUrl();

        $this->authorize->applyScope('visible', $obj);
        foreach ($obj->getVisible() as $field) {
            $value = $obj->get($field);

            if ($value instanceof Entity) {
                if ($field === 'teacher') {
                    $value->student = $obj;
                }

                $value = $this->jsonCompact($value, $obj);

                if ($field === 'teacher') {
                    unset($value['teacher']);
                }
            } elseif (is_array($value)) {
                $value = $this->jsonArray($value, $obj, $field);
                unset($value['parent']);
            } else {
                $label = Inflector::camelize('label_' . $field);
                if (method_exists($obj, $label)) {
                    $value = call_user_func([$obj, $label], $value);
                }
            }

            if (isset($this->aliases[$class][$field])) {
                $field = $this->aliases[$class][$field];
            }
            $result[$field] = $value;
        }

        return $result;
    }

    protected function jsonArray(ResultSet|array $list, ?Entity $parent, ?string $key = null): array
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = '';

        if ($parent) {
            $result['url'] = $parent->getUrl() . '/' . $key;
            $result['parent'] = $this->jsonCompact($parent);
        }

        $isTeaching = false;
        $result['list'] = [];
        foreach ($list as $obj) {
            $value = $obj;
            if ($obj instanceof Entity) {
                if ($obj instanceof Teaching) {
                    $isTeaching = true;
                    $obj->teacher = $parent;
                }
                $value = $this->jsonCompact($obj, $parent);
            }
            $result['list'][] = $value;
        }

        if ($isTeaching) {
            if (is_null($key)) {
                $result['url'] .= 'students';
            }
            foreach (array_keys($result['list']) as $key) {
                unset($result['list'][$key]['teacher']);
            }
        }

        return $result;
    }

    protected function jsonCompact(Entity $obj, ?Entity $parent = null): mixed
    {
        # place _joinData between $parent and $obj
        # also prevent re-queries for already retrieved (sub/nested) objects
        if (isset($obj->_joinData) && $parent instanceof Character) {
            $join = $obj->_joinData;
            if ($obj instanceof Skill) {
                $join->skill = $obj;
            }
            if ($obj instanceof Power) {
                $join->power = $obj;
            }
            if ($obj instanceof Condition) {
                $join->condition = $obj;
            }

            $join->character = $parent;
            unset($obj->_joinData);
            $obj = $join;
        }

        $class = getShortClassName($obj);

        $skip = null;
        if ($parent) {
            $skip = strtolower(getShortClassName($parent));
        }

        $result = [];
        $result['class'] = $class;
        $result['url'] = $obj->getUrl();

        foreach ($obj->getCompact() as $field) {
            if ($field === $skip) {
                continue;
            }

            $value = $obj->get($field);
            if ($value instanceof Entity) {
                $value = $this->jsonCompact($value, $obj);
            }

            if (isset($this->aliases[$class][$field])) {
                $field = $this->aliases[$class][$field];
            }
            $result[$field] = $value;
        }

        return $result;
    }
}
