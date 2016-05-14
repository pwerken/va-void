<?php
namespace App\Controller;

use App\AuthState;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Error\ErrorHandler;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Crud\Error\Exception\ValidationException;

class AppController
	extends Controller
{

	use \Crud\Controller\ControllerTrait;

	public $helpers = [ 'Date' ];

	protected $searchFields = [ ];

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels']
			]);
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
			, 'loginAction' => '/auth/login'
			, 'logoutRedirect' => '/'
			]);

		if(strcmp($this->name, 'Pages') != 0) {
			$this->viewBuilder()->className('Api');

			$arr = ['exceptionRenderer' => 'App\Error\ApiExceptionRenderer']
				+ Configure::read('Error');
			(new ErrorHandler($arr))->register();
		}
		if(!$this->request->is('post'))
			$this->request->data = $this->request->input('json_decode',1) ?: [];

		$error = json_last_error();
		if($error != JSON_ERROR_NONE) {
			$msg = sprintf("Failed to parse json, error: %s '%s'"
							, $error, json_last_error_msg());
			throw new BadRequestException($msg);
		}
	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeHandle']   = 'CrudBeforeHandle';
		$events['Crud.afterSave']      = 'CrudAfterSave';
		$events['Crud.afterDelete']    = 'CrudAfterDelete';
		$events['Crud.beforeRedirect'] = 'CrudBeforeRedirect';
		return $events;
	}

	public function isAuthorized($user)
	{
		AuthState::setAuth($this->Auth, $this->hasAuthUser());

		$auths = $this->Crud->action()->config('auth') ?: ['super'];
		foreach($auths as $role) {
			if(AuthState::hasRole($role))
				return true;
		}
		return false;
	}

	public function paginate($query = null)
	{
		$action = $this->request->action;
		$nested = strcmp(substr($action, -5, 5), 'Index') === 0;
		if(!$nested) {
			foreach(explode(' ', $this->request->query('q')) as $q) {
				foreach($this->searchFields as $field) {
					$ORs["$field LIKE"] = "%$q%";
				}
				if(empty($q) || empty($ORs))
					continue;
				$query->where(["OR" => $ORs]);
			}
		} else if(isset($this->viewVars['parent'])) {
			$key = Inflector::singularize(substr($action, 0, -5)).'_id';
			$query->where([$key => $this->viewVars['parent']->id]);
		}
		return $query->all();
	}

	public function CrudBeforeHandle(Event $event)
	{
		$action = $this->request->action;

		// = (A && B) || C
		$startPlin  = strcmp($this->name, 'Characters') == 0;
		$startPlin &= in_array($action, ['delete', 'edit', 'view']);
		$startPlin |= strcmp(substr($action, 0, 10), 'characters') == 0;

		if($startPlin && isset($event->subject->args[1])) {
			$plin = $event->subject->args[0];
			$chin = $event->subject->args[1];

			$this->loadModel('Characters');
			$char = $this->Characters->plinChin($plin, $chin)->id;

			array_shift($event->subject->args);
			$event->subject->args[0] = $char;

			if(strcmp($action, 'charactersAdd') == 0) {
				$this->request->data('character_id', $char);
			}
		}

		if(strcmp(substr($action, -5, 5), 'Index') == 0) {
			$model = ucfirst(substr($action, 0, -5));
			$parent = TableRegistry::get($model)->get($event->subject->args[0]);
			$this->set('parent', $parent);
		}
	}
	public function CrudAfterSave(Event $event)
	{
		if(!$event->subject->success)
			throw new ValidationException($event->subject->entity);

		if($event->subject->created) {
			$this->response->statusCode(201);
			//FIXME redirect to location of new entity
			return $this->response;
		}

		$action = $this->request->params['action'];
		$action = lcfirst(Inflector::camelize(substr($action, 0, -4)."_view"));
		$this->request->params['_method'] = 'GET';
		$this->request->params['action'] = $action;
		$this->Crud->beforeFilter($event);
		return $this->invokeAction();
	}
	public function CrudAfterDelete(Event $event)
	{
		if(!$event->subject->success)
			throw new BadRequestException('Failed to delete');

		$this->response->statusCode(204);
		return $this->response;
	}
	public function CrudBeforeRedirect(Event $event)
	{
		if(method_exists($this->Crud->action(), 'publishViewVar'))
			$this->Crud->action()->publishViewVar($event);
		return $this->render();
	}

	protected function hasAuthUser($id = null)
	{
		$id = ($id ?: $this->request->param('plin'));
		return ($this->Auth->user('id') == $id);
	}

	protected function mapMethod($action, $auth = [], $contain = false)
	{
		$className = ucfirst($action);
		$className = preg_replace('/.*([A-Z][a-z]+)/', 'Crud.\\1', $className);

		if(is_string($auth))
			$auth = [$auth];
		if(empty($auth))
			$auth = ['super'];

		$config = compact('className','auth');
		if($contain)
			$config['findMethod'] = 'withContain';

		$this->Crud->mapAction($action, $config);
	}

}
