<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// Email address verification
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$comments = trim($_POST['comments']);

// Validation
if (empty($name)) {
    echo '<div class="error_message">Enter your name.</div>';
    exit();
} else if (empty($email)) {
    echo '<div class="error_message">Enter a valid email address.</div>';
    exit();
} else if (!isEmail($email)) {
    echo '<div class="error_message">You have entered an invalid e-mail address, try again.</div>';
    exit();
} else if (empty($comments)) {
    echo '<div class="error_message">Enter your message.</div>';
    exit();
}

// Sanitize inputs to prevent XSS
$name     = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email    = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$comments = htmlspecialchars($comments, ENT_QUOTES, 'UTF-8');

// Configuration options
$address = "rekikfitret@gmail.com";
$e_subject = 'You\'ve been contacted by ' . $name . '.';

$e_body = "You have been contacted by: $name" . PHP_EOL . PHP_EOL;
$e_content = "Message:\r\n$comments" . PHP_EOL . PHP_EOL;
$e_reply = "E-mail: $email";

// Construct message
$msg = wordwrap($e_body . $e_content . $e_reply, 70);

// Email headers
$headers = "From: $email" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

// Send email
if (mail($address, $e_subject, $msg, $headers)) {
    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h3>Email Sent Successfully.</h3>";
    echo "<p>Thank you <strong>$name</strong>, your message has been submitted to us.</p>";
    echo "</div>";
    echo "</fieldset>";
} else {
    echo '<div class="error_message">There was an error sending your message. Please try again later.</div>';
}

?>
