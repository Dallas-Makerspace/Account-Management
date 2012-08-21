<?php

class ParserShell extends AppShell {
	public $uses = array('Board');

	public function main() {
		App::import('Vendor', 'PlancakeEmailParser');
		App::uses('CakeEmail', 'Network/Email');

		$raw = null;

		while(($buffer = $this->stdin->read()) !== false) {
			$raw .= $buffer;
		}

		$emailParser = new PlancakeEmailParser($raw);

		// Possible results:
		// Firstname Lastname <address@server.tld>
		// address@server.tld
		$email['from'] = $emailParser->getHeader('From');

		// Possible results:
		// Firstname Lastname <address@server.tld>
		// address@server.tld
		$email['to'] = $emailParser->getTo();
		// Might not be needed
		$email['deliveredTo'] = $emailParser->getHeader('Delivered-To');

		$email['subject'] = $emailParser->getSubject();

		$email['body'] = $emailParser->getPlainBody();
		$email['HTML'] = $emailParser->getHTMLBody();

		/*
		Parsing steps:
			1. identify user source
				If they can't be identified, send a bounce message
			2. identify target board, check 'to'
				If it can't be found, send a bounce message
			3. see if permissions allow user to post to that board
				If no, send bounce message
			4. identify target thread, check subject then check for our footer in the body
				If it can't be found, start a new thread and send a new thread message
				If it can be found and the user isn't allowed to post to it, send a bounce message
				If it can be found and the user can post, post the reply and send no message
		*/

		debug($email);echo "\n";


		// 1. identify user source
		$from = $this->getAddr($email['from']);

		// What the...
		if ($from === false) {
			$this->error("No 'from' address");
		}

		// Find the user!
		$this->Board->User->recursive = -1;
		$user = $this->Board->User->findByEmail($from);

		// Unknown user
		if (!$user) {
			$this->bounce($from, 'Unknown User', 'Your e-mail address was not found in our system, perhaps you registered with another address or your account is inactive.');
			$this->error('User not found');
		}

		// Inactive user
		if (!$user['User']['active']) {
			$this->bounce($from, 'Unknown User', 'Your e-mail address was not found in our system, perhaps you registered with another address or your account is inactive.');
			$this->error('User not active');
		}

		// 2. identify target board, check 'to'
		// We only care about the first 'to' address, if the user screws this up they get a bounce message
		$to = $this->getAddr($email['to'][0]);

		// Really?
		if ($to === false) {
			$this->bounce($from, 'Unknown Recipient', 'We were unable to determine the recipient for your e-mail. Please verify the recipient address and try again.');
			$this->error("No 'to' address");
		}

		// Grab everything before @ which should be a lowercase version of the Board name
		$board_name = substr($to,0,strpos($to,'@'));

		// Find the board
		$this->Board->recursive = -1;
		$board = $this->Board->findByName($board_name);

		// Error 404, board not found
		if (!$board) {
			$this->bounce($from, 'Unknown Recipient', 'We were unable to determine the recipient for your e-mail. Please verify the recipient address and try again.');
			$this->error('Invalid board');
		}

		// 3. see if permissions allow user to post to that board
		if (
			// Board is private
			$board['Board']['public'] == 0 &&
			// User is not an admin
			$user['User']['role'] != 'admin' &&
			// User does not have the class of supporting or regular
			!in_array($user['User']['class'], array('supporting', 'regular'))
		) {
			// User is not authorized to access that board
			$this->bounce($from, 'Post Denied', 'You are not allowed to post to this board.');
			$this->error('Not authorized for board');
		}

		// If the board is readonly and the user is not an admin
		if ($board['Board']['readonly'] && $user['User']['role'] != 'admin') {
			// User is not allowed to post to this board
			$this->bounce($from, 'Post Denied', 'You are not allowed to post to this board.');
			$this->error('Readonly board');
		}

		// 4. identify target thread, check subject then check for our footer in the body

		// This is probably not the best way to remove stuff like Re: and [DMS]
		$subject = substr($email['subject'],strpos($email['subject'],']')+2);
		// "[DMS] Test Thread" changes to "Test Thread", hopefully

		$this->Board->Thread->recursive = -1;
		// Thanks to the magic of the Thread model's sort, we get the latest thread matching the subject
		$thread = $this->Board->Thread->findBySubject($subject);

		// If thread can't be found, try looking into the e-mail for "THREAD_ID: "
		// TODO: That^

		// If thread still can't be found, start a new one
		if (!$thread) {
			// Start a new thread
			$thread = array(
				'Thread' => array(
					'board_id' => $board['Board']['id'],
					'user_id' => $user['User']['id'],
					// Use $email['subject'] instead of $subject in case we lost useful text
					'subject' => $email['subject'],
				),
				'Post' => array(
					0 => array(
						'user_id' => $user['User']['id'],
						'mailed' => 1,
						'text' => $email['body'],
					)
				),
			);

			$this->Board->Thread->create();
			if ($this->Board->Thread->saveAssociated($thread)) {
				$thread['Thread']['id'] = $this->Board->Thread->id;

				// Send "started new thread" message
				$CakeEmail = new CakeEmail();
				$CakeEmail->template('newthread')
					->viewVars(array('user' => $user, 'board' => $board, 'thread' => $thread))
					->to($from)
					->subject('Thread Created')
					->emailFormat('text')
					->send();
				return true;
			} else {
				$this->bounce($from, 'Post Error', 'An unknown error has occured, your post has not been submitted. Please try again.');
				$this->error('Unable to save Thread');
			}
		}

		// If thread is locked and user is not an admin
		if ($thread['locked'] && $user['User']['role'] != 'admin') {
			$this->bounce($from, 'Post Denied', 'You are not allowed to post to this thread as it has been locked.');
			$this->error('Thread is locked');
		} else {
			// Post message to thread
			$post = array('Post' => array(
				'thread_id' => $user['User']['id'],
				'thread_id' => $thread['Thread']['id'],
				'mailed' => 1,
				'text' => $email['body'],
			));

			if (!$this->Board->Thread->Post->save($post)) {
				$this->bounce($from, 'Post Error', 'An unknown error has occured, your post has not been submitted. Please try again.');
				$this->error('Unable to save Post');
			} else {
				// send no message
				return true;
			}
		}
	}
/**
 * bounce method
 *
 * Sends a bounce message
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return null
 */
	private function bounce($to, $subject, $message) {
		$CakeEmail = new CakeEmail();
		$CakeEmail->template('bounce')
			->viewVars(array('message' => $message))
			->to($to)
			->subject($subject)
			->send();
	}

/**
 * getAddr method
 *
 * Extracts the e-mail address from strings such as:
 * Firstname Lastname <address@server.tld>
 *
 * @param string $check
 * @return string
 */
	private function getAddr($check) {
		if (Validation::email($check)) {
			// That was easy
			return $check;
		}

		if (preg_match('/\<(.*)\>/',$check,$matches) == 0) {
			return false;
		}

		if (Validation::email($matches[1])) {
			return $matches[1];
		} else {
			return false;
		}
	}
}
