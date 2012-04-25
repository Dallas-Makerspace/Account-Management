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
		. $this->Html->link(__('Cancel'), array('controller' => 'boards', 'action' => 'view', $this->data['Board']['id']), array('class' => 'button danger'))
	);
	echo $this->Form->end();
?>
</div>
<?php
$page_actions = array(
	$this->Html->link(__('New Thread', true), array('controller' => 'threads', 'action' => 'add', 'board' => $this->data['Board']['id'])),
);

$page_admin_actions = array(
	$this->Html->link(__('Add Board', true), array('action' => 'add')),
	$this->Form->postLink(__('Delete Board'), array('action' => 'delete', $this->data['Board']['id']), null, __('Are you sure you want to delete this board?'))
);
$this->set(compact('page_actions','page_admin_actions'));
?>
