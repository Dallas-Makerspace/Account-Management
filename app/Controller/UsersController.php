<?php
// app/Controller/UsersController.php
class UsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		// Allow register, login and logout for everyone
		$this->Auth->allow('register', 'verify', 'login', 'logout', 'lostuser', 'lostpass', 'verifylostpass');
	}

	public function isAuthorized($user) {
		if (parent::isAuthorized($user)) {
			return true;
		}

		// Allow all users to access their profile
		if (in_array($this->action, array('dashboard', 'myprofile', 'changemail', 'changepass'))) {
			return true;
		}		

		if ( //Allow supporting and regular classes to access the index and profile actions
			in_array($this->action, array('index', 'profile'))
			&& isset($user['class'])
			&& in_array($user['class'], array('supporting','regular'))) {
			return true;
		}

		return false;
	}

/* Guest Functions */

	public function login() {
		if ($this->Auth->login()) {
			$this->redirect($this->Auth->redirect());
		} elseif($this->request->is('post') || $this->request->is('put')) {
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
		unset($this->request->data['User']['password']);
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function register() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__('You already have a user account'));
			$this->redirect($this->Auth->redirect());
		}
		if ($this->request->is('post')) {
			$this->User->create();
			$this->request->data['User']['role'] = 'user';
			$this->request->data['User']['class'] = 'friend';
			$this->request->data['User']['active'] = 0;
			$this->request->data['User']['verification_code'] = String::uuid();
			$user = $this->request->data;
			if ($this->User->save($this->request->data)) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('verification')
					->emailFormat('both')
					->viewVars(array('user' => $user))
					->to($user['User']['email'])
					->subject('Account Verification')
					->send();

				$this->Session->setFlash(__('Your account has been created, please check your e-mail to verify the account'));
				$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

	public function verify($uuid = false) {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__('You already have a user account'));
			$this->redirect($this->Auth->redirect());
		}

		$user = $this->User->findByVerificationCode($uuid);

		if (!$user) {
			$this->Session->setFlash(__('Invalid verification code'));
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		if ($user['User']['active'] == 1) {
			$this->Session->setFlash(__('This account has already been activated'));
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		App::uses('CakeTime', 'Utility');
		if (CakeTime::wasWithinLast('2 days', $user['User']['modified'])) {
			unset($user['User']['password']);
			$user['User']['verification_code'] = null;
			$user['User']['active'] = 1;
			if ($this->User->save($user)) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('welcome')
					->emailFormat('both')
					->viewVars(array('user' => $user))
					->to($user['User']['email'])
					->subject('Welcome')
					->send();

				$this->Session->setFlash(__('Your account is now active, please login.'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__('An error occurred. Please, try again later.'));
			}
		} else {
			unset($user['User']['password']);
			$user['User']['verification_code'] = String::uuid();
			if ($this->User->save($user,false)) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('verification')
					->emailFormat('both')
					->viewVars(array('user' => $user))
					->to($user['User']['email'])
					->subject('Account Verification')
					->send();

				$this->Session->setFlash(__('Verification code has expired, a new one has been sent'));
			} else {
				$this->Session->setFlash(__('An error occurred. Please, try again later.'));
			}
		}
		$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
	}

	public function lostuser() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__('Really? You forgot your username?'));
			$this->redirect($this->Auth->redirect());
		}

		if ($this->request->is('post')) {
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if ($user) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('lostuser')
					->emailFormat('both')
					->viewVars(array('user' => $user))
					->to($user['User']['email'])
					->subject('Your Account Info')
					->send();

				$this->Session->setFlash(__('An e-mail has been sent to you with your username'));
				$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
			} else {
				$this->Session->setFlash(__('No account found with that e-mail address'));
			}
		}
	}

	public function lostpass() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__('Password can not be reset while logged in'));
			$this->redirect($this->Auth->redirect());
		}

		if ($this->request->is('post')) {
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if ($user) {
				if ($user['User']['active'] == 0) {
					$this->Session->setFlash(__('This account has not been activated yet'));
					$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				}

				unset($user['User']['password']);
				$user['User']['verification_code'] = String::uuid();
				if ($this->User->save($user,false)) {
					App::uses('CakeEmail', 'Network/Email');
					$email = new CakeEmail('default');
					$email->template('lostpass')
						->emailFormat('both')
						->viewVars(array('user' => $user))
						->to($user['User']['email'])
						->subject('Password Reset Request')
						->send();

					$this->Session->setFlash(__('Password reset verification sent, please check your email'));
					$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				} else {
					$this->Session->setFlash(__('An error occurred. Please, try again later.'));
				}
			} else {
				$this->Session->setFlash(__('No account found with that e-mail address'));
			}
		}
	}

	public function verifylostpass($uuid = false) {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__('Password can not be reset while logged in'));
			$this->redirect($this->Auth->redirect());
		}

		$user = $this->User->findByVerificationCode($uuid);

		if (!$user) {
			$this->Session->setFlash(__('Invalid verification code'));
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		if ($user['User']['active'] == 0) {
			$this->Session->setFlash(__('This account has not been activated yet'));
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		App::uses('CakeTime', 'Utility');
		if (CakeTime::wasWithinLast('2 days', $user['User']['modified'])) {
			$password = $this->__randompassword();
			$user['User']['password'] = $password;
			$user['User']['verification_code'] = null;
			if ($this->User->save($user)) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('lostpasscomplete')
					->emailFormat('both')
					->viewVars(array('user' => $user, 'password' => $password))
					->to($user['User']['email'])
					->subject('Password Reset Complete')
					->send();

				$this->Session->setFlash(__('Your password has been reset, please check your email.'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__('An error occurred. Please, try again later.'));
			}
		} else {
			unset($user['User']['password']);
			$user['User']['verification_code'] = String::uuid();
			if ($this->User->save($user,false)) {
				App::uses('CakeEmail', 'Network/Email');
				$email = new CakeEmail('default');
				$email->template('lostpass')
					->emailFormat('both')
					->viewVars(array('user' => $user))
					->to($user['User']['email'])
					->subject('Password Reset Request')
					->send();

				$this->Session->setFlash(__('Verification code has expired, a new one has been sent'));
			} else {
				$this->Session->setFlash(__('An error occurred. Please, try again later.'));
			}
		}
		$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
	}

/* Friend Class And Higher Functions */
	public function dashboard() {
		$this->set('user', $this->Auth->user());
	}

	public function myprofile() {
		$this->User->id = $this->Auth->user('id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your profile has been saved'));
				$this->redirect(array('action' => 'dashboard'));
			} else {
				$this->Session->setFlash(__('Your profile could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $this->User->id);
		}
	}

	public function changemail() {
		// TODO: Have the user verify the new e-mail address before making the change
		$this->User->id = $this->Auth->user('id');
		$user = $this->User->read(null, $this->User->id);
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (AuthComponent::password($this->request->data['User']['password']) != $user['User']['password']) {
				$this->Session->setFlash(__('The password you entered was incorrect. Please, try again.'));
			} else {
				if ($this->User->save($this->request->data)) {
					App::uses('CakeEmail', 'Network/Email');
					$email = new CakeEmail('default');
					$email->template('changemail')
						->emailFormat('both')
						->viewVars(array('user' => $user, 'new_email' => $this->request->data['User']['email']))
						->to($user['User']['email'])
						->cc($this->request->data['User']['email'])
						->subject('Account Change')
						->send();

					$this->Session->setFlash(__('Your email address has been updated'));
					$this->redirect(array('action' => 'dashboard'));
				} else {
					$this->Session->setFlash(__('Your profile could not be saved. Please, try again.'));
				}
			}
		}
		unset($this->request->data['User']['password']);
		$this->set('user', $user);
	}

	public function changepass() {
		$this->User->id = $this->Auth->user('id');
		$user = $this->User->read(null, $this->User->id);
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (AuthComponent::password($this->request->data['User']['current_password']) != $user['User']['password']) {
				$this->Session->setFlash(__('The password you entered was incorrect. Please, try again.'));
			} elseif ($this->request->data['User']['new_password'] != $this->request->data['User']['verify_password']) {
				$this->Session->setFlash(__('Your passwords did not match. Please, try again.'));
			} else {
				$this->request->data['User']['password'] = $this->request->data['User']['new_password'];
				if ($this->User->save($this->request->data)) {
					App::uses('CakeEmail', 'Network/Email');
					$email = new CakeEmail('default');
					$email->template('changepass')
						->emailFormat('both')
						->viewVars(array('user' => $user))
						->to($user['User']['email'])
						->subject('Account Change')
						->send();

					$this->Session->setFlash(__('Your password has been changed'));
					$this->redirect(array('action' => 'dashboard'));
				} else {
					$this->Session->setFlash(__('Your profile could not be saved. Please, try again.'));
				}
			}
		}
		$this->request->data = null;
		$this->set('user', $user);
	}

/* Member Class Only Functions */

	public function index() {
		$this->User->recursive = 0;
		// Show admins and auditors all users, even inactive ones
		if (!in_array($this->Auth->user('role'), array('admin', 'auditor'))) {
			$this->paginate = array(
				'conditions' => array('User.active' => 1),
			);
		}
		$this->set('users', $this->paginate());
	}

	public function profile($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/* Admin Role Functions */

	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['User']['password'])) {
				unset($this->request->data['User']['password']);
			}
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
	}

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	private function __randompassword() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 
		while ($i <= 7) { 
			$num = rand() % 33; 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		} 
		return $pass;
	}
}
