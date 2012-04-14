<?php $this->set('title_for_layout', "{$auth['first_name']}'s Dashboard"); ?>

<h2><?php echo $auth['first_name']; ?>'s Dashboard</h2>

<?php debug($user); ?>

<?php
$page_actions = array(
	$this->Html->link(__('Edit My Profile', true), array('controller' => 'users', 'action' => 'myprofile')),
	$this->Html->link(__('Change My Password', true), array('controller' => 'users', 'action' => 'changepass')),
	$this->Html->link(__('Change My E-Mail', true), array('controller' => 'users', 'action' => 'changemail')),
	$this->Html->link(__('Change My Subscriptions', true), array('controller' => 'lists', 'action' => 'index')),
);

$page_admin_actions = array(
	$this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')),
);
$this->set(compact('page_actions','page_admin_actions'));
?>
