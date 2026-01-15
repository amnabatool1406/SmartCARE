<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Session se data uthayein jo add_doctor ne save kiya tha
if (isset($_SESSION['mail_data'])) {
    $data = $_SESSION['mail_data'];
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info.smartcare.org@gmail.com';
        $mail->Password   = 'vgft udnc zqwc gcjf'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('info.smartcare.org@gmail.com', 'SmartCARE Admin');
        $mail->addAddress($data['email'], $data['name']);

        $mail->isHTML(true);
        $mail->Subject = 'Your SmartCARE Doctor Credentials';
        $mail->Body    = "Hello Dr. {$data['name']},<br>Your username: <b>{$data['username']}</b><br>Password: <b>{$data['password']}</b>";

        $mail->send();
        header("Location: doctor_success.php?mail_sent=1");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    header("Location: manage_doctors.php");
}