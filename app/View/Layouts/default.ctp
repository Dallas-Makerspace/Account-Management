<?php
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?php echo $title_for_layout; ?></title>

<?php
	echo $this->Html->meta('icon') . "\n";
	echo $this->Html->css('straightblack') . "\n";
	echo $this->Html->css('cake.modified') . "\n";
	echo $this->Html->css('gh-buttons') . "\n";
	echo $this->Html->css('straightblack-print','stylesheet',array('media' => 'print')) . "\n";
	echo $this->Html->script('jquery-1.7.1.min'); // Include jQuery library
	echo $scripts_for_layout;
?>

</head>
<body>

<div id="wrap">


<div id="header">
<?php echo $this->Html->image('logo.png'); ?><h1><?php __('Dallas Makerspace'); ?></h1>
</div>

<div id="menu">
<ul>
<li><?php echo $this->Html->link(__('Blog', true), 'http://dallasmakerspace.org/blog');?></li>
<li><?php echo $this->Html->link(__('Wiki', true), 'http://dallasmakerspace.org/wiki');?></li>
<li><?php echo $this->Html->link(__('Calendar', true), array('controller' => 'pages', 'action' => 'display', 'calendar'));?></li>
<li><?php echo $this->Html->link(__('Discuss', true), array('controller' => 'boards', 'action' => 'index'));?></li>
<?php if(in_array($auth['class'],array('supporting','regular'))): ?>
<li><?php echo $this->Html->link(__('Inventory', true), 'https://dallasmakerspace.org/inventory');?></li>
<li><?php echo $this->Html->link(__('Members', true), array('controller' => 'users', 'action' => 'index'));?></li>
<?php endif; ?>
<li><?php echo $this->Html->link(__('Voting', true), 'https://dallasmakerspace.org/voting');?></li>
<li class="right"><?php echo $this->Html->link(__('Help', true), array('controller' => 'pages', 'action' => 'display', 'help'));?></li>
<?php if($auth): ?>
<li class="right"><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));?></li>
<li class="right"><?php echo $this->Html->link(__('My Account', true), array('controller' => 'users', 'action' => 'dashboard'));?></li>
<?php else: ?>
<li class="right"><?php echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'login'));?></li>
<li class="right"><?php echo $this->Html->link(__('Register', true), array('controller' => 'users', 'action' => 'register'));?></li>
<?php endif; ?>
</ul>
</div>

<div id="contentwrap"> 

<div id="content">

<?php
echo $this->Session->flash();
echo $this->Session->flash('auth');
?>

<?php echo $content_for_layout; ?>

<!-- 
<div class="printonly">
<h3>QR code for this page:</h3>
<?php /* echo $this->Qrcode->url($this->Html->url(null,true),array('size' => '150x150','margin' => 0)); */ ?>
</div>
-->

</div>

<?php if ($auth): ?>
<div id="sidebar">
	<?php
	echo $this->element('actions');
	if ($auth['role'] == 'admin') {
		echo $this->element('admin_actions');
	}
	?>
</div>
<?php endif; ?>

<div style="clear: both;"> </div>

</div>

<div id="footer">
<p><a href="https://github.com/Dallas-Makerspace/Account-Management">Source code on GitHub</a> | Content is available under <a href="http://creativecommons.org/licenses/by-sa/3.0/" class="external ">Attribution-Share Alike 3.0 Unported</a> | Template by <a href="http://www.templatestable.com/free-templates/straight-black-free-web-template/">Free Css Templates</a></p>
</div>

</div>
<div class="debug">
	<?php echo $this->element('sql_dump'); ?>
</div>
<?php echo $this->Js->writeBuffer(); // Write cached scripts ?>
</body>
</html>
