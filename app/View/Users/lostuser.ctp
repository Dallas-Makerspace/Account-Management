<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('I Forgot My Username'); ?></legend>
	<?php
		echo $this->Form->input('email', array('label' => 'E-Mail Address'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
