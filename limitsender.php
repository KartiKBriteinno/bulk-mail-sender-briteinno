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
    $emailLimit = 150;

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

        // Load the sent email counts
        $sentEmailCounts = loadSentEmailCounts();

        foreach ($recipientEmails as $key => $recipientEmail) {
            if ($key >= $emailLimit) {
                echo 'Email sending limit reached.';
                break;
            }

            // Check if the sender has reached the limit for the last 24 hours
            $senderKey = $user . '_' . date('Y-m-d');
            if (!isset($sentEmailCounts[$senderKey])) {
                $sentEmailCounts[$senderKey] = 0;
            }

            if ($sentEmailCounts[$senderKey] >= $emailLimit) {
                echo 'Todays Limit reached.Comeback after 24hours<br>';

                // If the sender's limit is reached, we stop sending emails
                break;
            }

            try {
                $mail->clearAddresses(); // Clear previous recipient address
                $mail->addAddress($recipientEmail); // Add new recipient address
                $status = $mail->send();

                echo 'Email sent to: ' . $recipientEmail;

                if ($status) {
                    echo ' - Sent successfully. (Email ' . ($key + 1) . ')<br>';
                    $successCount++;

                    // Increase the sender's sent email count
                    $sentEmailCounts[$senderKey]++;
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

        // Save the updated sent email counts
        saveSentEmailCounts($sentEmailCounts);

        // Check if all emails were sent successfully
        if ($successCount === count($recipientEmails)) {
            echo 'All emails sent successfully!';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
    }
}

// Load the sent email counts
function loadSentEmailCounts() {
    $filename = 'sent_email_counts.json';
    $counts = [];

    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $counts = json_decode($data, true);
    }

    return $counts;
}

// Save the sent email counts
function saveSentEmailCounts($counts) {
    $filename = 'sent_email_counts.json';
    $data = json_encode($counts);

    file_put_contents($filename, $data);
}
?>