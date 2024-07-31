<?php
namespace RPDF;

use FPDF;

class Rpdf
    extends FPDF
{

    function TextWithDirection($x, $y, $txt, $direction='R')
    {
        if ($direction=='L') {
            $a = $d = -1;
            $b = $c = 0;
        } elseif ($direction=='U') {
            $a = $d = 0;
            $b = 1;
            $c = -1;
        } elseif ($direction=='D') {
            $a = $d = 0;
            $b = -1;
            $c = 1;
        } else {
            $a = $d = 1;
            $b = $c = 0;
        }
        $this->MyHelper($a, $b, $c, $d, $x, $y, $txt);
    }

    function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
    {
        $font_angle += 90 + $txt_angle;
        $txt_angle *= M_PI/180;
        $font_angle *= M_PI/180;

        $a = cos($txt_angle);
        $b = sin($txt_angle);
        $c = cos($font_angle);
        $d = sin($font_angle);

        $this->MyHelper($a, $b, $c, $d, $x, $y, $txt);
    }

    private function MyHelper($a, $b, $c, $d, $x, $y, $txt)
    {
        $e = $x * $this->k;
        $f = ($this->h - $y) * $this->k;
        $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
                $a, $b, $c, $d, $e, $f, $this->_escape($txt));

        if ($this->ColorFlag)
            $s = 'q '.$this->TextColor.' '.$s.' Q';

        $this->_out($s);
    }
}
