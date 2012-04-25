<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array('Form', 'Html', 'Session', 'Js');
	public $components = array(
		'Session',
		'RequestHandler',
		'Security',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'users', 'action' => 'dashboard'),
			'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home'),
			'authError' => 'Did you really think you are allowed to see that?',
			'authorize' => array('Controller'),
		),
	);

	function beforeFilter(){
		// Sha1 is the default, but lets use sha254 instead
		Security::setHash('sha256');

		$this->Auth->authenticate = array(
			'Form' => array('scope' => array('User.active' => 1)),
		);

		$this->set('auth', $this->Auth->user());
	}

	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true; //Admin can access every action
		}
		return false; // Default, no access to actions
	}
}
