<?php
namespace App\Lammy;

use App\Model\Entity\Lammy as LammyEntity;
use FPDF;

/**
 *	Given a list of lammies place them in a pdf.
 *
 *	Also handle a other sizes of lammy
 */
class PdfSheet
{
	static private	$M_LEFT	= 29.5;	// paper margins
	static private	$M_TOP	=  8;

	static private	$P_HORZ	=  1;	// padding between lammmies
	static private	$P_VERT	=  2;

	static private	$LAMMIES_Y = 6; // nr's of lammies that fit on one page

	private $lammies = array();		// the lammies we want to print

	private $twosides = false;		// double sided pdf ?
	private $layout = NULL;			// actual layout
	private $spaceleft = 0;			// nr of singlesided slots left

	public function __construct()
	{
		$this->clearLayout();
	}

	public function getSpaceLeft()
	{
		if(!$this->layout)
			$this->makeLayout();

		return $this->spaceleft;
	}

	public function getDoubleSided()
	{
		return $this->twosides;
	}
	public function setDoubleSided($bool)
	{
		if($this->twosides == $bool)
			return;

		$this->twosides = $bool;
		$this->clearLayout();
	}

	/* add a Lammy */
	public function add(Lammy $lammy)
	{
		$this->lammies[] = $lammy;
		$this->clearLayout();
	}

	public function addEntity(LammyEntity $entity)
	{
		$class = 'App\\Lammy\\'.$entity->entity.'Lammy';

		if(!class_exists($class)) {
			echo "geen class: $class";
			die;
		}

		$this->add(new $class($entity->getTarget()));
	}

	private function clearLayout()
	{
		$this->layout = NULL;
		$this->spaceleft = self::$LAMMIES_Y * 2;
	}
	private function makeLayout()
	{
		$todo = array();
		foreach($this->lammies as $key => $lam)
		{
			$todo[] = array($key, $lam->sides());
		}
		if(!$this->twosides) {
			$this->makeLayout1P($todo);
		} else {
			$this->makeLayout2P($todo);
		}
	}
	private function makeLayout1P($todo)
	{
		$this->layout = array();
		$col = 0; $row = 0; $page = 0;
		while(count($todo))
		{
			$add = array();
			if($col == 0) {
				list($key, $sides) = array_shift($todo);
				for($i = 0; $i < $sides; $i++) {
					$add[] = $i;
				}
			} else {
				foreach($todo as $i => $item)
				{
					list($key, $sides) = $item;
					if($sides % 2 == 1) {
						break;
					}

					unset($i);
				}
				if(isset($i)) {
					unset($todo[$i]);
					$add[] = $sides;
					for($i = 0; $i < $sides - 1; $i++) {
						$add[] = $i;
					}
				} else {
					$key = NULL;
					$add[] = 1;
				}
			}
			foreach($add as $i)
			{
				if(isset($key))
					$this->layout[$page][$row][$col] = array($key, $i);

				if(++$col >= 2) {
					$col = 0;
					if(++$row >= self::$LAMMIES_Y) {
						$row = 0;
						$page++;
					}
				}
			}
		}
		$this->spaceleft = (self::$LAMMIES_Y - $row) * 2 - $col;
	}
	private function makeLayout2P($todo)
	{
		$this->layout = array();
		$col = 0; $row = 0; $page = 0;
		while(count($todo) > 0)
		{
			list($key, $sides) = array_shift($todo);
			for($i = 0; $i < $sides; $i += 2)
			{
				$this->layout[$page  ][$row][$col] = array($key, $i);
				if(++$i >= $sides) $key = NULL;
				$this->layout[$page+1][$row][$col] = array($key, $i);
				if(++$col >= 2) {
					$col = 0;
					if(++$row >= self::$LAMMIES_Y) {
						$row = 0;
						$page += 2;
					}
				}
			}
		}
		$this->spaceleft = (self::$LAMMIES_Y - $row) * 2 - $col;
	}

	/* render all the lammies */
	public function createPdf()
	{
		if(!$this->layout)
			$this->makeLayout();

		$pdf = new FPDF('P', 'mm', 'A4');
		$pdf->SetMargins(self::$M_LEFT, self::$M_TOP, self::$M_LEFT);
		$pdf->SetSubject('LammySheet');

		$pdf->SetAutoPageBreak(false);

		for($page = 0; $page < count($this->layout); $page++)
		{
			$pdf->addPage();

			foreach($this->layout[$page] as $row => $rowdata)
			{
				$y = self::row2y($row);
				foreach($rowdata as $col => $item)
				{
					list($key, $side) = $item;
					if(!isset($key))
						continue;

					$x = self::col2x($col);
					$this->lammies[$key]->preDraw($pdf, $x, $y);
					$this->lammies[$key]->draw($side);
				}
			}
		}

		return $pdf->Output('s');
	}

	/* utility methods */
	private static function col2x($col)
	{
		return self::$M_LEFT + (Lammy::$WIDTH  + self::$P_HORZ) * $col;
	}
	private static function row2y($row)
	{
		return self::$M_TOP  + (Lammy::$HEIGHT + self::$P_VERT) * $row;
	}

}
