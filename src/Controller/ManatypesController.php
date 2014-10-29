<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Manatypes Controller
 *
 * @property App\Model\Table\ManatypesTable $Manatypes
 */
class ManatypesController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Skills' ]);
		});
		return $this->Crud->execute();
	}

}
