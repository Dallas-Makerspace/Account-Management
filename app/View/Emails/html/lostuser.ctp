<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
<?php $url2 = $this->Html->url(array('controller' => 'users', 'action' => 'lostpass'), true); ?>
<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>You are receiving this e-mail because you have lost or forgotten the username
for your account.</p>
<p>Your username is:<br>
<?php echo $user['User']['username']; ?></p>
<p>You can login at:<br>
<?php echo $url; ?></p>
<p>To reset your password, please visit:<br>
<?php echo $url2; ?></p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
