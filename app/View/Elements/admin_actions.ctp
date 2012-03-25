<?php if(isset($page_admin_actions)): ?>
<h3>Admin Actions</h3>
<ul>
	<?php foreach($page_admin_actions as $action): ?>
		<li><?php echo $action ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
