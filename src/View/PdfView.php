<?php
namespace App\View;

use App\Lammy\LammyCard;
use App\Model\Entity\Lammy;
use Cake\View\View;
use FPDF;

class PdfView
	extends View
{

	static private $M_TOP	=  8;   // paper margins
	static private $M_SIDE	= 29.5;

	static private $P_HORZ	=  1;   // padding between lammmies
	static private $P_VERT	=  2;

	static private $LAMMIES_Y = 6;  // nr's of lammies that fit on one page

	private $lammies = [];          // the lammies we want to print

	public function render($view = null, $layout = null)
	{
		$this->layout('pdf');

		$data = $this->get($this->get('viewVar'));

		if(is_null($data)) {
			echo "huh?! \$data is null!"; #FIXME throw exception
			die;
		}
		if(!($data instanceof Lammy)) {
			echo "huh?! \$data is geen Lammy!"; #FIXME throw exception
			die;
		}

		$this->addEntity($data);

		$this->response->type('pdf');
#		$this->response->header('Content-Disposition', 'inline; filename="lammies.pdf"');
		return $this->createPdf(false);
	}

	private function addEntity(Lammy $entity)
	{
		$class = 'App\\Lammy\\'.$entity->entity.'Lammy';

		if(!class_exists($class)) {
			echo "geen class: $class"; #FIXME throw exception
			die;
		}

		$this->lammies[] = new $class($entity->target);
	}
	private function createPdf($twosided = false)
	{
		$todo = [];
		foreach($this->lammies as $key => $lam) {
			$todo[] = [$key, $lam->sides()];
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
					$this->lammies[$key]->preDraw($pdf, $x, $y);
					$this->lammies[$key]->draw($side);
				}
			}
		}

		return $pdf->Output('s');
	}
	private function makeLayout1P($todo)
	{
		$layout = [];
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
					$layout[$page][$row][$col] = array($key, $i);

				if(++$col >= 2) {
					$col = 0;
					if(++$row >= self::$LAMMIES_Y) {
						$row = 0;
						$page++;
					}
				}
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
			for($i = 0; $i < $sides; $i += 2)
			{
				$layout[$page  ][$row][$col] = array($key, $i);
				if(++$i >= $sides) $key = NULL;
				$layout[$page+1][$row][$col] = array($key, $i);
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
