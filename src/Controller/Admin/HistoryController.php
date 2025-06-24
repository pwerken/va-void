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
     * GET /admin/history/...
     */
    public function index(?string $e = null, string|int|null $k1 = null, string|int|null $k2 = null): void
    {
        if (!is_null($e)) {
            $this->entityHistory($e, $k1, $k2);

            return;
        }

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

    protected function entityHistory(string $e, mixed $k1, mixed $k2): void
    {
        $this->viewBuilder()->setTemplate('compact');

        $plin = $this->request->getQuery('highlight');
        $plin = empty($plin) ? null : (int)$plin;
        $this->set('plin', $plin);

        $characters = $this->fetchTable('Characters');
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

                        case 'character_id':
                            if ($value) {
                                $char = $characters->get($value);
                                $id = $char->player_id . '/' . $char->chin;
                                $value = $id . ' ' . $char->name;
                                $cur['link'] = '/character/' . $id;
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

                        case 'character_id':
                            if ($value) {
                                $char = $characters->get($value);
                                $id = $char->player_id . '/' . $char->chin;
                                $value = $id . ' ' . $char->name;
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
        $cur['modifier_id'] = $row->modifier_id;

        $data = Json::decode($row->data ?? '{}');

        switch ($row->entity) {
            case 'Character':
                $cur['link'] = '/character' . '/' . $data['player_id'] . '/' . $data['chin'];
                $cur['name'] = $data['name'];
                $cur['key'] = 'Character' . '/' . $data['player_id'] . '/' . $data['chin'];
                break;
            case 'CharactersCondition':
                if ($fromCharacter) {
                    $cur['link'] = '/condition/' . $row->key2;
                    $cur['name'] = $row->condition?->get('name');
                } else {
                    $cur['link'] = '/character/' . $row->character->player_id . '/' . $row->character->chin;
                    $cur['name'] = $row->character?->get('name');
                }
                $cur['key'] = $row->keyString();
                break;
            case 'CharactersPower':
                if ($fromCharacter) {
                    $cur['link'] = '/power/' . $row->key2;
                    $cur['name'] = $row->power?->get('name');
                } else {
                    $cur['link'] = '/character/' . $row->relation()->player_id . '/' . $row->relation()->chin;
                    $cur['name'] = $row->character?->get('name');
                }
                $cur['key'] = $row->keyString();
                break;
            case 'Player':
                $cur['link'] = '/player/' . $row->key1;
                $cur['name'] = implode(' ', [$data['first_name'], $data['insertion'], $data['last_name']]);
                $cur['key'] = $row->keyString();
                break;
            default:
                if (!isset($data['name'])) {
                    $data['name'] = null;
                }

                $cur['link'] = false;
                $cur['name'] = $row->relation()?->name ?? $data['name'];
                $cur['key'] = $row->keyString();
        }

        if (strtolower($row->entity) == $self || is_null($cur['name'])) {
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
            $result[$row->id] = $row->full_name;
        }

        return $result;
    }
}
