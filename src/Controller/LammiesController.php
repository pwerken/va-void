<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Model\Table\LammiesTable $Lammies;
 */
class LammiesController extends Controller
{
    use DeleteTrait; // DELETE /lammies/{id}
    use EditTrait; // PUT /lammies/{id}
    use ViewTrait; // GET /lammies/{id}

    /**
     * GET /lammies
     */
    public function index(): void
    {
        $query = $this->Lammies->find()
                    ->select([], true)
                    ->select('Lammies.id')
                    ->select('Lammies.status')
                    ->select('Lammies.entity')
                    ->select('Lammies.key1')
                    ->select('Lammies.key2')
                    ->select('Lammies.modified');
        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $content[] = [
                'class' => 'Lammy',
                'url' => '/lammies/' . $row[0],
                'status' => $row[1],
                'entity' => $row[2],
                'key1' => (int)$row[3],
                'key2' => (int)$row[4],
                'modified' => $row[5],
            ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }

    /**
     * GET /lammies/queue
     * returns the ID of the last item in the queue
     */
    public function queue(): void
    {
        $last = $this->Lammies
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
        $lammies = $this->Lammies
                    ->find('printing')
                    ->where(['Lammies.id <=' => $this->intFromBody()])
                    ->all();
        $this->Lammies->setStatuses($lammies, 'Printed');

        $this->set('_serialize', count($lammies));
    }

    /**
     * returns PDF for printing
     */
    private function pdfOutput(int $max_id, ?bool $double = false): void
    {
        $lammies = $this->Lammies
                    ->find('queued')
                    ->where(['Lammies.id <=' => $max_id])
                    ->all();

        $this->Lammies->setStatuses($lammies, 'Printing');

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
