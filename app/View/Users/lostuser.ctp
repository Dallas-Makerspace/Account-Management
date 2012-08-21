<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('I Forgot My Username'); ?></legend>
	<?php
		echo $this->Form->input('email', array('label' => 'E-Mail Address'));
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Submit'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('controller' => 'users', 'action' => 'login'), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
