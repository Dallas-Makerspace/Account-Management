<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Manage Subscriptions'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('Board',array('type' => 'select', 'multiple' => 'checkbox', 'label' => ''));
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Submit'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('action' => 'dashboard'), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>

<?php
$page_actions = array(
	$this->Html->link(__('Edit My Profile', true), array('controller' => 'users', 'action' => 'myprofile')),
	$this->Html->link(__('Change My Password', true), array('controller' => 'users', 'action' => 'changepass')),
	$this->Html->link(__('Change My E-Mail', true), array('controller' => 'users', 'action' => 'changemail')),
);

if (in_array($auth['class'], array('supporting', 'regular'))) {
	$page_actions[] = $this->Html->link(__('Show Member List', true), array('controller' => 'users', 'action' => 'index'));
}

$page_admin_actions = array(
	$this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')),
);
$this->set(compact('page_actions','page_admin_actions'));
?>
