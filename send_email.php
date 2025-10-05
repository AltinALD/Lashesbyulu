<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars(trim($_POST["name"] ?? ''));
    $phone   = htmlspecialchars(trim($_POST["phone"] ?? ''));
    $email   = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $date    = htmlspecialchars(trim($_POST["date"] ?? ''));
    $message = nl2br(htmlspecialchars(trim($_POST["message"] ?? '')));

    if (empty($name) || empty($phone) || empty($date)) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    $subject = "New Booking Request - Lash by Ulu";
    $mail = new PHPMailer(true);

    try {
        if (!ob_get_level()) ob_start();

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'presslogic36@gmail.com';
        $mail->Password   = 'qsoz cpnl dvwd ibfs'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 0;

        $mail->setFrom('no-reply@lashbyulu.com', 'Lash by Ulu Website');
        $mail->addAddress('altinejup@gmail.com'); // You can add more recipients

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = "
            <div style='font-family:sans-serif;max-width:500px;margin:0 auto;'>
                <h2 style='color:#f08da3;'>New Booking Request</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Preferred Date:</strong> $date</p>
                <p><strong>Message:</strong><br>$message</p>
                <hr style='border:0;border-top:1px solid #eee;'>
                <p style='font-size:12px;color:#888;'>This message was sent from the Lash by Ulu booking form.</p>
            </div>
        ";
        $mail->AltBody = "Booking Request from $name\nPhone: $phone\nEmail: $email\nDate: $date\nMessage:\n$message";

        $mail->send();

        if (ob_get_length()) ob_end_clean();
        header("Location: thank-you.html");
        exit();
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        if (in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        } else {
            header("Location: error.html");
            exit();
        }
    }
} else {
    header("Location: error.html");
    exit();
}
?>
