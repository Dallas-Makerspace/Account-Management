<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('role', array('options' => array('user' => 'User', 'admin' => 'Administrator', 'auditor' => 'Auditor')));
		echo $this->Form->input('class', array('options' => array('friend' => 'Friend', 'supporting' => 'Supporting Member', 'regular' => 'Regular/Voting Member')));
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('phone');
		echo $this->Form->input('active', array('checked' => 'checked'));
		echo $this->Form->input('textonly_email', array('label' => 'Text-only emails'));
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Submit'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>

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
