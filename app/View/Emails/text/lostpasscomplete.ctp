<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
Hello <?php echo $user['User']['first_name']; ?>,

Your password has been reset successfully, your new login information is:
Username: <?php echo $user['User']['username']; ?>
Password: <?php echo $password; ?>

We recommend you login as soon as possible and change your password. You can
login to the website at:
<?php echo $url; ?>

Thank you,
Dallas Makerspace Website Admin
