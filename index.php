<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$error = false;
	$messages = array();

	$len = array('min' => 10, 'max' => 500);

	if (isset($_POST['message']))
	{
		iaUtil::loadUTF8Functions();

		$data = array();
		$data['sender_name'] = iaSanitize::html($_POST['sender_name']);
		$data['receiver_name'] = iaSanitize::html($_POST['receiver_name']);
		$data['sender_email'] = $_POST['sender_email'];
		$data['receiver_email'] = $_POST['receiver_email'];
		$data['message'] = iaSanitize::html($_POST['message']);
		$data['ip'] = iaUtil::getIp();
		$body_len = utf8_strlen($data['message']);

		if (!iaValidate::isEmail($data['sender_email']))
		{
			$error = true;
			$messages[] = iaLanguage::get('sender_email_incorrect');
		}

		if (!iaValidate::isEmail($data['receiver_email']))
		{
			$error = true;
			$messages[] = iaLanguage::get('receiver_email_incorrect');
		}

		if ($len['min'] > $body_len || $len['max'] < $body_len)
		{
			$error = true;
			$messages[] = str_replace('{NUM}', "{$len['min']}-{$len['max']}", iaLanguage::get('message_len_incorrect'));
		}

		if (!iaUsers::hasIdentity() && !iaValidate::isCaptchaValid())
		{
			$error = true;
			$messages[] = iaLanguage::get('confirmation_code_incorrect');
		}

		if (!$error)
		{
			$iaMailer = $iaCore->factory('mailer');
			$iaMailer->IsHTML = true;

			$emailTemplate = iaCore::get('tell_a_friend_messagebody');
			$emailSubject = iaCore::get('tell_a_friend_messagesubject');

			$data = array_map(array('iaSanitize', 'sql'), $data);
			$iaDb->insert($data, array('date' => 'NOW()'), 'tell_a_friend');

			// validate config email address
			if (iaValidate::isEmail($data['receiver_email']))
			{
				$email_to_send = $data['receiver_email'];
			}

			$iaMailer->addAddress($email_to_send);

			$iaMailer->From = $data['sender_email'];
			$iaMailer->FromName = "From: {$data['sender_name']}<{$data['sender_email']}>";
			$iaMailer->Subject = str_replace(array('{RECEIVER}', '{SENDER}'), array($data['receiver_name'],$data['sender_name']), $emailSubject);
			$iaMailer->Body = str_replace('{URL}', IA_URL, $emailTemplate) . '<br>' . $data['message'];

			$iaMailer->send();

			$messages = array(iaLanguage::get('message_sent'));
		}

		$iaView->setMessages($messages, $error ? iaView::ERROR : iaView::SUCCESS);
	}

	$iaView->display('index');
}