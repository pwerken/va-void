<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Utility\Json;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity as CakeEntity;
use Cake\ORM\TableRegistry;

/**
 * @property int                                    $id
 * @property string                                 $entity
 * @property int                                    $key1
 * @property ?int                                   $key2
 * @property ?string                                $data
 * @property \Cake\I18n\DateTime|string|null        $modified
 * @property ?int                                   $modifier_id
 */
class History extends CakeEntity
{
    public static function fromEntity(EntityInterface $entity): ?History
    {
        if (!($entity instanceof Entity)) {
            return null;
        }

        $table = TableRegistry::getTableLocator()->get($entity->getSource());
        $columns = $table->getSchema()->columns();
        $data = $entity->extractOriginal($columns);

        $primary = $table->getPrimaryKey();
        if (!is_array($primary)) {
            $primary = [$primary];
        }

        $history = new History();
        $history->entity = getShortClassName($entity);

        $history->key1 = $data[$primary[0]];
        unset($data[$primary[0]]);

        if (isset($primary[1])) {
            $history->key2 = $data[$primary[1]];
            unset($data[$primary[1]]);
        }

        if (isset($data['modified'])) {
            $history->modified = (string)$data['modified'];
            unset($data['modified']);
        }

        if (isset($data['modifier_id'])) {
            $history->modifier_id = $data['modifier_id'];
            unset($data['modifier_id']);
        }

        if ($history->entity == 'SocialProfile') {
            $history->key2 = $data['user_id'];
        }

        if (empty($data)) {
            $history->data = '{}';
        } else {
            $history->data = Json::encode($data, false);
        }

        return $history;
    }

    public function decode(): array
    {
        return Json::decode($this->data ?? '{}');
    }

    public static function compare(?History $a, ?History $b): int
    {
        if (is_null($a) && is_null($b)) {
            return 0;
        } elseif (is_null($a)) {
            return 1;
        } elseif (is_null($b)) {
            return -1;
        }

        $cmp = strcmp($b->modifiedString(), $a->modifiedString());
        if ($cmp != 0) {
            return $cmp;
        }

        if (is_null($a->key1)) {
            return 1;
        }

        if (is_null($b->key1)) {
            return -1;
        }

        $cmp = strcmp($a->entity, $b->entity);
        if ($cmp != 0) {
            // count upper-case letters
            $aE = strlen(preg_replace('![^A-Z]+!', '', $a->entity));
            $bE = strlen(preg_replace('![^A-Z]+!', '', $b->entity));

            if ($aE != $bE) {
                return $bE - $aE;
            }

            return -$cmp;
        }

        return $b->key1 - $a->key1;
    }

    public function makeKey(): string
    {
        $entity = $this->entity;

        if ($entity === 'Character') {
            $data = $this->decode();

            return $entity . '/' . $data['plin'] . '/' . $data['chin'];
        }

        if ($entity === 'CharactersItem') {
            return 'Item/' . $this->key2;
        }

        $key = $entity . '/' . $this->key1;
        if ($this->key2) {
            $key .= '/' . $this->key2;
        }

        return $key;
    }

    public function modifiedString(): string
    {
        if (!$this->hasValue('modified')) {
            return '(??)';
        }

        return (string)$this->modified;
    }

    public function modifierString(): string
    {
        if (!$this->hasValue('modifier_id')) {
            return '(??)';
        }

        if ($this->modifier_id < 0) {
            return '_cli';
        }

        return sprintf('%04d', $this->modifier_id);
    }
}
