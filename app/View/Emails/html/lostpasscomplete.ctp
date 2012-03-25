<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>Your password has been reset successfully, your new login information is:<br>
Username: <?php echo $user['User']['username']; ?><br>
Password: <?php echo $password; ?></p>
<p>We recommend you login as soon as possible and change your password. You can login to the website at:<br>
<?php echo $url; ?></p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
