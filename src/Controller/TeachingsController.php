<?php
namespace App\Controller;

class TeachingsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersIndex',  [ 'read-only', 'user' ], true);
		$this->mapMethod('charactersAdd',    [ 'infobalie'         ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'         ]);
		$this->mapMethod('charactersEdit',   [ 'infobalie'         ]);
		$this->mapMethod('charactersView',   [ 'read-only', 'user' ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'infobalie' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function charactersEdit($student_id)
	{
		$action = 'charactersEdit';
		if(!$this->Teachings->exists(['student_id = ' => $student_id])) {
			$action = 'charactersAdd';
			$this->request = $this->request->withData('student_id', $student_id);
		}

		$plin = $this->request->getData('plin');
		$chin = $this->request->getData('chin');
		$this->request = $this->request->withData('plin', NULL);
		$this->request = $this->request->withData('chin', NULL);

		$char_id = NULL;
		if($plin || $chin) {
			$chars = $this->loadModel('Characters');
			$char = $chars->findByPlayerIdAndChin($plin, $chin)->first();
			$char_id = $char ? $char->id : -1;
		}
		$this->request->withData('teacher_id', $char_id);

		$this->dataNameToId('events', 'updated');
		$this->dataNameToId('events', 'started');

		return $this->Crud->execute($action);
	}

	public function charactersQueue($student_id)
	{
		$this->queueLammy();
	}

	public function paginate($query = null, array $settings = [])
	{
		if(isset($this->viewVars['parent'])) {
			$query->where(['teacher_id' => $this->viewVars['parent']->id]);
		}
		return $query->all();
	}

}
