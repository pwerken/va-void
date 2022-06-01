<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\AuthState;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Inflector;
use Crud\Error\Exception\ValidationException;
use Crud\Controller\ControllerTrait;

class AppController
	extends Controller
{
	use ControllerTrait;

	protected $searchFields = [ ];
	protected $parent = null;

	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels']
			]);
		$this->loadComponent('Auth',
			[ 'storage' => 'Memory'
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
					, 'unauthenticatedException' => '\Cake\Http\Exception\ForbiddenException'
				]	]
			, 'authorize' => ['Controller']
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/auth/login'
			, 'logoutRedirect' => '/'
			]);

		$this->viewBuilder()->setClassName('Api');

		$arr = ['exceptionRenderer' => 'App\Error\ApiExceptionRenderer']
			+ Configure::read('Error');
		(new ErrorHandler($arr))->register();

		$this->response->compress();
	}

	public function implementedEvents(): array
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeHandle']   = 'CrudBeforeHandle';
		$events['Crud.afterSave']      = 'CrudAfterSave';
		$events['Crud.afterDelete']    = 'CrudAfterDelete';
		$events['Crud.beforeRedirect'] = 'CrudBeforeRedirect';
		return $events;
	}

	public function isAuthorized($user): bool
	{
		AuthState::initialize($this->Auth, $this->wantAuthUser());

		$auths = $this->Crud->action()->getConfig('auth') ?: ['super'];
		return AuthState::hasAuth($auths);
	}

	protected function wantAuthUser(): ?int
	{
		$plin = $this->request->getParam('plin');
		if (is_null($plin))
			return null;

		return (int)$plin;
	}

	public function paginate($query = null, array $settings = [])
	{
		if(isset($this->parent)) {
			# this nested index listing, limit query to the parent object
			$classname = get_class($this->parent);
			$pos = strrpos($classname, '\\');
			$key = strtolower(substr($classname, $pos + 1)).'_id';
			$query->where([$key => $this->parent->id]);
		}
		return $query->all();
	}

	public function parseRequestInput($input)
	{
		if(empty($input)) {
			return [];
		}

		$json = json_decode($input, true);

		$error = json_last_error();
		if($error != JSON_ERROR_NONE) {
			$msg = sprintf("Failed to parse json, error: %s '%s'"
							, $error, json_last_error_msg());
			throw new BadRequestException($msg);
		}
		return $json;
	}

	public function CrudBeforeHandle(Event $event)
	{
		$action = $this->request->getParam('action');

		if(strcmp(substr($action, -3, 3), 'add') == 0
		|| strcmp(substr($action, -4, 4), 'edit') == 0) {
			# remove stuff that's not in the db
			foreach($this->request->getData() as $key => $value) {
				if(!$this->loadModel()->hasField($key))
					$this->request = $this->request->withoutData($key);
			}
			# these can never be set through the rest-api
			$this->request = $this->request->withoutData('created');
			$this->request = $this->request->withoutData('creator_id');
			$this->request = $this->request->withoutData('modified');
			$this->request = $this->request->withoutData('modifier_id');
		}
		if(strcmp(substr($action, -5, 5), 'Index') == 0) {
			# this nested index listing, find the parent object
			$model = ucfirst(substr($action, 0, -5));
			$parent = $this->loadModel($model)->get($event->getSubject()->args[0]);
			$this->parent = $parent;
		}
	}
	public function CrudAfterSave(Event $event)
	{
		$subject = $event->getSubject();
		if(!$subject->success)
			throw new ValidationException($subject->entity);

		if(!$subject->created) {
			$action = 'view';
			$oldAction = $this->request->getParam('action');
			if(strcmp(substr($oldAction, -4, 4), 'Edit') == 0) {
				$action = substr($oldAction, 0, -4) . 'View';
			}
			return $this->Crud->execute($action);
		}

		$location = $subject->entity->refresh()->getUrl();
		$this->response = $this->response->withStatus(201);
		$this->response = $this->response->withLocation($location);
		return $this->response;
	}
	public function CrudAfterDelete(Event $event)
	{
		$subject = $event->getSubject();
		if(!$subject->success)
			throw new ValidationException($subject->entity);

		$this->response = $this->response->withStatus(204);
		return $this->response;
	}
	public function CrudBeforeRedirect(Event $event)
	{
		if(method_exists($this->Crud->action(), 'publishViewVar'))
			$this->Crud->action()->publishViewVar($event);
		return $this->render();
	}

	protected function mapMethod($action, $auth = [], $contain = false)
	{
		$className = ucfirst($action);
		$className = preg_replace('/^.*([A-Z][a-z]+)$/', 'Crud.\\1', $className);

		if(is_string($auth))
			$auth = [$auth];
		if(empty($auth))
			$auth = ['super'];

		$config = compact('className','auth');
		if($contain)
			$config['findMethod'] = 'withContain';

		$this->Crud->mapAction($action, $config);
	}

	protected function setResponseModified()
	{
		$model = $this->modelClass;
		$query = $this->$model->find();
		$query->select(['last' => $query->func()->max("$model.modified")]);
		$modified = $this->doRawQuery($query);
		if($modified[0][0] == null) {
			$modified[0][0] = '1970-01-01 12:00:00';
		}
		$this->response = $this->response->withModified($modified[0][0]);

		return $this->response->checkNotModified($this->request);
	}

	protected function doRawQuery($query)
	{
		$q = $this->request->getQuery('q');
		if(is_null($q))
			$q = '';
		else
			$q = trim();

		$orWhere = [];
		$params = [];
		$values = explode(' ', $q);
		foreach($this->searchFields as $field) {
			if(strlen($q) == 0)
				break;

			$andWhere = [];
			foreach($values as $val) {
				$andWhere[] = "$field LIKE ?";
				$params[] = "%$val%";
			}
			$orWhere[] = function($exp) use ($andWhere) {
				return $exp->and_($andWhere);
			};
		}
		if(!empty($orWhere)) {
			$query->andWhere(function($exp) use ($orWhere) {
				return $exp->or_($orWhere);
			});
		}

		$conn = ConnectionManager::get('default');
		return $conn->execute($query->sql(), $params)->fetchAll();
	}
	protected function doRawIndex($query, $class, $url, $id = 'id')
	{
		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$content[] =
				[ 'class' => $class
				, 'url'   => $url . $row[0]
				, $id     => (int)$row[0]
				, 'name'  => $row[1]
				];
		}

		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => rtrim($this->request->getPath(), '/')
			, 'list' => $content
			]);
	}

	protected function dataNameToId($table, $field)
	{
		$name = $this->request->getData($field);
		if(is_null($name)) {
			return null;
		}

		$this->request = $this->request->withoutData($field);
		if(empty($name)) {
			$name = "-";
		}

		$model = $this->loadModel($table);
		$ids = $model->findByName($name)->select('id', true)
					->enableHydration(false)->all();
		if($ids->count() == 0) {
			$this->request = $this->request->withData($field.'_id', -1);
		} else {
			$this->request = $this->request->withData($field.'_id', $ids->first()['id']);
		}
		return $name;
	}

	protected function dataNameToIdAndAddIfMissing($table, $field)
	{
		$name = $this->dataNameToId($table, $field);
		$id = $this->request->getData($field.'_id');
		if($id < 0) {
			$model = $this->loadModel($table);
			$obj = $model->newEntity();
			$obj->name = $name;
			$model->save($obj);
			$this->request = $this->request->withData($field.'_id', $obj->id);
		}
		return $name;
	}
}
