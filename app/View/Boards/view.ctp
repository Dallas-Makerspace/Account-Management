<?php
$this->Paginator->options(array(
    'update' => '#content',
    'evalScripts' => true
));
?>
<h2><?php  echo h("[{$board['Board']['name']}] {$board['Board']['description']}");?></h2>
<div class="breadcrumbs"><?php echo $this->Html->link(__('Board Index'), array('action' => 'index')); ?> &raquo; <?php echo $this->Html->link($board['Board']['description'], array('controller' => 'boards', 'action' => 'view', $board['Board']['id'])); ?></div>
<div class="related">
	<?php if (!empty($board['Thread'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Topics'); ?></th>
		<th><?php echo __('Replies'); ?></th>
		<th><?php echo __('Last Post'); ?></td>
	</tr>
	<?php
		$i = 0;
		foreach ($threads as $thread): ?>
		<tr>
			<td>
				<strong><?php
				if ($thread['Thread']['sticky']) {
					echo '<span class="sticky"></span>';
				}
				if ($thread['Thread']['locked']) {
					echo '<span class="locked"></span>';
				}
				echo $this->Html->link($thread['Thread']['subject'], array('controller' => 'threads', 'action' => 'view', $thread['Thread']['id']));
				?></strong><br />
				by <?php echo $this->Html->link($thread['User']['username'], array('controller' => 'users', 'action' => 'profile', $thread['User']['id']));?> &raquo; <?php echo $this->Time->niceShort($thread['Thread']['created']);?>
			</td>
			<td><?php echo $thread['Thread']['post_count'] - 1;?></td>
			<td>by <?php echo $this->Html->link($thread['User']['username'], array('controller' => 'users', 'action' => 'profile', $thread['User']['id']));?><br /><?php echo $this->Time->niceShort($thread['Thread']['LastPost']['Post']['created']);?></td>
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
	<?php else: ?>
	<p>There are no threads for this board.</p>
	<?php endif; ?>
</div>

<?php

if (
	($auth['role'] == 'admin') ||
	($board['Board']['public'] == 1 && $board['Board']['readonly'] == 0) ||
	($board['Board']['public'] == 0 && $board['Board']['readonly'] == 0 && in_array($auth['class'], array('supporting','regular')))
) {
	$page_actions[] = $this->Html->link(__('New Thread', true), array('controller' => 'threads', 'action' => 'add', 'board' => $board['Board']['id']));
}

$page_actions[] = $this->Html->link(__('Manage My Subscriptions', true), array('controller' => 'users', 'action' => 'subscriptions'));

if ($subscription) {
	echo '<p>You are subscribed to this board, to stop receiving e-mails you can unsubscribe using the ' . $this->Html->link(__('Manage My Subscriptions', true), array('controller' => 'users', 'action' => 'subscriptions')) . ' page.</p>';
}

$page_admin_actions = array(
	$this->Html->link(__('Add Board', true), array('action' => 'add')),
	$this->Html->link(__('Edit Board', true), array('action' => 'edit', $board['Board']['id'])),
	$this->Form->postLink(__('Delete Board'), array('action' => 'delete', $board['Board']['id']), null, __('Are you sure you want to delete this board?'))
);
$this->set(compact('page_actions','page_admin_actions'));
?>
