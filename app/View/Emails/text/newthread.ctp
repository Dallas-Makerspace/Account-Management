<?php
	App::uses('HtmlHelper', 'Utility');
	$url = HtmlHelper::url(array('controller' => 'threads', 'action' => 'view', $thread['Thread']['id']));
?>
Hello <?php echo $user['User']['first_name']; ?>,

You have successfully created a new thread with the subject:
<?php echo $thread['Thread']['subject']; ?>

You can access this thread at:
https://dallasmakerspace.org/account<?php echo $url; ?>

Thank you,
Dallas Makerspace Admin
