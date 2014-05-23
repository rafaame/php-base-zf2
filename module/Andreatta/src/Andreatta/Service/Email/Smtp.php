<?php

namespace Andreatta\Service\Email;

use Zend\Mail,
	Zend\Mime\Part as MimePart,
	Zend\Mime\Message as MimeMessage,

	Andreatta\Service\Base;

class Smtp extends Base
{

	protected function getConfig($name)
	{

		$config = $this->getServiceLocator()->get('application')->getConfig();
		
		return $config['smtp'][$name];

	}

	public function sendEmail($configName, $to, $subject, $content)
	{

		$config = $this->getConfig($configName);
		$options = new Mail\Transport\SmtpOptions($config['options']);

		$body = new MimeMessage();

	    $bodyMessage = new MimePart($content);
	    $bodyMessage->type = 'text/html';

	    $body->setParts([$bodyMessage]);

		$mail = new Mail\Message();
		$mail->setBody($body);
		$mail->setFrom($config['identity_config']['from'], $config['identity_config']['from-name']);
		$mail->setTo($to);
		$mail->setSubject($subject);
		$mail->setEncoding('UTF-8');
  
		$transport = new Mail\Transport\Smtp($options);
		$transport->send($mail);

	}

}