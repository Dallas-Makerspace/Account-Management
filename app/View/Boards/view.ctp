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
		foreach ($board['Thread'] as $thread): ?>
		<tr>
			<td>
				<strong><?php echo $this->Html->link($thread['subject'], array('controller' => 'threads', 'action' => 'view', $thread['id']));?></strong><br />
				by <?php echo $this->Html->link($thread['User']['username'], array('controller' => 'users', 'action' => 'profile', $thread['User']['id']));?> &raquo; <?php echo $this->Time->niceShort($thread['created']);?>
			</td>
			<td><?php echo $thread['post_count'] - 1;?></td>
			<td>by <?php echo $this->Html->link($thread['User']['username'], array('controller' => 'users', 'action' => 'profile', $thread['User']['id']));?><br /><?php echo $this->Time->niceShort($thread['LastPost']['Post']['created']);?></td>
		</tr>
	<?php endforeach; ?>
	</table>
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
	$page_actions = array(
		$this->Html->link(__('New Thread', true), array('controller' => 'threads', 'action' => 'add', 'board' => $board['Board']['id'])),
	);
}

$page_admin_actions = array(
	$this->Html->link(__('Edit Board', true), array('action' => 'edit', $board['Board']['id'])),
);
$this->set(compact('page_actions','page_admin_actions'));
?>
