<?php $url = $this->Html->url(array('controller' => 'users', 'action' => 'verifylostpass', $user['User']['verification_code']), true); ?>
<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>You are receiving this e-mail because you have requested to reset the password for your acccount.</p>
<p>To reset your password, please visit:<br>
<a href="<?php echo $url; ?>"><?php echo $url; ?></p>
<p>If you believe that this change was in error, please contact as soon as possible so that we can sort things out.</p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
