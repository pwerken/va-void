<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\History;
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
            usort($list, [History::class, 'compare']);
        }

        $plins = [];
        foreach ($list as $row) {
            $modifier = $row->get('modifier_id');
            if (is_int($modifier) && $modifier >= 0) {
                $plins[] = $modifier;
            }
        }

        $this->set('lookup', $this->lookupPlins($plins));
        $this->set('list', $list);
    }

    /**
     * GET /admin/history/player/$plin
     */
    public function player(int ...$ids): void
    {
        $this->checkParams($ids);
        $this->entityHistory('player', ...$ids);
    }

    /**
     * GET /admin/history/character/$plin/$chin
     */
    public function character(int ...$ids): void
    {
        $this->checkParams($ids, 2);
        $this->entityHistory('character', ...$ids);
    }

    /**
     * GET /admin/history/item/$itin
     */
    public function item(int ...$ids): void
    {
        $this->checkParams($ids);
        $this->entityHistory('item', ...$ids);
    }

    /**
     * GET /admin/history/condition/$coin
     */
    public function condition(int ...$ids): void
    {
        $this->checkParams($ids);
        $this->entityHistory('condition', ...$ids);
        $this->set('rhs', false);
    }

    /**
     * GET /admin/history/power/$poin
     */
    public function power(int ...$ids): void
    {
        $this->checkParams($ids);
        $this->entityHistory('power', ...$ids);
        $this->set('rhs', false);
    }

    protected function checkParams(array $ids, int $expected = 1): void
    {
        $this->getRequest()->allowMethod(['get']);

        if (count($ids) != $expected) {
            throw new NotFoundException();
        }
    }

    protected function entityHistory(string $e, int $k1, ?int $k2 = null): void
    {
        $list = $this->fetchTable('History')->getEntityHistory($e, $k1, $k2);
        if (empty($list)) {
            throw new NotFoundException();
        }

        $seen = [];
        $plins = [];
        $list = array_reverse($list);
        foreach ($list as $row) {
            $key = $row->makeKey();

            $modifier_id = $row->get('modifier_id');
            if (is_int($modifier_id) && $modifier_id >= 0) {
                $plins[] = $modifier_id;
            }

            if (!isset($seen[$key])) {
                // added
                $row->set('prev', null);
                $seen[$key] = $row;
            } elseif (is_null($row->get('data'))) {
                // removed
                $row->set('prev', $seen[$key]);
                unset($seen[$key]);
            } else {
                // modified
                $row->set('prev', $seen[$key]);
                $seen[$key] = $row;
            }
        }

        $this->viewBuilder()->setTemplate('compact');
        $this->set('lookup', $this->lookupPlins($plins));
        $this->set('history', array_reverse($list));
        $this->set('rhs', true);
    }

    private function lookupPlins(array $plins): array
    {
        sort($plins);
        $plins = array_unique($plins);

        if (empty($plins)) {
            return [];
        }

        $query = $this->fetchTable('Players')->find()
                ->where(['plin IN' => $plins]);

        $lookup = [];
        foreach ($query->all() as $row) {
            $lookup[$row->get('plin')] = $row;
        }

        return $lookup;
    }
}
