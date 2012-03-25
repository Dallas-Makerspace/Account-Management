<?php if(isset($page_actions)): ?>
<h3>Actions</h3>
<ul>
	<?php foreach($page_actions as $action): ?>
		<li><?php echo $action ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
