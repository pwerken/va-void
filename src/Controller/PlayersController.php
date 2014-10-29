<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Players Controller
 *
 * @property App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('players', $this->paginate($this->Players));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$player = $this->Players->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('player', $player);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$player = $this->Players->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Players->save($player)) {
				$this->Flash->success('The player has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The player could not be saved. Please, try again.');
			}
		}
		$this->set(compact('player'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$player = $this->Players->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$player = $this->Players->patchEntity($player, $this->request->data);
			if ($this->Players->save($player)) {
				$this->Flash->success('The player has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The player could not be saved. Please, try again.');
			}
		}
		$this->set(compact('player'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$player = $this->Players->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Players->delete($player)) {
			$this->Flash->success('The player has been deleted.');
		} else {
			$this->Flash->error('The player could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}
