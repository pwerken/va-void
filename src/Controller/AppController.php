<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\JsonEntity;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\ResultSet;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	use \Crud\Controller\ControllerTrait;

	public $helpers = [ 'Date' ];

/**
 * Initialization hook method.
 *
 * Use this method to add common initialization code like loading components.
 *
 * @return void
 */
	public function initialize() {
		parent::initialize();

		$this->loadComponent('Flash');
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Crud.Crud',
			[ 'actions' =>
				[ 'Crud.Index'
				, 'Crud.Add'
				, 'Crud.Edit'
				, 'Crud.View'
				, 'Crud.Delete'
				]
			, 'listeners' =>
				[ 'Crud.RelatedModels'
				]
			]
		);

		array_walk($this->request->data, array($this, 'removeEmptyDate'));
	}

	private function removeEmptyDate(&$value, $key) {
		if(!is_array($value)) return;
		if(count($value) == 3 &&
			isset($value['year'], $value['month'], $value['day']) &&
			strlen(implode($value)) == 0) {
			$value = null;
		} else {
			array_walk($value, array($this, 'removeEmptyDate'));
		}
	}

	public function beforeRender(Event $event) {
		parent::beforeRender($event);

		if($this->request->is('json')) {
			$objName = $this->viewVars['viewVar'];
			$obj = $this->viewVars[$objName];

			if($obj instanceof JsonEntity) {
				$this->set($objName, $obj->jsonFull());
				$this->set('_serialize', $objName);
				return;
			}
			if($obj instanceof ResultSet) {
				$output = [];
				$output['url'] = $this->request->here;
				foreach($obj as $item) {
					$output['list'][] = $item->jsonShort();
				}
				$this->set($objName, $output);
				$this->set('_serialize', $objName);
				return;
			}
		}
	}
}
