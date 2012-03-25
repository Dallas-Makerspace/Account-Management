<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Reset Password'); ?></legend>
	<?php
		echo $this->Form->input('email', array('label' => 'E-Mail Address'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
