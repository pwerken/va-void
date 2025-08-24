<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;
use App\Model\Enum\LammyStatus;

class LammiesController extends Controller
{
    use IndexTrait; // GET /lammies
    use ViewTrait; // GET /lammies/{id}
    use EditTrait; // PUT /lammies/{id}
    use DeleteTrait; // DELETE /lammies/{id}

    /**
     * GET /lammies/queue
     * returns the ID of the last item in the queue
     */
    public function queue(): void
    {
        $last = $this->fetchTable()
                ->find('lastInQueue')
                ->disableHydration()
                ->select(['id'])
                ->first();
        $last_id = is_array($last) ? $last['id'] : 0;
        $this->set('_serialize', $last_id);
    }

    /**
     * POST /lammies/single
     * change lammy status to printing
     * returns PDF for single-sided printing
     */
    public function pdfSingle(): void
    {
        $this->pdfOutput($this->intFromBody(), false);
    }

    /**
     * POST /lammies/double
     * change lammy status to printing
     * returns PDF for double-sided printing
     */
    public function pdfDouble(): void
    {
        $this->pdfOutput($this->intFromBody(), true);
    }

    /**
     * POST /lammies/printed
     * change lammy status to printed
     */
    public function printed(): void
    {
        $lammies = $this->fetchTable()
                    ->find('printing')
                    ->where(['Lammies.id <=' => $this->intFromBody()])
                    ->all();
        $this->fetchTable()->setStatuses($lammies, LammyStatus::Printed);

        $this->set('_serialize', count($lammies));
    }

    /**
     * returns PDF for printing
     */
    private function pdfOutput(int $max_id, ?bool $double = false): void
    {
        $lammies = $this->fetchTable()
                    ->find('queued')
                    ->where(['Lammies.id <=' => $max_id])
                    ->all();

        $this->fetchTable()->setStatuses($lammies, LammyStatus::Printing);

        $this->viewBuilder()->setClassName('Pdf');
        $this->set('viewVar', 'lammies');
        $this->set('lammies', $lammies);
        $this->set('double', $double);
    }

    private function intFromBody(): int
    {
        $body_string = (string)$this->getRequest()->getBody();

        return (int)$body_string;
    }
}
