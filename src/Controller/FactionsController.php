<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Factions Controller
 *
 * @property App\Model\Table\FactionsTable $Factions
 */
class FactionsController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters' ]);
		});
		return $this->Crud->execute();
	}

}
