<div class="boards form">
<?php echo $this->Form->create('Board');?>
	<fieldset>
		<legend><?php echo __('Edit Board'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('public');
		echo $this->Form->input('readonly');
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Save'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('controller' => 'boards', 'action' => 'index'), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
