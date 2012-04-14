<div class="posts form">
<?php echo $this->Form->create('Post');?>
	<fieldset>
		<legend><?php echo __('Add Reply'); ?></legend>
	<?php
		echo $this->Form->input('text');
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Post'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('controller' => 'threads', 'action' => 'view', $this->passedArgs['thread']), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
