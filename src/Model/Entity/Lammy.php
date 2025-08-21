<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Lammy\LammyCard;
use App\Model\Enum\LammyStatus;
use Cake\Core\App;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;

class Lammy extends Entity
{
    use LocatorAwareTrait;

    private ?Entity $target = null;
    private ?LammyCard $lammy = null;

    protected array $_defaults = [
        'status' => LammyStatus::Queued,
    ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['entity', 'key1', 'key2', 'status', 'modified']);
        $this->setVirtual(['target', 'lammy']);
        $this->setHidden(['lammy'], true);
    }

    protected function _getTarget(): ?Entity
    {
        if (is_null($this->target)) {
            $name = Inflector::pluralize($this->get('entity'));
            $table = $this->fetchTable($name);

            $keys = [$this->get('key1'), $this->get('key2')];
            $primary = $table->getPrimaryKey();
            if (!is_array($primary)) {
                $primary = [$primary];
            }

            $where = [];
            foreach ($primary as $i => $id) {
                if (isset($keys[$i])) {
                    $where[$name . '.' . $id] = $keys[$i];
                }
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

        $table = $this->fetchTable($target->getSource());
        $this->set('entity', getShortClassName($table->getEntityClass()));

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
        $target = $this->get('target');
        if (is_null($this->lammy) && !is_null($target)) {
            $class = App::className($this->get('entity'), 'Lammy', 'Lammy');
            $this->lammy = new $class($target);
            $this->lammy->printedBy($this->get('creator_id'));
        }

        return $this->lammy;
    }
}
