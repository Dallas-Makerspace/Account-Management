<?php
App::uses('AppModel', 'Model');
/**
 * Board Model
 *
 * @property Thread $Thread
 * @property User $User
 */
class Board extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
/**
 * Order
 *
 * @var string
 */
	public $order = 'Board.order ASC';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'unique' => array(
				'rule' => array('unique'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'public' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'readonly' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Thread' => array(
			'className' => 'Thread',
			'foreignKey' => 'board_id',
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
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'users_boards',
			'foreignKey' => 'board_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

/**
 * lastThread Method
 *
 * @param string $id
 * @return array
 */
	public function lastThread ($id = null) {
		$this->id = $id;
		if (!$this->exists()) {
			return false;
		}

		return $this->Thread->find('first', array(
			'recursive' => 0,
			'conditions' => array('Thread.board_id' => $id),
			'order' => array('Thread.created DESC'),
			'limit' => 1,
		));
	}

/**
 * isSubscribed Method
 *
 * @param string $id
 * @return array
 */
	public function isSubscribed ($id = null) {
		$this->id = $id;
		if (!$this->exists()) {
			return false;
		}

		if ($this->UsersBoard->findByUserIdAndBoardId(AuthComponent::user('id'),$id)) {
			return true;
		} else {
			return false;
		}
	}

}
