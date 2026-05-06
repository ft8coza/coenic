<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo 'Method not allowed.';
	exit;
}

$sandblastingEmail = 'martin@coenic.co.za';
$powderCoatingEmail = 'coatings@coenic.co.za';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$serviceType = trim($_POST['serviceType'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $message === '' || $serviceType === '') {
	http_response_code(400);
	echo 'Please complete all required fields.';
	exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(400);
	echo 'Invalid email address.';
	exit;
}

$recipients = [];

if ($serviceType === 'sandblasting') {
	$recipients[] = $sandblastingEmail;
} elseif ($serviceType === 'powderCoating') {
	$recipients[] = $powderCoatingEmail;
} elseif ($serviceType === 'both') {
	$recipients[] = $sandblastingEmail;
	$recipients[] = $powderCoatingEmail;
} else {
	http_response_code(400);
	echo 'Invalid service type selected.';
	exit;
}

$to = implode(',', $recipients);
$subject = 'Website Contact Form - ' . $serviceType;

$body = "Name: {$name}\n"
	. "Email: {$email}\n"
	. "Phone: {$phone}\n"
	. "Service Type: {$serviceType}\n\n"
	. "Message:\n{$message}\n";

$headers = "From: no-reply@coenic.co.za\r\n"
	. "Reply-To: {$email}\r\n"
	. "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $body, $headers);

if (!$sent) {
	http_response_code(500);
	echo 'There was a problem sending your message.';
	exit;
}

echo 'Message sent successfully.';

?>