<?php
$this->Paginator->options(array(
    'update' => '#content',
    'evalScripts' => true
));
?>
<h2><?php
	if ($thread['Thread']['sticky']) {
		echo '[Sticky] ';
	}
	if ($thread['Thread']['locked']) {
		echo '[Locked] ';
	}
	echo h($thread['Thread']['subject']);
?></h2>
<div class="breadcrumbs"><?php echo $this->Html->link(__('Board Index'), array('controller' => 'boards')); ?> &raquo; <?php echo $this->Html->link($thread['Board']['description'], array('controller' => 'boards', 'action' => 'view', $thread['Board']['id'])); ?></div>
<div class="posts">
	<table cellpadding = "0" cellspacing = "0">
	<?php
		$i = 0;
		foreach ($posts as $post): ?>
		<tr>
			<td>
				<h3 id="post-<?php echo $post['Post']['id']; ?>">
					<?php
					echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'profile', $post['User']['id']));
					echo ' &raquo; ';
					echo $this->Time->niceShort($post['Post']['created']);
					if ($post['Post']['mailed'] == 1) {
						echo ' via email';
					}
					?>
				</h3>
				<p><?php echo $this->Text->autoLinkUrls($this->Markdown->parse($post['Post']['text']), array('escape' => false)); ?></p>
			</td>
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
<?php if ($thread['Thread']['locked'] == 0 || $auth['role'] === 'admin'): ?>
<p>
<?php
	echo $this->Js->link(__('Add Reply'),
		array('controller' => 'posts', 'action' => 'add', 'thread' => $thread['Thread']['id']),
		array('class' => 'button icon add', 'id' => 'add-post','before' => $this->Js->get('#posts-form')->effect('fadeIn', array('buffer' => false)) . $this->Js->get('#add-post')->effect('hide', array('buffer' => false)))
	);
?>
</p>
<div id="posts-form" class="posts form" style="display: none">
<?php echo $this->Form->create('Post',array('url' => '/posts/add/thread:' . $thread['Thread']['id']));?>
	<fieldset>
		<legend><?php echo __('Add Reply'); ?></legend>
	<?php
		echo $this->Form->input('text');
	?>
	All messages are formated using <?php echo $this->Html->link('MarkDown','http://daringfireball.net/projects/markdown/syntax'); ?>.
	</fieldset>
<?php
	echo $this->Html->div('button-group',
		$this->Form->button(__('Post'), array('type'=>'submit','class'=>'button primary icon approve'))
		. $this->Js->link(__('Cancel'),
			array('controller' => 'threads', 'action' => 'view', $thread['Thread']['id']),
			array('class' => 'button danger','before' => $this->Js->get('#posts-form')->effect('hide', array('buffer' => false)) . $this->Js->get('#add-post')->effect('fadeIn', array('buffer' => false)))
		)
	);
	echo $this->Form->end();
?>
</div>
<?php endif; ?>

<?php

if ($auth['role'] == 'admin' || $thread['Board']['readonly'] == 0) {
	$page_actions[] = $this->Html->link(__('New Thread', true), array('controller' => 'threads', 'action' => 'add', 'board' => $thread['Board']['id']));
}

if ($auth['role'] == 'admin' || $thread['Thread']['locked'] == 0) {
	$page_actions[] = $this->Html->link(__('New Reply', true), array('controller' => 'posts', 'action' => 'add', 'thread' => $thread['Thread']['id']));
}

$page_actions[] = $this->Html->link(__('Manage My Subscriptions', true), array('controller' => 'users', 'action' => 'subscriptions'));

if ($subscription) {
	echo '<p>You are subscribed to this board, to stop receiving e-mails you can unsubscribe using the ' . $this->Html->link(__('Manage My Subscriptions', true), array('controller' => 'users', 'action' => 'subscriptions')) . ' page.</p>';
}

if ($thread['Thread']['locked'] == 1) {
	$page_admin_actions[] = $this->Form->postLink(__('Unlock Thread'), array('action' => 'unlock', $thread['Thread']['id']), null, __('Are you sure you want to unlock this thread?'));
} else {
	$page_admin_actions[] = $this->Form->postLink(__('Lock Thread'), array('action' => 'lock', $thread['Thread']['id']), null, __('Are you sure you want to lock this thread?'));
}

$page_admin_actions[] =	$this->Form->postLink(__('Delete Thread'), array('action' => 'delete', $thread['Thread']['id']), null, __('Are you sure you want to delete this thread?'));

$this->set(compact('page_actions','page_admin_actions'));
?>
