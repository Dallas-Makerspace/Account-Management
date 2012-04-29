<div class="users index">
	<h2><?php echo __('Users');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
<?php if (in_array($auth['role'], array('admin', 'auditor'))): ?>
			<th><?php echo $this->Paginator->sort('role');?></th>
			<th><?php echo $this->Paginator->sort('class');?></th>
<?php endif; ?>
			<th><?php echo $this->Paginator->sort('first_name');?></th>
			<th><?php echo $this->Paginator->sort('last_name');?></th>
			<th><?php echo $this->Paginator->sort('phone');?></th>
	</tr>
	<?php
	foreach ($users as $user): ?>
	<?php echo ($user['User']['active'] == 1 ? '<tr>' : '<tr style="background: #F99">'); ?>
		<td><?php echo $this->Html->link($user['User']['username'], array('action' => 'profile', $user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($user['User']['email'], 'mailto:' . $user['User']['email']); ?>&nbsp;</td>
<?php if (in_array($auth['role'], array('admin', 'auditor'))): ?>
		<td><?php echo h($user['User']['role']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['class']); ?>&nbsp;</td>
<?php endif; ?>
		<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
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
