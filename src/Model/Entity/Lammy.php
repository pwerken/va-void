<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Lammy\LammyCard;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Lammy extends Entity
{
    private ?Entity $target = null;
    private ?LammyCard $lammy = null;

    protected array $_defaults = [ 'status' => 'Queued' ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['entity', 'key1', 'key2', 'status', 'modified']);
        $this->setVirtual(['target', 'lammy']);
        $this->setHidden(['lammy'], true);
    }

    public static function statusValues(): array
    {
        static $data = null;
        if (is_null($data)) {
            $data = ['Queued', 'Failed', 'Printing', 'Printed'];
        }

        return $data;
    }

    protected function _getTarget(): Entity
    {
        if (is_null($this->target)) {
            $name = Inflector::pluralize($this->get('entity'));
            $table = TableRegistry::getTableLocator()->get($name);

            $keys = [$this->get('key1'), $this->get('key2')];
            $primary = $table->getPrimaryKey();
            if (!is_array($primary)) {
                $primary = [$primary];
            }

            $where = [];
            foreach ($primary as $i => $id) {
                $where[$name . '.' . $id] = $keys[$i];
            }

            $this->target = $table->find('withContain')->where($where)->first();
        }

        return $this->target;
    }

    protected function _setTarget(?Entity $target): void
    {
        if (is_null($target)) {
            return;
        }

        $table = TableRegistry::getTableLocator()->get($target->getSource());
        $class = $table->getEntityClass();
        $pos = strrpos($class, '\\');
        if ($pos > 0) {
            $class = substr($class, $pos + 1);
        }
        $this->set('entity', $class);

        $primary = $table->getPrimaryKey();
        if (!is_array($primary)) {
            $primary = [$primary];
        }

        foreach ($primary as $key => $field) {
            $this->set('key' . ($key + 1), $target->get($field));
        }
    }

    protected function _getLammy(): ?LammyCard
    {
        if (is_null($this->lammy)) {
            $class = 'App\\Lammy\\' . $this->get('entity') . 'Lammy';
            $target = $this->_getTarget();
            if (!is_null($target)) {
                $this->lammy = new $class($target);
                $this->lammy->printedBy($this->get('creator_id'));
            }
        }

        return $this->lammy;
    }
}
