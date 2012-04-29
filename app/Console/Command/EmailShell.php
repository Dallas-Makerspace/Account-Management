<?php

class EmailShell extends AppShell {
	public $uses = array('Email');

	public function main() {
		App::uses('CakeEmail', 'Network/Email');

		$this->Email->Behaviors->attach('Containable');
		$this->Email->contain('User',array('Post' => array('User', 'Thread' => 'Board')));
		$emails = $this->Email->find('all');

		foreach ($emails as $email) {
			$first_post = $this->Email->Post->Thread->firstPost($email['Post']['thread_id'],array('recursive' => -1));

			$CakeEmail = new CakeEmail('boards');
			$CakeEmail->template('post')
				->viewVars(array('email' => $email))
				->to($email['User']['email'])
				->from(array(strtolower($email['Post']['Thread']['Board']['name']) . '@boards.dallasmakerspace.org' => $email['Post']['Thread']['Board']['description']));

			if ($first_post['Post']['id'] == $email['Email']['post_id']) {
				$CakeEmail->subject("[{$email['Post']['Thread']['Board']['name']}] {$email['Post']['Thread']['subject']}");
			} else {
				$CakeEmail->subject("[{$email['Post']['Thread']['Board']['name']}] RE: {$email['Post']['Thread']['subject']}");
			}

			$CakeEmail->send();

			$this->Email->delete($email['Email']['id']);
		}

		//CakeLog::write('email', 'Log message here');
	}
}
