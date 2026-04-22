<?php
// ──────────────────────────────────────────────────────────────
// Mail Configuration
// Uses PHPMailer with SMTP (works on localhost and production)
// ──────────────────────────────────────────────────────────────

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Returns a pre-configured PHPMailer instance ready to send.
 * Just set ->addAddress(), ->Subject, ->Body, then call ->send().
 */
function getMailer(): PHPMailer
{
    $mail = new PHPMailer(true); // true = throw exceptions on error

    // ── SMTP Server Settings ──────────────────────────────────
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // or smtp.outlook.com, mail.yourhost.com
    $mail->SMTPAuth = true;
    $mail->Username = 'masterofthemaster3@gmail.com'; // your Gmail address
    $mail->Password = 'ruirqdxthwqjautl'; // Gmail App Password (NOT your login password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // ── Sender Identity ───────────────────────────────────────
    $mail->setFrom('masterofthemaster3@gmail.com', 'Furni Store');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    return $mail;
}
