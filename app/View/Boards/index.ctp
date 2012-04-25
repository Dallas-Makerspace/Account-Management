<div class="boards index">
	<h2><?php echo __('Boards');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo __('Name');?></th>
			<th><?php echo __('Threads');?></th>
			<th><?php echo __('Last Post'); ?></td>
	</tr>
	<?php
	foreach ($boards as $board): ?>
	<tr>
		<td><?php echo $this->Html->link("[{$board['Board']['name']}] {$board['Board']['description']}", array('action' => 'view', $board['Board']['id'])); ?>&nbsp;</td>
		<td><?php echo h($board['Board']['thread_count']); ?>&nbsp;</td>
		<?php if ($board['Board']['thread_count'] == 0): ?>
		<td>Never</td>
		<?php else: ?>
		<td>by <?php echo $this->Html->link($board['LastPost']['User']['username'], array('controller' => 'users', 'action' => 'profile', $board['LastPost']['User']['id']));?><br /><?php echo $this->Time->niceShort($board['LastPost']['Post']['created']);?></td>
		<?php endif; ?>
	</tr>
<?php endforeach; ?>
	</table>
</div>
<?php
$page_admin_actions = array(
	$this->Html->link(__('Add Board', true), array('action' => 'add')),
);
$this->set(compact('page_admin_actions'));
?>
