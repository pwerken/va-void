<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class AppEntity
    extends Entity
{

    protected $_defaults = [ ];
    protected $_compact  = [ 'id', 'name' ];

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        if($this->isNew()) {
            $this->patch($this->_defaults, ['guard' => false, 'asOriginal' => true]);
        }
    }

    protected function emptyToDefault(string $name, mixed $value): mixed
    {
        if (empty($value) && array_key_exists($name, $this->_defaults)) {
            return $this->_defaults[$name];
        }
        return $value;
    }

    public function getClass(): string
    {
        $class = get_class($this);
        return substr($class, strrpos($class, '\\') + 1);
    }

    protected function getBaseUrl()
    {
        return strtolower(Inflector::pluralize($this->getClass()));
    }

    protected function getRelationUrl($first, $second, $fallback)
    {
        $a = $this->$first  ?: $fallback;
        $b = $this->$second ?: $fallback;
        return $a->getUrl().$b->getUrl();
    }
    public function getUrl()
    {
        return '/'.$this->getBaseUrl().'/'.$this->id;
    }

    public function refresh()
    {
        $name = Inflector::pluralize($this->getClass());
        $table = TableRegistry::getTableLocator()->get($name);
        $query = $table->find('withContain');

        $keys = $table->getPrimaryKey();
        if(is_array($keys)) {
            foreach($keys as $i => $key) {
                $field = current($query->aliasField($key));
                $query->where([$field => $this->$key]);
            }
        } else {
            $field = current($query->aliasField($keys));
            $query->where([$field => $this->$keys]);
        }
        return $query->first();
    }

    public function setCompact($properties, $merge = false)
    {
        if($merge === false) {
            $this->_compact = $properties;

            return $this;
        }

        $properties = array_merge($this->_compact, $properties);
        $this->_compact = array_unique($properties);

        return $this;
    }

    public function getCompact()
    {
        return $this->_compact;
    }
}
