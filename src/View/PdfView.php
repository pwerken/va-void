<?php
declare(strict_types=1);

namespace App\View;

use App\Lammy\LammyCard;
use Cake\View\View;
use RPDF\Rpdf;

class PdfView extends View
{
    private static int $M_TOP = 8; // paper margins
    private static int $M_SIDE = 29;

    private static int $P_HORZ = 2; // padding between lammmies
    private static int $P_VERT = 2;

    private static int $LAMMIES_Y = 6; // nr's of lammies that fit on one page

    public function render(?string $template = null, string|false|null $layout = null): string
    {
        $lammies = [];
        $todo = [];
        foreach ($this->get($this->get('viewVar')) as $key => $entity) {
            $lammy = $entity->lammy;
            if (is_null($lammy)) {
                continue;
            }

            $lammy->printedBy($entity->creator_id);
            $lammies[$key] = $lammy;
            $todo[] = [$key, $lammy->sides()];
        }

        if (empty($todo)) {
            return '';
        }

        if (!$this->get('double')) {
            $layout = $this->makeLayout1P($todo);
        } else {
            $layout = $this->makeLayout2P($todo);
        }

        $pdf = new Rpdf('P', 'mm', 'A4');
        $pdf->SetMargins(self::$M_SIDE, self::$M_TOP, self::$M_SIDE);
        $pdf->SetTitle('Lammies!');
        $pdf->SetAutoPageBreak(false);

        $pages = count($layout);
        for ($page = 0; $page < $pages; $page++) {
            $pdf->addPage();
            foreach ($layout[$page] as $row => $rowdata) {
                $y = self::row2y($row);
                foreach ($rowdata as $col => $item) {
                    [$key, $side] = $item;
                    if (!isset($key)) {
                        continue;
                    }
                    $x = self::col2x($col);
                    $lammies[$key]->preDraw($pdf, $x, $y);
                    $lammies[$key]->draw($side);
                }
            }
        }

        $this->setLayout('pdf');
        $this->response = $this->response->withType('pdf');
        return $pdf->Output('s');
    }

    private function makeLayout1P(array $todo): array
    {
        $layout = [];
        $page = 0;
        $row = 0;
        $col = 0;
        foreach ($todo as [$key, $sides]) {
            $space = 2 * (self::$LAMMIES_Y - $row) - $col;
            if ($space < $sides) {
                // not enough space left
                // fill current page with blanks
                for (; $row < self::$LAMMIES_Y; $row++) {
                    for (; $col < 2; $col++) {
                        $layout[$page][$row][$col] = [null, 1];
                    }
                    $col = 0;
                }
                $row = 0;
                $page++;
            }
            if ($col == 1) { // starting in 2nd column, try to fill it
                $add = $sides % 2 == 1 ? [$key, --$sides] : [null, 1];
                $layout[$page][$row][$col] = $add;
                $col = 0;
                $row++;
            }
            for ($i = 0; $i < $sides; $i++) {
                $layout[$page][$row][$col] = [$key, $i];
                if ($col == 0) {
                    $col++;
                    continue;
                }
                $col = 0;
                $row++;
            }
        }

        return $layout;
    }

    private function makeLayout2P(array $todo): array
    {
        $layout = [];
        $col = 0;
        $row = 0;
        $page = 0;
        foreach ($todo as [$key, $sides]) {
            for ($i = 0; $i < $sides; $i++) {
                $layout[$page][$row][$col] = [$key, $i];
                if (++$i >= $sides) {
                    $key = null;
                }
                $layout[$page + 1][$row][1 - $col] = [$key, $i];
                if (++$col >= 2) {
                    $col = 0;
                    if (++$row >= self::$LAMMIES_Y) {
                        $row = 0;
                        $page += 2;
                    }
                }
            }
        }

        return $layout;
    }

    private static function col2x(int $col): int
    {
        return self::$M_SIDE + (LammyCard::$WIDTH + self::$P_HORZ) * $col;
    }

    private static function row2y(int $row): int
    {
        return self::$M_TOP + (LammyCard::$HEIGHT + self::$P_VERT) * $row;
    }
}
