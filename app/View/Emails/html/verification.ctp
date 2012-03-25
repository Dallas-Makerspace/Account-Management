<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'verify', $user['User']['verification_code']), true); ?>
<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>Your account has been created with the username:</p>
<p><?php echo h($user['User']['username']); ?></p>
<p>In order to complete your account activation you will need to click on the following link:</p>
<p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
<p>After you visit that page, your account will be activated and you can then login. You will also receive another e-mail from us with more information about your account and how to use the website.</p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
