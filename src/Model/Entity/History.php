<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Utility\Json;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity as CakeEntity;
use Cake\ORM\TableRegistry;

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
        $history->set('entity', getShortClassName($entity));

        $history->set('key1', $data[$primary[0]]);
        unset($data[$primary[0]]);

        if (isset($primary[1])) {
            $history->set('key2', $data[$primary[1]]);
            unset($data[$primary[1]]);
        }

        if (isset($data['modified'])) {
            $history->set('modified', (string)$data['modified']);
            unset($data['modified']);
        }

        if (isset($data['modifier_id'])) {
            $history->set('modifier_id', $data['modifier_id']);
            unset($data['modifier_id']);
        }

        if ($history->get('entity') == 'SocialProfile') {
            $history->set('key2', $data['user_id']);
        }

        if (empty($data)) {
            $history->set('data', '{}');
        } else {
            $history->set('data', Json::encode($data, false));
        }

        return $history;
    }

    public function decode(): array
    {
        return Json::decode($this->get('data') ?? '{}');
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

        if (is_null($b->get('id'))) {
            return 1;
        }

        if (is_null($a->get('id'))) {
            return -1;
        }

        $cmp = strcmp($a->get('entity'), $b->get('entity'));
        if ($cmp != 0) {
            // count upper-case letters
            $aE = strlen(preg_replace('![^A-Z]+!', '', $a->get('entity')));
            $bE = strlen(preg_replace('![^A-Z]+!', '', $b->get('entity')));

            if ($aE != $bE) {
                return $bE - $aE;
            }

            return -$cmp;
        }

        return $b->get('id') - $a->get('id');
    }

    public function makeKey(): string
    {
        $entity = $this->get('entity');

        if ($entity === 'Character') {
            $data = $this->decode();

            return $entity . '/' . $data['plin'] . '/' . $data['chin'];
        }

        $key = $entity . '/' . $this->get('key1');
        if ($this->get('key2')) {
            $key .= '/' . $this->get('key2');
        }

        return $key;
    }

    public function modifiedString(): string
    {
        $modified = $this->get('modified');
        if (is_null($modified)) {
            return '(??)';
        }

        return (string)$modified;
    }

    public function modifierString(): string
    {
        $modifier = $this->get('modifier_id');
        if (is_null($modifier)) {
            return '(??)';
        }
        if ($modifier < 0) {
            return '_cli';
        }

        return sprintf('%04d', $modifier);
    }
}
