<?php
declare(strict_types=1);

namespace App\Lammy;

use App\Model\Entity\Entity;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use RPDF\Rpdf;

/**
 *  Contains all the basics for making a lammy.
 */
abstract class LammyCard
{
    public static float $WIDTH = 75; // lammy size (in mm)
    public static float $HEIGHT = 45; // if changed, fix all the *Lammy classes!

    public int $single = 0;
    public int $double = 0;
    protected ?Entity $entity = null;
    protected ?Rpdf $pdf = null;
    private float $xPos = 0;
    private float $yPos = 0;
    private float $size = 0;
    protected ?int $who = null;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function printedBy(?int $plin): void
    {
        $this->who = $plin;
    }

    /************************* USED BY PdfSheet ***************************/

    /***
     *  Return the number of cards the lammy has.
     */
    public function cards(): int
    {
        return 1;
    }

    /***
     *  Return the number of sides the lammy has.
     *
     *  Side 2n is the front and 2n+1 is the back of a single card.
     */
    public function sides(): int
    {
        return 2;
    }

    /***
     *  Called by the LammySheet to fill the pdf.
     */
    abstract public function draw(int $side): void;

    /**
     *  This method should be called prior to any of the other draw methods.
     *  It sets the pdf object we will draw on and also where the top left
     *  corner of the lammy should be.
     *
     *  This method called by LammySheet.
     */
    public function preDraw(Rpdf $pdf, float $x, float $y): void
    {
        $this->pdf = $pdf;
        $this->xPos = $x;
        $this->yPos = $y;
    }

    protected function cardFront(string $title): void
    {
        $this->border();
        $this->logo(68, 1);
        $this->title($title);
        $this->footer(date('G:i d/m/Y'));
    }

    protected function cardBack(string $title): void
    {
        $this->border();
        $this->logo(1, 1);
        $this->title($title);
        $this->footer('(c) Vortex Adventures');
    }

    //******************** very basic drawing methods *********************

    protected function border(): void
    {
        $this->square(0, 0, self::$WIDTH, self::$HEIGHT);
    }

    protected function logo(float $x, float $y): void
    {
        $this->image('va_logo.png', $x, $y, 6, 10);
    }

    protected function title(string $text): void
    {
        $this->pdf->SetTextColor(0);
        $this->font(9);
        $this->text(0, 3, self::$WIDTH, 'C', $text);
    }

    protected function footer(string $text): void
    {
        $this->pdf->SetTextColor(191);
        $this->font(5);
        $this->text(0, self::$HEIGHT - 1.3, self::$WIDTH, 'R', $text);
    }

    protected function square(float $x1, float $y1, float $x2, float $y2): void
    {
        $this->pdf->SetDrawColor(0);
        $this->pdf->rect($this->xPos + $x1, $this->yPos + $y1, $x2 - $x1, $y2 - $y1);
    }

    protected function font(float $size, string $style = ''): void
    {
        $this->size = $size;
        $this->pdf->SetFont('Arial', $style, $size);
    }

    protected function image(string $filename, float $x, float $y, float $w, float $h): void
    {
        $this->pdf->Image(
            APP . DS . 'Lammy' . DS . $filename,
            $this->xPos + $x,
            $this->yPos + $y,
            $w,
            $h,
        );
    }

    protected function qrcode(): void
    {
        $ecc = ErrorCorrectionLevel::valueOf('L');
        $matrix = Encoder::encode($this->entity->getUrl(), $ecc, 'UTF-8')->getMatrix();

        $width = $matrix->getWidth();
        $height = $matrix->getHeight();

        $qrcodeSize = 18;
        $x = $this->xPos + self::$WIDTH - 1 - $qrcodeSize;
        $y = $this->yPos + self::$HEIGHT - 3 - $qrcodeSize;
        $blockWidth = $qrcodeSize / $width;
        $blockHeight = $qrcodeSize / $height;

        for ($row = 0; $row < $height; $row++) {
            for ($col = 0; $col < $width; $col++) {
                if ($matrix->get($col, $row) === 1) {
                    $this->pdf->Rect(
                        $x + $col * $blockWidth,
                        $y + $row * $blockHeight,
                        $blockWidth,
                        $blockHeight,
                        'F',
                    );
                }
            }
        }
    }

    protected function text(float $x, float $y, float $w, string $align, mixed $text, int $border = 0): void
    {
        if (is_null($text)) {
            return;
        }

        $text = mb_convert_encoding((string)$text, 'ISO-8859-1', 'UTF-8');

        while ($this->pdf->GetStringWidth($text) > $w) {
            $text = substr($text, 0, -1);
        }
        $this->pdf->SetXY($this->xPos + $x, $this->yPos + $y);
        $this->pdf->Cell($w, 0, $text, $border, 0, $align);
        $this->pdf->SetXY($this->xPos, $this->yPos);
    }

    protected function textarea(float $x, float $y, float $w, float $h, string $text): void
    {
        $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');

        preg_match_all('/\s*[^\s]+/', $text, $matches);
        $matches = $matches[0];

        $l = 0;
        $outputlines = [];
        $outputlines[0] = '';
        $spaceonline = $w - .75;
        foreach ($matches as $lines) {
            $words = preg_split('/\n/', $lines);
            foreach ($words as $i => $word) {
                $length = $this->pdf->GetStringWidth($word);

                if ($i > 0 || $length > $spaceonline) {
                    $spaceonline = $w - .75;
                    $outputlines[++$l] = '';

                    $word = ltrim($word);
                    $length = $this->pdf->GetStringWidth($word);
                }
                $outputlines[$l] .= $word;
                $spaceonline -= $length;
            }
        }
        $lineheigth = $this->size / 2;
        foreach ($outputlines as $i => $line) {
            $offset = $i * $lineheigth;
            if ($offset + $lineheigth > $h) {
                break;
            }
            $this->text($x, $y + $offset, $w, 'L', $line);
        }
    }

    protected function textblock(float $x, float $y, float $w, string $align, string $text, int $border = 0): void
    {
        $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');

        $h = $this->size / 2;
        $this->pdf->SetXY($this->xPos + $x, $this->yPos + $y - $h / 2);
        $this->pdf->MultiCell($w, $h, $text, $border, $align);
        $this->pdf->SetXY($this->xPos, $this->yPos);
    }

    protected function inMargin(string $text): void
    {
        $this->font(9);

        $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        $w = $this->pdf->GetStringWidth($text);

        $x = -5;
        $y = (self::$HEIGHT - $w) / 2;
        $a = 'D';

        if ($this->xPos > self::$WIDTH) {
            $x = self::$WIDTH + 5;
            $y = (self::$HEIGHT + $w) / 2;
            $a = 'U';
        }
        $x += $this->xPos;
        $y += $this->yPos;

        $this->pdf->TextWithDirection($x, $y, $text, $a);
    }
}
