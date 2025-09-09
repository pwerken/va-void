<?php
declare(strict_types=1);

namespace App\View;

use App\Lammy\LammyCard;
use Cake\View\View;
use Mpdf\Mpdf;

class PdfView extends View
{
    // paper margins
    private static float $M_TOP = 8;
    private static float $M_SIDE = 30;

    // padding between lammies
    private static float $P_HORZ = 2;
    private static float $P_VERT = 2;

    // nr of rows that fit on one page
    private static int $LAMMIES_Y = 6;

    /**
     * Renders the lammies as a pdf, ready for printing.
     */
    public function render(?string $template = null, string|false|null $layout = null): string
    {
        $lammies = [];
        foreach ($this->get($this->get('viewVar')) as $entity) {
            $lammy = $entity->lammy;
            if (!is_null($lammy)) {
                $lammies[] = $lammy;
            }
        }

        $this->setLayout('pdf');
        $this->response = $this->response->withType('pdf');

        return $this->createPdf($lammies, $this->get('double', false));
    }

    public function createPdf(array $lammies, bool $double): string
    {
        if (empty($lammies)) {
            return '';
        }

        if (!$this->get('double')) {
            $layout = $this->makeLayout1P($lammies);
        } else {
            $layout = $this->makeLayout2P($lammies);
        }

        $pdf = new Mpdf(['tempDir' => TMP]);
        $pdf->SetMargins(self::$M_SIDE, self::$M_SIDE, self::$M_TOP);
        $pdf->SetTitle('Lammies!');
        $pdf->SetAutoPageBreak(false);

        foreach ($layout as $page) {
            $pdf->addPage();
            foreach ($page as $row => $columns) {
                foreach ($columns as $col => [$lammy, $side]) {
                    $lammy->preDraw($pdf, self::col2x($col), self::row2y($row));
                    $lammy->draw($side);
                }
            }
        }

        return $pdf->OutputBinaryData();
    }

    /**
     * Make a layout of the lammies that need to be printed.
     * This method makes layout intended for single-sided printing.
     *
     * It places the front and back side of a lammy on the same row.
     *
     * The return type is: $layout[$page][$row][$column] = [$lammy, $side]
     *
     * @param list<\App\Lammy\LammyCard> $lammies
     * @return array<int, array<int, array<int, array{\App\Lammy\LammyCard, int} >>>
     */
    private function makeLayout1P(array $lammies): array
    {
        $layout = [];

        $page = 0;
        $row = 0;
        $col = 0;
        foreach ($lammies as $lammy) {
            $sides = $lammy->sides();
            $space = 2 * (self::$LAMMIES_Y - $row) - $col;
            if ($space < $sides) {
                // not enough space left, start on next page
                $page++;
                $row = 0;
                $col = 0;
            }
            if ($col == 1) {
                // starting in 2nd column
                if ($sides % 2 == 1) {
                    // try to fill it with a single sided lammy
                    $layout[$page][$row][$col] = [$lammy, --$sides];
                }
                $row++;
                $col = 0;
            }
            for ($i = 0; $i < $sides; $i++) {
                $layout[$page][$row][$col] = [$lammy, $i];
                if ($col == 0) {
                    $col++;
                    continue;
                }
                $row++;
                $col = 0;
            }
        }

        return $layout;
    }

    /**
     * Make a layout of the lammies that need to be printed.
     * This method makes layout intended for double sided printing.
     *
     * It places the back side of the lammy in the mirrored column of the next
     * page.
     *
     * The return type is: $layout[$page][$row][$column] = [$lammy, $side]
     *
     * @param list<\App\Lammy\LammyCard> $lammies
     * @return array<int, array<int, array<int, array{\App\Lammy\LammyCard, int} >>>
     */
    private function makeLayout2P(array $lammies): array
    {
        $layout = [];
        $col = 0;
        $row = 0;
        $page = 0;
        foreach ($lammies as $lammy) {
            $sides = $lammy->sides();
            for ($i = 0; $i < $sides; $i++) {
                $layout[$page][$row][$col] = [$lammy, $i];
                if (++$i < $sides) {
                    $layout[$page + 1][$row][1 - $col] = [$lammy, $i];
                }
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

    /**
     * Convert column number (0 or 1) to x position on the page in mm.
     *
     * @param int $column
     * @return float
     */
    private static function col2x(int $column): float
    {
        return self::$M_SIDE + (LammyCard::$WIDTH + self::$P_HORZ) * $column;
    }

    /**
     * Convert row number (0..) to y position on the page in mm.
     *
     * @param int $row
     * @return float
     */
    private static function row2y(int $row): float
    {
        return self::$M_TOP + (LammyCard::$HEIGHT + self::$P_VERT) * $row;
    }
}
