<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("PHPMailer.php");
require("Exception.php");
require("SMTP.php");

$mail = new PHPMailer;

function sendMail($mail, $address, $name, $message) {
	try {
		// SMTP

		$mail -> isSMTP();
		$mail -> Host       = "mail.gmx.net";                   
		$mail -> SMTPAuth   = true;                                
		$mail -> Username   = "simon.waldek@gmx.de";             
		$mail -> Password   = "TAWMIODDVEBHNQKHCVWR";                           
		$mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
		$mail -> Port       = 587;                                   

		// Sending Mail

		$mail -> setFrom("simon.waldek@mailueberfall.de");
		$mail -> addAddress("simon.waldek@mailueberfall.de");
		$mail -> addReplyTo($address, $name);
		$mail -> Subject = "Neue Anfrage: $name";
		$mail -> Body    = "$message";

		$mail -> send();
		header("Location: thanks.html");
	} catch (Exception $e) {
		echo "Message could not be sent. Please try again later or use the e-Mail";
	}
}

// Logic that actually reads the form and sends the mail

$errors = [];
$errorMessage = '';

if (!empty($_POST)) {
	$name = $_POST['name'];
	$address = $_POST['mail'];
	$message = $_POST['message'];
	
	if (empty($name)) {
		$errors[] = "Bitte einen Namen angeben.";
	}
	
	if (empty($address) or (!filter_var($address, FILTER_VALIDATE_EMAIL))) {
		$errors[] = "Bitte eine gültige E-Mail-Adresse angeben.";
	}

	if (empty($message)) {
		$errors[] = "Bitte eine Nachricht angeben.";
	}
	
	if (empty($errors)) {
		sendMail($mail, $address, $name, $message);
	} else {
		$allErrors = join('<br/>', $errors);
		echo "<p style='color: red;'>{$allErrors}</p>";
		echo "<INPUT TYPE='button' VALUE='Back' onClick='history.go(-1);'>";
	}
}

?>
