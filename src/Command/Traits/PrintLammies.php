<?php
declare(strict_types=1);

namespace App\Command\Traits;

use App\View\PdfView;

trait PrintLammies
{
    protected function createPdf(int $id, bool $double): ?string
    {
        $lammies = $this->fetchTable()
                        ->find('Queued')
                        ->where(['Lammies.id <=' => $id])
                        ->all();

        if($lammies->count() == 0)
            return null;

        $this->fetchTable()->setStatuses($lammies, 'Printing');

        $pdf = (new PdfView())->createPdf($lammies, $double);
        if (is_null($pdf)) {
            $this->abort('Error generating pdf');
        }

        return $pdf;
    }
}
