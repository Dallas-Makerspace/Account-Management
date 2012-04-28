<?php
App::uses('AppModel', 'Model');
/**
 * Thread Model
 *
 * @property Board $Board
 * @property User $User
 * @property Post $Post
 */
class Thread extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'subject';
/**
 * Order
 *
 * @var string
 */
	public $order = array('Thread.sticky DESC', 'Thread.updated DESC');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'A subject is required',
				//'allowEmpty' => false,
				//'required' => false,
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
		'Board' => array(
			'className' => 'Board',
			'foreignKey' => 'board_id',
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
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'thread_id',
			'dependent' => true,
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
 * lastPost Method
 *
 * @param string $id
 * @return array
 */
	public function lastPost ($id = null) {
		$this->id = $id;
		if (!$this->exists()) {
			return false;
		}

		return $this->Post->find('first', array(
			'conditions' => array('Post.thread_id' => $id),
			'order' => array('Post.created DESC'),
			'limit' => 1,
		));
	}

/**
 * lock method
 *
 * @param string $id
 * @return array
 */
	public function lock ($id = null) {
		$this->id = $id;
		if (!$this->exists()) {
			return false;
		}

		$this->recursive = -1;
		$this->read();

		$this->data['Thread']['locked'] = true;
		if ($this->save()) {
			return true;
		} else {
			return false;
		}
	}

/**
 * unlock method
 *
 * @param string $id
 * @return array
 */
	public function unlock ($id = null) {
		$this->id = $id;
		if (!$this->exists()) {
			return false;
		}

		$this->recursive = -1;
		$this->read();

		$this->data['Thread']['locked'] = false;
		if ($this->save()) {
			return true;
		} else {
			return false;
		}
	}
}
