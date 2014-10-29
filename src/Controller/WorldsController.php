<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Worlds Controller
 *
 * @property App\Model\Table\WorldsTable $Worlds
 */
class WorldsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('worlds', $this->paginate($this->Worlds));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$world = $this->Worlds->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('world', $world);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$world = $this->Worlds->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Worlds->save($world)) {
				$this->Flash->success('The world has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The world could not be saved. Please, try again.');
			}
		}
		$this->set(compact('world'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$world = $this->Worlds->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$world = $this->Worlds->patchEntity($world, $this->request->data);
			if ($this->Worlds->save($world)) {
				$this->Flash->success('The world has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The world could not be saved. Please, try again.');
			}
		}
		$this->set(compact('world'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$world = $this->Worlds->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Worlds->delete($world)) {
			$this->Flash->success('The world has been deleted.');
		} else {
			$this->Flash->error('The world could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}
