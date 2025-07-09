<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendVerificationEmail($userEmail, $userName) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'muthudinesh1271@gmail.com';     
        $mail->Password   = 'lawipfngkppzqjjv';         
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and Recipient
        $mail->setFrom('muthudinesh1271@gmail.com', 'ICMR-NIE Internship');
        $mail->addAddress($userEmail, $userName);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'ICMR-NIE Internship Application Confirmation';
        $mail->Body    = "
            <h3>Dear {$userName},</h3>
            <p>Thank you for submitting your internship application at ICMR-NIE.</p>
            <p>We have received your application and will review it shortly.</p>
            <p>Best regards,<br>ICMR-NIE Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
