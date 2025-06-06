<?php
declare(strict_types=1);

namespace App\Controller\Admin;

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
        $history = $this->fetchTable('History');
        if (!is_null($e)) {
            $plin = $this->request->getQuery('highlight');
            $plin = empty($plin) ? null : (int)$plin;

            $this->viewBuilder()->setTemplate('compact');
            if (array_key_exists('verbose', $this->request->getQueryParams())) {
                $this->viewBuilder()->setTemplate('entity');
            }

            $list = $history->getEntityHistory($e, $k1, $k2);
            if (empty($list)) {
                throw new NotFoundException();
            }

            $this->set('plin', $plin);
            $this->set('list', $list);

            return;
        }

        $plin = $this->request->getQuery('plin');
        $plin = empty($plin) ? null : (int)$plin;

        $since = $this->request->getQuery('since', '');
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $since);
        if (!$date) {
            $date = new DateTime();
            $date->sub(new DateInterval('P3M'));
        }
        $since = $date->format('Y-m-d');

        $what = $this->request->getQuery('what');

        $this->set('what', $what);
        $this->set('since', $since);
        $this->set('plin', $plin);

        $list = $history->getAllLastModified($plin, $since, $what);

        if ($plin) {
            $all = $history->getAllModificationsBy($plin, $since, $what);
            $list = array_merge($list, $all);
            usort($list, [$history, 'compare']);
        }
        $this->set('list', $list);
    }
}
