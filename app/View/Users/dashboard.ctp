<?php $this->set('title_for_layout', "{$user['User']['first_name']}'s Dashboard"); ?>

<h2><?php echo $user['User']['first_name']; ?>'s Dashboard</h2>

<?php if ($user['Board']): ?>
<p>You are currently subscribed to the following boards:</p>
<ul>
<?php foreach ($user['Board'] as $board): ?>
	<li><?php echo $this->Html->link($board['name'], array('controller' => 'boards', 'view' => $board['id'])); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<!-- TODO: Make this work
<h3>Active Topics</h3>
<p></p>
-->

<?php
$page_actions = array(
	$this->Html->link(__('Edit My Profile', true), array('controller' => 'users', 'action' => 'myprofile')),
	$this->Html->link(__('Change My Password', true), array('controller' => 'users', 'action' => 'changepass')),
	$this->Html->link(__('Change My E-Mail', true), array('controller' => 'users', 'action' => 'changemail')),
	$this->Html->link(__('Manage My Subscriptions', true), array('controller' => 'users', 'action' => 'subscriptions')),
);

if (in_array($auth['class'], array('supporting', 'regular'))) {
	$page_actions[] = $this->Html->link(__('Show Member List', true), array('controller' => 'users', 'action' => 'index'));
}

$page_admin_actions = array(
	$this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')),
);
$this->set(compact('page_actions','page_admin_actions'));
?>
