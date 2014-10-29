<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Manatypes Controller
 *
 * @property App\Model\Table\ManatypesTable $Manatypes
 */
class ManatypesController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('manatypes', $this->paginate($this->Manatypes));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$manatype = $this->Manatypes->get($id, [
			'contain' => ['Skills']
		]);
		$this->set('manatype', $manatype);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$manatype = $this->Manatypes->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Manatypes->save($manatype)) {
				$this->Flash->success('The manatype has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The manatype could not be saved. Please, try again.');
			}
		}
		$this->set(compact('manatype'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$manatype = $this->Manatypes->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$manatype = $this->Manatypes->patchEntity($manatype, $this->request->data);
			if ($this->Manatypes->save($manatype)) {
				$this->Flash->success('The manatype has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The manatype could not be saved. Please, try again.');
			}
		}
		$this->set(compact('manatype'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$manatype = $this->Manatypes->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Manatypes->delete($manatype)) {
			$this->Flash->success('The manatype has been deleted.');
		} else {
			$this->Flash->error('The manatype could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}
