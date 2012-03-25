<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'login'), true); ?>
<?php $url2 = $this->Html->url(array('controller' => 'users', 'action' => 'lostpass'), true); ?>
<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>Welcome to the Dallas Makerspace website! Your account is now active and ready to be used.</p>
<p>You can login to your new account at:</p>
<p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
<p>If you forgot, your account was created with the username:</p>
<p><?php echo h($user['User']['username']); ?></p>
<p>If you lose your password you can reset it by going to:</p>
<p><a href="<?php echo $url2; ?>"><?php echo $url2; ?></a></p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
