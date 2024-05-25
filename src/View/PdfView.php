<?php
declare(strict_types=1);

namespace App\View;

use App\Lammy\LammyCard;
use App\Model\Entity\Lammy;
use Cake\ORM\ResultSet;
use Cake\View\View;
use FPDF;

class PdfView
    extends View
{

    static private $M_TOP   =  8;   // paper margins
    static private $M_SIDE  = 29;

    static private $P_HORZ  =  2;   // padding between lammmies
    static private $P_VERT  =  2;

    static private $LAMMIES_Y = 6;  // nr's of lammies that fit on one page

    public function render(?string $view = null, $layout = null): string
    {
        $this->setLayout('pdf');

        $data = $this->get($this->get('viewVar'));

        $this->response = $this->response->withType('pdf');
#       $this->response->header('Content-Disposition', 'inline; filename="lammies.pdf"');
        return $this->createPdf($data, $this->get('double'));
    }

    public function createPdf($data, $twosided = false): string
    {
        $lammies = [];
        $todo = [];
        foreach($data as $key => $entity) {
            $lammy = $entity->lammy;
            if(is_null($lammy)) {
                continue;
            }
            $lammies[$key] = $lammy;
            $todo[] = [$key, $lammy->sides()];
        }

        if(empty($todo)) {
            return "";
        }

        if(!$twosided) {
            $layout = $this->makeLayout1P($todo);
        } else {
            $layout = $this->makeLayout2P($todo);
        }

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(self::$M_SIDE, self::$M_TOP, self::$M_SIDE);
        $pdf->SetTitle('Lammies!');
        $pdf->SetAutoPageBreak(false);

        for($page = 0; $page < count($layout); $page++)
        {
            $pdf->addPage();

            foreach($layout[$page] as $row => $rowdata)
            {
                $y = self::row2y($row);
                foreach($rowdata as $col => $item)
                {
                    list($key, $side) = $item;
                    if(!isset($key))
                        continue;

                    $x = self::col2x($col);
                    $lammies[$key]->preDraw($pdf, $x, $y);
                    $lammies[$key]->draw($side);
                }
            }
        }

        return $pdf->Output('s');
    }
    private function makeLayout1P($todo)
    {
        $layout = [];
        $col = 0; $row = 0; $page = 0;
        foreach($todo as list($key, $sides))
        {
            $space = 2*(self::$LAMMIES_Y - $row) - $col;
            if($space < $sides) {
                // lammies don't fit on page, goto next page
                for(; $row < self::$LAMMIES_Y; $row++) {
                    for(; $col < 2; $col++) {
                        $layout[$page][$row][$col] = [null, 1];
                    }
                    $col = 0;
                }
                $row = 0;
                $page++;
            }
            if($col == 1) { // starting in 2nd column, try to fill it
                $add = ($sides % 2 == 1) ? [$key, --$sides] : [null, 1];
                $layout[$page][$row][$col] = $add;
                $col = 0;
                $row++;
            }
            for($i = 0; $i < $sides; $i++) {
                $layout[$page][$row][$col] = [$key, $i];
                if($col == 0) {
                    $col++;
                    continue;
                }
                $col = 0;
                $row++;
            }
        }
        return $layout;
    }
    private function makeLayout2P($todo)
    {
        $layout = [];
        $col = 0; $row = 0; $page = 0;
        while(count($todo) > 0)
        {
            list($key, $sides) = array_shift($todo);
            for($i = 0; $i < $sides; $i++)
            {
                $layout[$page  ][$row][$col] = [$key, $i];
                if(++$i >= $sides) $key = NULL;
                $layout[$page+1][$row][1-$col] = [$key, $i];
                if(++$col >= 2) {
                    $col = 0;
                    if(++$row >= self::$LAMMIES_Y) {
                        $row = 0;
                        $page += 2;
                    }
                }
            }
        }
        return $layout;
    }

    /* utility methods */
    private static function col2x($col)
    {
        return self::$M_SIDE + (LammyCard::$WIDTH  + self::$P_HORZ) * $col;
    }
    private static function row2y($row)
    {
        return self::$M_TOP  + (LammyCard::$HEIGHT + self::$P_VERT) * $row;
    }

}
