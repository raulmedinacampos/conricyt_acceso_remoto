<?php

class Email{

	public $to;
	public $from;
	public $message;
	public $headers;
	public $args;

	private $twig;
	private $template;

	public function __construct($twig, $to = array(), $from = '', $message = '', $args = array())
	{
		$this->twig = $twig;
		$this->to = $to;
		$this->from = $from;
		$this->message = $message;
		$this->args = $args;
		
		/* Funciones de la libreria PHPMailer */
		$this->phpmailer = new PHPMailer();
		$this->phpmailer->IsSMTP();
		$this->phpmailer->SMTPDebug  = 0;
		$this->phpmailer->SMTPAuth   = true;					// activa autenticación
		$this->phpmailer->Host       = "smtp.gmail.com";		// servidor de correo
		//$this->phpmailer->Host       = "74.125.136.108";		// servidor de correo
		$this->phpmailer->Port       = 465;                    // puerto de salida que usa Gmail
		$this->phpmailer->SMTPSecure = 'ssl';					// protocolo de autenticación
		$this->phpmailer->Username   = "conricyt@gmail.com";
		$this->phpmailer->Password   = 'C0nR1c17p1x3l8lu3';
			
		$this->phpmailer->SetFrom('conricyt@gmail.com', 'CONRICyT');
			
		foreach($to as $correo) {
			$this->phpmailer->AddAddress($correo);
		}
		
		$this->phpmailer->CharSet = 'UTF-8';
	}

	private function getTemplate()
	{
		$this->template = $this->twig->render($this->message, $this->args);
		$xml = simplexml_load_string($this->template);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		return array(
			'message' => $this->template,
			'subject' => 'Servicio de Acceso Remoto CONRICYT' //$array['html']['head']['title']
		);
	}
	    
	private function getHeaders()
	{
		
		$headers = array
		(
			'MIME-Version: 1.0',
			'Content-Type: text/html; charset="UTF-8"',
			'Content-Transfer-Encoding: 7bit',
			'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
			'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
			'From: ' . $this->from,
			'Bcc: ' . 'nilbalion@outlook.com',
			'Reply-To: ' . $this->from,
			'Return-Path: ' . $this->from,
			'X-Mailer: PHP v' . phpversion(),
			'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
		);	
		return implode("\n" , $headers);
	}

	public function send($tipo)
	{
		$template = $this->getTemplate();
		
		$this->phpmailer->Subject    = utf8_encode($template['subject']);
		$this->phpmailer->AltBody    = utf8_encode($template['subject']);
		
		$this->phpmailer->MsgHTML($template['message']);
		
		if($tipo=="PDF"){
			$file = "terms.pdf";
			/*$file_size = filesize($file);
			$handle = fopen($file, "r");
			$content = fread($handle, $file_size);
			fclose($handle);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
			$name = basename($file);
			$header = "From: ".$this->from."\r\n";
			$header .= "Bcc: nilbalion@outlook.com\r\n";
			$header .= "Reply-To: ".$this->from."\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/html; charset=UTF-8\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $template['message']."\r\n\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$file."\"\r\n";
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$file."\"\r\n\r\n";
			$header .= $content."\r\n\r\n";
			$header .= "--".$uid."--";
			
			mail(implode(', ', $this->to), $template['subject'], "", $header);*/
			$this->phpmailer->AddAttachment($file);
		}else{
			//mail(implode(', ', $this->to),$template['subject'],$template['message'],$this->getHeaders());
			
		}
		
		$this->phpmailer->Send();
	}

}