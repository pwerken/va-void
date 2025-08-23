<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity as CakeEntity;
use Cake\ORM\TableRegistry;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

abstract class Entity extends CakeEntity
{
    use LazyLoadEntityTrait;

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

    protected function getBaseUrl(): string
    {
        $table = TableRegistry::getTableLocator()->get($this->getSource());

        return strtolower($table->getTable());
    }

    protected function getRelationUrl(string $first, string $second): string
    {
        $a = $this->get(strtolower($first))->getUrl();
        $b = $this->get(strtolower($second))->getUrl();

        return $a . $b;
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('id');
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

    public static function refresh(EntityInterface $entity): self
    {
        $table = TableRegistry::getTableLocator()->get($entity->getSource());
        $query = $table->find('withContain');

        $keys = $table->getPrimaryKey();
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $field = current($query->aliasField($key));
                $query->where([$field => $entity->get($key)]);
            }
        } else {
            $field = current($query->aliasField($keys));
            $query->where([$field => $entity->get($keys)]);
        }

        return $query->first();
    }
}
