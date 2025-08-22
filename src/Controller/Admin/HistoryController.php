<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\History;
use App\Utility\Json;
use Cake\Http\Exception\NotFoundException;
use DateInterval;
use DateTime;
use DateTimeImmutable;

class HistoryController extends AdminController
{
    /**
     * GET /admin/history
     */
    public function index(): void
    {
        $plin = $this->request->getQuery('plin');
        $plin = empty($plin) ? null : (int)$plin;
        $this->set('plin', $plin);

        $since = $this->request->getQuery('since', '');
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $since);
        if (!$date) {
            $date = new DateTime();
            $date->sub(new DateInterval('P3M'));
        }
        $since = $date->format('Y-m-d');
        $this->set('since', $since);

        $what = $this->request->getQuery('what');
        $this->set('what', $what);

        $table = $this->fetchTable('History');
        $list = $table->getAllLastModified($plin, $since, $what);
        if ($plin) {
            $all = $table->getAllModificationsBy($plin, $since, $what);
            $list = array_merge($list, $all);
            usort($list, [$table, 'compare']);
        }

        $plins = [];
        foreach ($list as $row) {
            if (is_int($row['modifier_id']) && $row['modifier_id'] >= 0) {
                $plins[] = $row['modifier_id'];
            }
        }

        $this->set('modifier_names', $this->retrieveModifierNames($plins));
        $this->set('list', $list);
    }

    /**
     * GET /admin/history/player/$plin
     */
    public function player(int $plin): void
    {
        $this->entityHistory('player', $plin);
    }

    /**
     * GET /admin/history/character/$plin/$chin
     */
    public function character(int $plin, int $chin): void
    {
        $this->entityHistory('character', $plin, $chin);
    }

    /**
     * GET /admin/history/item/$itin
     */
    public function item(int $itin): void
    {
        $this->entityHistory('item', $itin);
    }

    /**
     * GET /admin/history/condition/$coin
     */
    public function condition(int $coin): void
    {
        $this->entityHistory('condition', $coin);
    }

    /**
     * GET /admin/history/power/$poin
     */
    public function power(int $poin): void
    {
        $this->entityHistory('power', $poin);
    }

    protected function entityHistory(string $e, int $k1, ?int $k2 = null): void
    {
        $this->viewBuilder()->setTemplate('compact');

        $characters = $this->fetchTable('Characters');
        $skills = $this->fetchTable('Skills');

        $table = $this->fetchTable('History');
        $list = $table->getEntityHistory($e, $k1, $k2);
        if (empty($list)) {
            throw new NotFoundException();
        }

        $seen = [];
        $history = [];
        $plins = [];

        foreach (array_reverse($list) as $row) {
            $cur = $this->entityLink($e, $row);

            $data = Json::decode($row->data ?? '{}');
            $key = $row->keyString();

            $modifier_id = $row->get('modifier_id');
            if (is_int($modifier_id) && $modifier_id >= 0) {
                $plins[] = $modifier_id;
            }

            if (!isset($seen[$key])) {
                $cur['state'] = 'added';
                $seen[$key] = $data;

                foreach ($data as $field => $value) {
                    switch ($field) {
                        case 'modified':
                        case 'modifier_id':
                        case 'created':
                        case 'creator_id':
                            continue 2;

                        case 'teacher_id':
                        case 'character_id':
                            if ($value) {
                                $char = $characters->get($value);
                                $id = $char->get('player_id') . '/' . $char->get('chin');
                                $value = $id . ' ' . $char->get('name');
                                $cur['link'] = '/character/' . $id;
                            }
                            break;
                        case 'skill_id':
                            if ($value) {
                                $skill = $skills->get($value);
                                $value = $value . ' ' . $skill->get('name');
                            }
                            break;
                    }

                    $cur['field'] = $field;
                    $cur['value'] = $value;
                    $history[] = $cur;
                }

                if (empty($data)) {
                    $history[] = $cur;
                }
            } elseif ($row->data === null) {
                $cur['state'] = 'removed';
                $cur['field'] = null;
                $history[] = $cur;
                unset($seen[$key]);
            } else {
                foreach ($seen[$key] as $field => $oldvalue) {
                    if (array_key_exists($field, $data)) {
                        continue;
                    }

                    $cur['field'] = $field;
                    $cur['state'] = 'removed';
                    $cur['value'] = $oldvalue;
                    $history[] = $cur;
                }

                foreach ($data as $field => $value) {
                    if (array_key_exists($field, $seen[$key])) {
                        if ($seen[$key][$field] == $value) {
                            continue;
                        }
                        $cur['state'] = 'modified';
                    } else {
                        $cur['state'] = 'added';
                    }

                    $cur['link'] = false;
                    switch ($field) {
                        case 'modified':
                        case 'modifier_id':
                        case 'created':
                        case 'creator_id':
                            continue 2;

                        case 'teacher_id':
                        case 'character_id':
                            if ($value) {
                                $char = $characters->get($value);
                                $id = $char->get('player_id') . '/' . $char->get('chin');
                                $value = $id . ' ' . $char->get('name');
                                $cur['link'] = '/character/' . $id;
                            }
                            break;
                    }

                    $cur['field'] = $field;
                    $cur['value'] = $value;
                    $history[] = $cur;
                }
                $seen[$key] = $data;
            }
        }

        $this->set('modifier_names', $this->retrieveModifierNames($plins));
        $this->set('history', array_reverse($history));
    }

    protected function entityLink(string $self, History $row): array
    {
        $fromCharacter = ($self == 'character');

        $cur = [];
        $cur['modified'] = $row->modifiedString();
        $cur['modifier'] = $row->modifierString();
        $cur['modifier_id'] = $row->get('modifier_id');

        $data = Json::decode($row->get('data') ?? '{}');

        switch ($row->get('entity')) {
            case 'Character':
                $cur['link'] = '/character' . '/' . $data['player_id'] . '/' . $data['chin'];
                $cur['name'] = $data['name'];
                $cur['key'] = 'Character' . '/' . $data['player_id'] . '/' . $data['chin'];
                break;
            case 'CharactersCondition':
                if ($fromCharacter) {
                    $cur['link'] = '/condition/' . $row->get('key2');
                    $cur['name'] = $row->get('condition')?->get('name');
                } else {
                    $character = $row->get('character');
                    $cur['link'] = '/character/' . $character->get('player_id') . '/' . $character->get('chin');
                    $cur['name'] = $character->get('name');
                }
                $cur['key'] = $row->keyString();
                break;
            case 'CharactersPower':
                if ($fromCharacter) {
                    $cur['link'] = '/power/' . $row->get('key2');
                    $cur['name'] = $row->get('power')?->get('name');
                } else {
                    $character = $row->get('character');
                    $cur['link'] = '/character/' . $character->get('player_id') . '/' . $character->get('chin');
                    $cur['name'] = $character->get('name');
                }
                $cur['key'] = $row->keyString();
                break;
            case 'Player':
                $cur['link'] = '/player/' . $row->get('key');
                $cur['name'] = implode(' ', [$data['first_name'], $data['insertion'], $data['last_name']]);
                $cur['key'] = $row->keyString();
                break;
            case 'Teaching':
                $cur['link'] = false;
                $cur['name'] = '';
                $cur['key'] = $row->keyString();
                break;
            default:
                if (!isset($data['name'])) {
                    $data['name'] = null;
                }

                $cur['link'] = false;
                $cur['name'] = $row->relation()->name ?? $data['name'];
                $cur['key'] = $row->keyString();
        }

        if (strtolower($row->get('entity')) == $self || is_null($cur['name'])) {
            $cur['link'] = false;
        }

        return $cur;
    }

    protected function retrieveModifierNames(array $plins): array
    {
        if (empty($plins)) {
            return [];
        }

        $result = [];
        $query = $this->fetchTable('Players')->find('all')->where(['id IN' => $plins]);
        foreach ($query->all() as $row) {
            $result[$row->id] = $row->get('name');
        }

        return $result;
    }
}
