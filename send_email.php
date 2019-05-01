<?php
	
	require_once './PHPMailer/PHPMailerAutoload.php';
	require_once './PHPMailer/class.phpmailer.php';
	require_once './PHPMailer/class.smtp.php';
	
	/* CONFIGURATION */
	$crendentials = array(
	'email'     => 'kontakbpom@gmail.com',    
	'password'  => 'kontakbp0m'       
	);
	
	$smtp = array(
	'host' => 'smtp.gmail.com',
	'port' => 587,
	'username' => $crendentials['email'],
	'password' => $crendentials['password'],
	'secure' => 'tls'
	);
	
	$mailer = new PHPMailer();
	
	//SMTP Configuration
	$mailer->isSMTP();
	$mailer->SMTPAuth   = true; 
	$mailer->Host       = $smtp['host'];
	$mailer->Port       = $smtp['port'];
	$mailer->Username   = $smtp['username'];
	$mailer->Password   = $smtp['password'];
	$mailer->SMTPSecure = $smtp['secure']; 
	
	//Now, send mail :
	//From - To :
	$mailer->setFrom($smtp['username'], 'kontak BPOM');
	$mailer->addAddress($receiver_email);
	$mailer->isHTML(true);
	$mailer->Subject        = $subject;
	 
	
	
	$mailer->Body           = $message;
	
	
	//Check if mail is sent :
	if(!$mailer->send()){
		$this->session->set_flashdata('errors', '<div class="alert alert-danger">Gagal mengirim email : '.$mailer->ErrorInfo.'</div>');
		//echo 'Error sending mail : ' . $mailer->ErrorInfo;
	} else {
		$this->session->set_flashdata('message', '<div class="alert alert-info">Data akun telah dikirim ke Email Anda</div>');
	}
	
?>