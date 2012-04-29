<?php
	$full_name = "{$email['Post']['User']['first_name']} {$email['Post']['User']['last_name']}";
	$username = $email['Post']['User']['username'];
	$board_name = $email['Post']['Thread']['Board']['name'];
	$board_name_lower = strtolower($board_name);
	$board_desc = $email['Post']['Thread']['Board']['description'];

	App::uses('HtmlHelper', 'Utility');
	$url = HtmlHelper::url(array('controller' => 'threads', 'action' => 'view', $email['Post']['Thread']['id']));

	App::uses('CakeTime', 'Utility');
	$time = CakeTime::nice($email['Post']['created']);


	echo "On {$time}, {$full_name} ({$username}) wrote:\n\n";

	echo $email['Post']['text'];

	echo "\n\n_______________________________________________\n";
	echo "[{$board_name}] {$board_desc}\n";
	echo "{$board_name_lower}@boards.dallasmakerspace.org\n";
	echo "https://dallasmakerspace.org/account{$url}\n";
	echo "THREAD_ID: {$email['Post']['Thread']['id']}\n";
?>
