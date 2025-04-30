<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity as CakeEntity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class Entity extends CakeEntity
{
    protected array $_defaults = [ ];
    protected array $_compact = [ 'id', 'name' ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        if ($this->isNew()) {
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
        $class = $this::class;

        return substr($class, strrpos($class, '\\') + 1);
    }

    protected function getBaseUrl(): string
    {
        return strtolower(Inflector::pluralize($this->getClass()));
    }

    protected function getRelationUrl(string $first, string $second, ?Entity $fallback): string
    {
        $a = $this->$first ?? $fallback;
        $b = $this->$second ?? $fallback;

        return $a->getUrl() . $b->getUrl();
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->id;
    }

    public function refresh(): self
    {
        $name = Inflector::pluralize($this->getClass());
        $table = TableRegistry::getTableLocator()->get($name);
        $query = $table->find('withContain');

        $keys = $table->getPrimaryKey();
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $field = current($query->aliasField($key));
                $query->where([$field => $this->$key]);
            }
        } else {
            $field = current($query->aliasField($keys));
            $query->where([$field => $this->$keys]);
        }

        return $query->first();
    }

    public function setCompact(array $properties, bool $merge = false): static
    {
        if (!$merge) {
            $this->_compact = $properties;

            return $this;
        }

        $properties = array_merge($this->_compact, $properties);
        $this->_compact = array_unique($properties);

        return $this;
    }

    public function getCompact(): array
    {
        return $this->_compact;
    }
}
