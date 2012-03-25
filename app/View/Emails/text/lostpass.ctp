<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'verifylostpass', $user['User']['verification_code']), true); ?>
Hello <?php echo $user['User']['first_name']; ?>,

You are receiving this e-mail because you have requested to reset the password
for your acccount.

To reset your password, please visit:
<?php echo $url; ?>

If you believe that this change was in error, please contact as soon as possible
so that we can sort things out.

Thank you,
Dallas Makerspace Website Admin
