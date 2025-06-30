<?php
declare(strict_types=1);

namespace App\View;

use App\Model\Entity\Entity;
use App\Utility\Json;
use App\View\Helper\AuthorizeHelper;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
use Cake\View\View;
use ReflectionClass;

/**
 * @property \App\View\Helper\AuthorizeHelper $authorize
 */
class ApiView extends View
{
    protected array $aliases = [
        'Character' => ['player_id' => 'plin'],
        'Condition' => ['id' => 'coin'],
        'Item' => ['id' => 'itin'],
        'Lammy' => ['lammy' => 'pdf_page'],
        'Player' => ['id' => 'plin'],
        'Power' => ['id' => 'poin'],
    ];

    public function initialize(): void
    {
        $this->addHelper(AuthorizeHelper::class);
    }

    public function render(?string $template = null, string|false|null $layout = null): string
    {
        $this->response = $this->response->withType('json');

        $data = $this->get('_serialize', $this->viewVars);
        if ($data instanceof Entity) {
            $data = $this->jsonEntity($data);
        } elseif ($data instanceof ResultSet) {
            $parent = $this->get('parent');
            $data = $this->jsonArray($data->toArray(), $parent);
        }

        return Json::encode($data);
    }

    protected function jsonEntity(Entity $obj): array
    {
        $class = (new ReflectionClass($obj))->getShortName();

        $result = [];
        $result['class'] = $class;
        $result['url'] = $obj->getUrl();

        $this->authorize->applyScope('visible', $obj);
        foreach ($obj->getVisible() as $field) {
            $value = $obj->get($field);

            if ($value instanceof Entity) {
                $value = $this->jsonCompact($value, $obj);
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

    protected function jsonArray(array $list, ?Entity $parent, ?string $key = null): array
    {
        $result = [];
        $result['class'] = 'List';
        $result['url'] = '';

        if ($parent) {
            $result['url'] = $parent->getUrl() . '/' . $key;
            $result['parent'] = $this->jsonCompact($parent);
        }

        $result['list'] = [];
        foreach ($list as $obj) {
            if ($obj instanceof Entity) {
                $result['list'][] = $this->jsonCompact($obj, $parent);
            } else {
                $result['list'][] = $obj;
            }
        }

        return $result;
    }

    protected function jsonCompact(Entity $obj, ?Entity $parent = null): mixed
    {
        $class = (new ReflectionClass($obj))->getShortName();
        $skip = '';

        if ($parent) {
            $skip = strtolower((new ReflectionClass($parent))->getShortName());
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

        // joinData should be in between $parent en $obj
        if (isset($obj->_joinData)) {
            $join = $this->jsonCompact($obj->_joinData, $parent);
            $join[strtolower($class)] = $result;
            $result = $join;
        }

        return $result;
    }
}
