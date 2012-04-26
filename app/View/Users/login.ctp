<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Please enter your username and password'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Login'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Forgot Username'), array('action' => 'lostuser'), array('class' => 'button'))
		. $this->Html->link(__('Forgot Password'), array('action' => 'lostpass'), array('class' => 'button'))
	);
	echo $this->Form->end();
?>
</div>
