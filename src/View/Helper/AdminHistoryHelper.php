<?php
declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\Character;
use App\Model\Entity\History;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\View\Helper;

class AdminHistoryHelper extends Helper
{
    use LocatorAwareTrait;

    public function formatField(string $field, mixed $value): string
    {
        $prefix = '<em>' . $field . ':</em> ';
        if (is_null($value)) {
            return $prefix . '<em>NULL</em>';
        }
        $value = var_export($value, true);

        if ($field === 'teacher_id' || $field === 'character_id') {
            $char = $this->getCharacter((int)$value);
            if ($char) {
                $value .= ' = '
                    . $char->get('plin') . '/' . $char->get('chin')
                    . ' ' . $char->get('name');
            }
        }

        return $prefix . nl2br(h($value));
    }

    public function getName(?History $h, bool $rhs = true): string
    {
        if (is_null($h)) {
            return '';
        }

        $data = $h->decode();
        $entity = $h->get('entity');

        if ($entity === 'Player') {
            $fields = [$data['first_name'], $data['insertion'], $data['last_name']];

            return implode(' ', array_filter($fields));
        }

        if ($entity === 'Teaching' && !empty($data)) {
            $obj = $this->fetchTable('Skills')
                    ->find()
                    ->where(['id' => $data['skill_id']])
                    ->first();

            return $obj?->get('name') ?? '(removed)';
        }

        if (str_starts_with($entity, 'Characters')) {
            if ($rhs) {
                $table = $this->fetchTable(substr($entity, 10) . 's');
                $key = $table->getPrimaryKey();
                $obj = $table->find()->where([$key => $h->get('key2')])->first();

                return $obj?->get('name') ?? '(removed)';
            }

            $obj = $this->getCharacter($h->get('key1'));
            if (is_null($obj)) {
                return '(removed)';
            }

            return $obj->get('plin') . '/' . $obj->get('chin')
                . ' ' . $obj->get('name');
        }

        return $data['name'] ?? '';
    }

    public function makeLink(History $h): array
    {
        $entity = $h->get('entity');

        $link = [];
        $link['controller'] = 'History';
        $link['action'] = strtolower($entity);
        if ($entity === 'Character') {
            $data = $h->decode();
            $link[] = $data['plin'];
            $link[] = $data['chin'];
        } else {
            $link[] = $h->get('key1');
        }

        return $link;
    }

    public function relationLink(History $h, bool $rhs = true): ?array
    {
        $entity = $h->get('entity');

        $link = [];
        $link['controller'] = 'History';

        switch ($entity) {
            case 'CharactersImbue':
            case 'CharactersGlyphImbue':
            case 'CharactersRuneImbue':
                if ($rhs) {
                    $link['action'] = strtolower(substr($entity, -5));
                    $link[] = $h->get('key2');
                } else {
                    $obj = $this->getCharacter($h->get('key1'));
                    $link['action'] = 'character';
                    $link[] = $obj->get('plin');
                    $link[] = $obj->get('chin');
                }

                return $link;
            case 'CharactersPower':
            case 'CharactersCondition':
                if ($rhs) {
                    $link['action'] = strtolower(substr($entity, 10));
                    $link[] = $h->get('key2');
                } else {
                    $obj = $this->getCharacter($h->get('key1'));
                    $link['action'] = 'character';
                    $link[] = $obj->get('plin');
                    $link[] = $obj->get('chin');
                }

                return $link;
        }

        return null;
    }

    private function getCharacter(int $character_id): ?Character
    {
        return $this->fetchTable('Characters')
                    ->find()
                    ->where(['id' => $character_id])
                    ->first();
    }
}
