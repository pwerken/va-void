<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Lammy\LammyCard;
use App\Model\Enum\LammyStatus;
use Cake\Core\App;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;

/**
 * @template T of \App\Model\Entity\Entity
 *
 * Properties:
 * @property int                            $id
 * @property \App\Model\Enum\LammyStatus    $status
 * @property class-string<T>                $entity
 * @property int                            $key1
 * @property ?int                           $key2
 * @property ?\Cake\I18n\DateTime           $created
 * @property ?int                           $creator_id
 * @property ?\Cake\I18n\DateTime           $modified
 *
 * Virtual:
 * @property ?T                             $target
 * @property ?\App\Lammy\LammyCard          $lammy
 */
class Lammy extends Entity
{
    use LocatorAwareTrait;

    private ?Entity $tEntity = null;
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

    /**
     * @return T
     */
    protected function _getTarget(): ?Entity
    {
        if (is_null($this->tEntity)) {
            $name = Inflector::pluralize($this->entity);

            /** @var \App\Model\Table\Table<T> $table */
            $table = $this->fetchTable($name);

            $keys = [$this->key1, $this->key2];
            $primary = $table->getPrimaryKey();
            if (!is_array($primary)) {
                $primary = [$primary];
            }

            $where = [];
            foreach ($primary as $i => $id) {
                $where[$name . '.' . $id] = $keys[$i];
            }

            $this->tEntity = $table->find('withContain')->where($where)->first();
        }

        return $this->tEntity;
    }

    /**
     * @param T $target
     */
    protected function _setTarget(?Entity $target): ?Entity
    {
        if (is_null($target)) {
            return null;
        }

        $table = $this->fetchTable($target->getSource());
        $this->entity = getShortClassName($table->getEntityClass());

        $primary = $table->getPrimaryKey();
        if (!is_array($primary)) {
            $primary = [$primary];
        }

        foreach ($primary as $key => $field) {
            $this->set('key' . ($key + 1), $target->get($field));
        }

        return $target;
    }

    protected function _getLammy(): ?LammyCard
    {
        $target = $this->target;
        if (is_null($this->lammy) && !is_null($target)) {
            $class = App::className($this->entity, 'Lammy', 'Lammy');
            $this->lammy = new $class($target);
            $this->lammy->printedBy($this->creator_id);
        }

        return $this->lammy;
    }
}
