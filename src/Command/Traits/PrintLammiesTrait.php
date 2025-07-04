<?php
declare(strict_types=1);

namespace App\Command\Traits;

use App\View\PdfView;

trait PrintLammiesTrait
{
    protected function createPdf(int $id, bool $double): ?string
    {
        $queue = $this->fetchTable()
                    ->find('queued')
                    ->where(['Lammies.id <=' => $id])
                    ->all();

        if ($queue->count() == 0) {
            return null;
        }

        $this->fetchTable()->setStatuses($queue, 'Printing');

        $lammies = [];
        foreach ($queue as $queued) {
            $lammies[] = $queued->lammy;
        }

        return (new PdfView())->createPdf($lammies, $double);
    }
}
