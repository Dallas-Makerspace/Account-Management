<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
<?php $url2 = $this->Html->url(array('controller' => 'users', 'action' => 'lostpass'), true); ?>
Hello <?php echo $user['User']['first_name']; ?>,

Welcome to the Dallas Makerspace website! Your account is now active and ready
to be used.

You can login to your new account at:
<?php echo $url; ?>

If you forgot, your account was created with the username:
<?php echo h($user['User']['username']); ?>

If you lose your password you can reset it by going to:
<?php echo $url2; ?>

Thank you,
Dallas Makerspace Website Admin
