<h2><?php  echo h($thread['Thread']['subject']);?></h2>
<div class="breadcrumbs"><?php echo $this->Html->link(__('Board Index'), array('controller' => 'boards')); ?> &raquo; <?php echo $this->Html->link($thread['Board']['description'], array('controller' => 'boards', 'action' => 'view', $thread['Board']['id'])); ?></div>
<div class="posts">
	<table cellpadding = "0" cellspacing = "0">
	<?php
		$i = 0;
		foreach ($thread['Post'] as $post): ?>
		<tr>
			<td>
				<h3 id="post-<?php echo $post['id']; ?>">
					<?php
					echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'profile', $post['User']['id']));
					echo ' &raquo; ';
					echo $this->Time->niceShort($post['created']);
					if ($post['mailed'] == 1) {
						echo ' via email';
					}
					?>
				</h3>
				<p><?php echo $this->Text->autoLinkUrls($this->Markdown->parse($post['text']), array('escape' => false)); ?></p>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>
<?php
	echo $this->Js->link(__('Add Reply'),
		array('controller' => 'posts', 'action' => 'add', 'thread' => $thread['Thread']['id']),
		array('class' => 'button icon add', 'id' => 'add-post','before' => $this->Js->get('#posts-form')->effect('fadeIn', array('buffer' => false)) . $this->Js->get('#add-post')->effect('hide', array('buffer' => false)))
	);
?>
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
<?php

if ($auth['role'] == 'admin' || $thread['Board']['readonly'] == 0) {
	$page_actions = array(
		$this->Html->link(__('New Thread', true), array('controller' => 'threads', 'action' => 'add', 'board' => $thread['Board']['id'])),
		$this->Html->link(__('New Reply', true), array('controller' => 'posts', 'action' => 'add', 'thread' => $thread['Thread']['id'])),
	);
}

$page_admin_actions = array(
	
);
$this->set(compact('page_actions','page_admin_actions'));
?>
