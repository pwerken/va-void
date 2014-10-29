<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Believes Controller
 *
 * @property App\Model\Table\BelievesTable $Believes
 */
class BelievesController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain([ 'Characters' ]);
		});
		return $this->Crud->execute();
	}

}
