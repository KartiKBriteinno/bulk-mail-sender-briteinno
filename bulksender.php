<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Disable time limit
set_time_limit(0);

if (isset($_POST['send'])) {
    $email = $_POST['emails'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $user = $_POST['u-name'];
    $password = $_POST['pass'];
    $sender_email = $_POST['s-email'];
    $sender_name = $_POST['s-name'];

    $recipientEmails = explode(',', $email);
    $recipientEmails = array_map('trim', $recipientEmails);

    $successCount = 0;
    $failCount = 0;

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $user; // Enter your email address
        $mail->Password = $password; // Enter your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($sender_email, $sender_name); // Enter your email address and name
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        foreach ($recipientEmails as $key => $recipientEmail) {
            try {
                $mail->clearAddresses(); // Clear previous recipient address
                $mail->addAddress($recipientEmail); // Add new recipient address
                $status = $mail->send();

                echo 'Email sent to: ' . $recipientEmail;

                if ($status) {
                    echo ' - Sent successfully. (Email ' . ($key + 1) . ')<br>';
                    $successCount++;
                } else {
                    echo ' - Failed to send. Error: ' . $mail->ErrorInfo . ' (Email ' . ($key + 1) . ')<br>';
                    $failCount++;
                }

                // Add a 1.5-second delay
                usleep(1500000);
            } catch (Exception $e) {
                echo ' - Failed to send. Error: ' . $e->getMessage() . ' (Email ' . ($key + 1) . ')<br>';
                $failCount++;
            }
        }

        echo 'Total emails sent successfully: ' . $successCount . '<br>';
        echo 'Total emails failed to send: ' . $failCount . '<br>';

        // Check if all emails were sent successfully
        if ($successCount === count($recipientEmails)) {
            echo 'All emails sent successfully!';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
    }
}
?>