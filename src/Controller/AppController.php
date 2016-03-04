<?php
namespace App\Controller;

use App\Model\Entity\JsonEntity;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\ResultSet;
use Crud\Error\Exception\ValidationException;

class AppController
	extends Controller
{

	use \Crud\Controller\ControllerTrait;

	public $helpers = [ 'Date' ];

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels'] ]
		);
		$this->loadComponent('Auth',
			[ 'storage' => 'Session'
			, 'authenticate' =>
				[ 'Form' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					]
				, 'ADmad/JwtAuth.Jwt' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					, 'parameter' => 'token'
					, 'queryDatasource' => true
				]	]
			, 'authorize' => ['Controller']
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/api/login'
			, 'logoutRedirect' => '/'
			]
		);

		if($this->request->is('json')) {
			$this->viewBuilder()->className('Api');
			if(!$this->request->is('post'))
				$this->request->data = $this->request->input('json_decode', 1);

			$error = json_last_error();
			if($error != JSON_ERROR_NONE) {
				$msg = sprintf("Failed to parse json, error: %s '%s'"
								, $error, json_last_error_msg());
				throw new BadRequestException($msg);
			}

			$this->Crud->on('afterSave', function(Event $event) {
				if(!$event->subject->success)
					throw new ValidationException($event->subject->entity);

				if($event->subject->success) {
					$code = $event->subject->created ? 302 : 303;
					$this->response->location($this->request->here);
					return $this->response;
				}
			});
			$this->Crud->on('afterDelete', function(Event $event) {
				if(!$event->subject->success)
					throw new BadRequestException('Failed to delete');

				$this->response->statusCode(204);
				return $this->response;
			});
			$this->Crud->on('beforeRedirect', function(Event $event) {
				if(method_exists($this->Crud->action(), 'publishViewVar'))
					$this->Crud->action()->publishViewVar($event);
				return $this->render();
			});
        }
	}

	public function paginate($query = null)
	{
		if($this->request->is('json'))
			return $query->all();

		return parent::paginate($query);
	}

	public function isAuthorized($user)
	{
		return $this->hasAuthSuper();
	}

	protected function argsOrder($from, $to, $array)
	{
		$lookup = array_flip(str_split($from));
		$output = [];
		foreach(str_split($to) as $key) {
			$output[] = $array[$lookup[$key]];
		}
		return $output;
	}
	protected function argsCharId($args)
	{
		$this->loadModel('Characters');
		if(count($args) >= 2) {
			$plin = array_shift($args);
			$chin = array_shift($args);
			$char = $this->Characters->plinChin($plin, $chin)->id;
			array_unshift($args, $char);
		}
		return $args;
	}

	protected function hasAuthUser($id = null)
	{
		if($id === null)
			$id = (int)$this->request->param('plin');
		return $this->Auth->user('id') == $id;
	}
	protected function hasAuthRole($role)
	{
		return $this->Auth->user('role') == $role;
	}
	protected function hasAuthSuper()
	{
		return $this->hasAuthRole('Super');
	}
	protected function hasAuthInfobalie()
	{
		return $this->hasAuthRole('Infobalie') || $this->hasAuthSuper();
	}
	protected function hasAuthReferee()
	{
		return $this->hasAuthRole('Referee') || $this->hasAuthInfobalie();
	}
	protected function hasAuthPlayer()
	{
		return $this->hasAuthRole('Participant') || $this->hasAuthReferee();
	}
}
