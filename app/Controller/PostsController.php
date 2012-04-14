<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 */
class PostsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->helpers[] = 'Time';
	}

	public function isAuthorized($user) {
		if (parent::isAuthorized($user)) {
			return true;
		}

		// Allow all users to access view
		if (in_array($this->action, array('add'))) {
			return true;
		}

		return false;
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Post->Thread->Behaviors->attach('Containable');
		$this->Post->Thread->contain(array('Board'));

		$thread = $this->Post->Thread->findById($this->passedArgs['thread']);

		if (empty($thread)) {
			throw new NotFoundException(__('Invalid thread'));
		}

		if (
			$thread['Board']['public'] == 0 &&
			!in_array($this->Auth->user('role'), array('admin', 'auditor')) &&
			!in_array($this->Auth->user('class'), array('supporting', 'regular'))
		) {
			$this->Auth->flash('Access to that board is restricted');
			$this->redirect(array('controler' => 'boards', 'action' => 'index'));
		}

		if ($this->request->is('post')) {
			$this->request->data['Post']['user_id'] = $this->Auth->user('id');
			$this->request->data['Post']['mailed'] = 0;
			$this->request->data['Post']['thread_id'] = $thread['Thread']['id'];
			$this->Post->create();
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The post has been saved'));
				$this->redirect(array('controller' => 'threads', 'action' => 'view', $thread['Thread']['id']));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
			}
		}
	}
}
