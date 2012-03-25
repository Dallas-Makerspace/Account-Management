<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
<?php $url2 = $this->Html->url(array('controller' => 'users', 'action' => 'lostpass'), true); ?>
Hello <?php echo $user['User']['first_name']; ?>,

You are receiving this e-mail because you have lost or forgotten the username
for your account.

Your username is:
<?php echo $user['User']['username']; ?>

You can login at:
<?php echo $url; ?>

To reset your password, please visit:
<?php echo $url2; ?>

Thank you,
Dallas Makerspace Website Admin
