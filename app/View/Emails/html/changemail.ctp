<p>Hello <?php echo $user['User']['first_name']; ?>,</p>
<p>You are receiving this e-mail because you have requested to change the address that we have on file.</p>
<p>The old address in our system was:<br>
<?php echo $user['User']['email']; ?></p>
<p>The new address you specified was:<br>
<?php echo $new_email; ?></p>
<p>If you believe that this change was in error, please contact as soon as possible so that we can sort things out.</p>
<p>Thank you,<br>Dallas Makerspace Website Admin</p>
