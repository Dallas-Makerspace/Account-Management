<?php
App::uses('AppModel', 'Model');
App::uses('Sanitize', 'Utility');

/**
 * Post Model
 *
 * @property Thread $Thread
 * @property User $User
 */
class Post extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'text';
/**
 * Order
 *
 * @var string
 */
	public $order = 'Post.created ASC';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'mailed' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'text' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Your message needs to have some text.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Thread' => array(
			'className' => 'Thread',
			'foreignKey' => 'thread_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Email' => array(
			'className' => 'Email',
			'foreignKey' => 'post_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * beforeSave callback
 *
 */
	public function beforeSave() {
		$this->Thread->recursive = -1;
		$thread = $this->Thread->findById($this->data['Post']['thread_id']);

		// Only admins can post to a locked thread
		if ($thread['Thread']['locked'] == 1 && $auth['role'] !== 'admin') {
			return false;
		} else {
			return true;
		}
	}
/**
 * afterSave callback
 *
 */
	public function afterSave() {
		$this->Thread->recursive = -1;
		$thread = $this->Thread->findById($this->data['Post']['thread_id']);
		$thread['Thread']['updated'] = null;
		$this->Thread->save($thread);

		$subscribers = $this->User->UsersBoard->find('all', array('conditions' => array('UsersBoard.board_id' => $thread['Thread']['board_id'])));

		foreach ($subscribers as $subscriber) {
			// Don't send an e-mail to the person who made this post
			if ($subscriber['UsersBoard']['user_id'] != $this->data['Post']['user_id']) {
				$emails[] = array('Email' => array(
					'post_id' => $this->data['Post']['id'],
					'user_id' => $subscriber['UsersBoard']['user_id'],
				));
			}
		}

		$this->Email->saveAll($emails);

		return true;
	}
}
