<?php
namespace App\View;

use App\Lammy\CharacterLammy;
use App\Lammy\PdfSheet;
use App\Model\Entity\Lammy;
use Cake\View\View;

class PdfView
	extends View
{

	public function render($view = null, $layout = null)
	{
		$this->layout('pdf');

		$data = $this->get($this->get('viewVar'));

		if(is_null($data)) {
			echo "huh?! \$data is null!";
			die;
		}
		if(!($data instanceof Lammy)) {
			echo "huh?! \$data is geen Lammy!";
			die;
		}

		$sheet = new PdfSheet(true);	// true = double sided

		$sheet->add(new CharacterLammy($data->getTarget()));

		$this->response->type('pdf');
		$this->response->header('Content-Disposition', 'inline; filename="lammies.pdf"');
		return $sheet->createPdf();
	}

}
