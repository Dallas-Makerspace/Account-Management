<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'verify', $user['User']['verification_code']), true); ?>
Hello <?php echo $user['User']['first_name']; ?>,

Your account has been created with the username:

<?php echo h($user['User']['username']); ?>

In order to complete your account activation you will need to click on the following link:

<?php echo $url; ?>

After you visit that page, your account will be activated and you can then
login. You will also receive another e-mail from us with more information about
your account and how to use the website.

Thank you,
Dallas Makerspace Website Admin
