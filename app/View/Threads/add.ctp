<div class="threads form">
<?php echo $this->Form->create('Thread');?>
	<fieldset>
		<legend><?php echo __('Add Thread'); ?></legend>
	<?php
		echo $this->Form->input('subject');
		echo $this->Form->input('Post.text', array('type' => 'textarea', 'div' => array('class' => 'required')));
	?>
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Post'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Html->link(__('Cancel'), array('controller' => 'boards', 'action' => 'view', $this->passedArgs['board']), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
