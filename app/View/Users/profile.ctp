<div class="users view">
<h2><?php  echo __('User');?></h2>
	<dl>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo $this->Html->link($user['User']['email'], 'mailto:' . $user['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Role'); ?></dt>
		<dd>
			<?php
				if ($user['User']['role'] == 'user') {
					echo 'User';
				} elseif ($user['User']['role'] == 'admin') {
					echo 'Administrator';
				} elseif ($user['User']['role'] == 'auditor') {
					echo 'Auditor';
				} else {
					echo h($user['User']['role']);
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Class'); ?></dt>
		<dd>
			<?php
				if ($user['User']['class'] == 'friend') {
					echo 'Friend (Non-Member)';
				} elseif ($user['User']['class'] == 'supporting') {
					echo 'Supporting Member';
				} elseif ($user['User']['class'] == 'regular') {
					echo 'Regular/Voting Member';
				} else {
					echo h($user['User']['class']);
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($user['User']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Active'); ?></dt>
		<dd>
			<?php echo ($user['User']['active'] ? 'Yes' : 'No'); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php
$page_actions = array(
	$this->Html->link(__('Edit My Profile', true), array('controller' => 'users', 'action' => 'myprofile')),
	$this->Html->link(__('Change My Password', true), array('controller' => 'users', 'action' => 'changepass')),
	$this->Html->link(__('Change My E-Mail', true), array('controller' => 'users', 'action' => 'changemail')),
	$this->Html->link(__('Change My Subscriptions', true), array('controller' => 'lists', 'action' => 'index')),
);

if (in_array($auth['class'], array('supporting', 'regular'))) {
	$page_actions[] = $this->Html->link(__('Show Member List', true), array('controller' => 'users', 'action' => 'index'));
}

$page_admin_actions = array(
	$this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')),
	$this->Html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])),
	$this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete %s?', $user['User']['username']))
);
$this->set(compact('page_actions','page_admin_actions'));
?>
