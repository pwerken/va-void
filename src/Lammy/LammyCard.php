<?php
namespace App\Lammy;

use FPDF;

/**
 *  Contains all the basics for making a lammy.
 */
abstract class LammyCard
{
	static public	$WIDTH	= 75;	// lammy size (in mm)
	static public	$HEIGHT	= 45;	// if changed, fix all the *Lammy classes!

	public    $single = 0;
	public    $double = 0;
	protected $entity = null;
	protected $pdf    = null;
	private   $xPos   = 0;
	private   $yPos   = 0;
	private   $size   = 0;

	public function __construct($entity)
	{
		$this->entity = $entity;
	}

	/************************* USED BY PdfSheet ***************************/

	/***
	 *  Return the number of sides the lammy has.
	 *
	 *  Side 2n is the front and 2n+1 is the back of a single card.
	 */
	abstract public function sides();

	/***
	 *  Called by the LammySheet to fill the pdf.
	 */
	abstract public function draw($side);

	/**
	 *  This method should be called prior to any of the other draw methods.
	 *  It sets the pdf object we will draw on and also where the top left
	 *  corner of the lammy should be.
	 *
	 *  This method called by LammySheet.
	 */
	public function preDraw($pdf, $x, $y)
	{
		$this->pdf  = $pdf;
		$this->xPos = $x;
		$this->yPos = $y;
	}

	protected function cardFront($title)
	{
		$this->border();
		$this->logo(68, 1);
		$this->title($title);
		$this->footer(date('G:i d/m/Y'));
	}
	protected function cardBack($title)
	{
		$this->border();
		$this->logo(1, 1);
		$this->title($title);
		$this->footer('(c) Vortex Adventures');
	}

	/********************* very basic drawing methods *********************/

	protected function border()
	{
		$this->square(0, 0, self::$WIDTH, self::$HEIGHT);
	}
	protected function logo($x, $y)
	{
		$this->pdf->Image(APP . DS . 'Lammy' . DS . 'va_logo.png'
						, $this->xPos + $x, $this->yPos + $y
						, 6, 10);
	}
	protected function title($text)
	{
		$this->pdf->SetTextColor(0);
		$this->font(9);
		$this->text(0, 3, self::$WIDTH, 'C', $text);
	}
	protected function footer($text)
	{
		$this->pdf->SetTextColor(191);
		$this->font(5);
		$this->text(0, self::$HEIGHT - 1.3, self::$WIDTH, 'R', $text);
	}
	protected function square($x1, $y1, $x2, $y2)
	{
		$this->pdf->SetDrawColor(0);
		$this->pdf->rect($this->xPos+$x1, $this->yPos+$y1, $x2-$x1, $y2-$y1);
	}
	protected function font($size, $style = '')
	{
		$this->size = $size;
		$this->pdf->SetFont('Arial', $style, $size);
	}
	protected function text($x, $y, $w, $align, $text, $border = 0)
	{
		$text = utf8_decode($text);
		while($this->pdf->GetStringWidth($text) > $w) {
			$text = substr($text, 0, -1);
		}
		$this->pdf->SetXY($this->xPos + $x, $this->yPos + $y);
		$this->pdf->Cell($w, 0, $text, $border, 0, $align);
		$this->pdf->SetXY($this->xPos, $this->yPos);
	}
	protected function textblock($x, $y, $w, $align, $text, $border = 0)
	{
		$h = $this->size / 2;
		$this->pdf->SetXY($this->xPos + $x, $this->yPos + $y - $h/2);
		$this->pdf->MultiCell($w, $h, utf8_decode($text), $border, $align);
		$this->pdf->SetXY($this->xPos, $this->yPos);
	}

}
