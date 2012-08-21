<?php
App::uses('AppController', 'Controller');
/**
 * Boards Controller
 *
 * @property Board $Board
 */
class BoardsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->helpers[] = 'Time';
		$this->Auth->allow('index','view');
	}

	public function isAuthorized($user) {
		if (parent::isAuthorized($user)) {
			return true;
		}

		// Allow all users to access index and view
		if (in_array($this->action, array('index', 'view'))) {
			return true;
		}

		return false;
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Board->recursive = 0;
		if (in_array($this->Auth->user('class'), array('supporting','regular')) || in_array($this->Auth->user('role'), array('admin','auditor'))) {
			$boards = $this->Board->find('all');
		} else {
			$boards = $this->Board->findAllByPublic(1);
		}

		foreach ($boards as &$board) {
			$board['LastThread'] = $this->Board->lastThread($board['Board']['id']);
			$board['LastPost'] = $this->Board->Thread->lastPost($board['LastThread']['Thread']['id']);
		}

		$this->set('boards', $boards);
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Board->id = $id;
		if (!$this->Board->exists()) {
			throw new NotFoundException(__('Invalid board'));
		}

		$this->Board->Behaviors->attach('Containable');
		//$this->Board->contain(array('Thread' => 'User'));

		$board = $this->Board->read(null, $id);

		if (
			$board['Board']['public'] == 0 &&
			!in_array($this->Auth->user('role'), array('admin', 'auditor')) &&
			!in_array($this->Auth->user('class'), array('supporting', 'regular'))
		) {
			$this->Auth->flash('Access to that board is restricted');
			$this->redirect(array('action' => 'index'));
		}

		$this->Board->Thread->Behaviors->attach('Containable');
		$this->paginate = array(
			'conditions' => array('Thread.board_id' => $board['Board']['id']),
			'contain' => array('User'),
			'limit' => 20
		);
		$threads = $this->paginate('Thread');

		foreach ($threads as &$thread) {
			$thread['Thread']['LastPost'] = $this->Board->Thread->lastPost($thread['Thread']['id']);
		}

		$subscription = $this->Board->isSubscribed($id);

		$this->set(compact('board','threads','subscription'));
	}

/* Admin Role Functions */

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Board->create();
			if ($this->Board->save($this->request->data)) {
				$this->Session->setFlash(__('The board has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The board could not be saved. Please, try again.'));
			}
		}
		$users = $this->Board->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Board->id = $id;
		if (!$this->Board->exists()) {
			throw new NotFoundException(__('Invalid board'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Board->save($this->request->data)) {
				$this->Session->setFlash(__('The board has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The board could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Board->read(null, $id);
		}
		$users = $this->Board->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Board->id = $id;
		if (!$this->Board->exists()) {
			throw new NotFoundException(__('Invalid board'));
		}
		if ($this->Board->delete()) {
			$this->Session->setFlash(__('Board deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Board was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
