<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Players Controller
 *
 * @property App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController {

	public function view($id = null) {
		$this->Crud->on('beforeFind', function(Event $event) {
			$event->subject->query->contain(
				[ 'Characters' =>
					[ 'Factions'
					, 'Believes'
					, 'Groups'
					, 'Worlds'
					]
				]);
		});
		return $this->Crud->execute();
	}
}
