<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $toName, $subject, $htmlContent, $plainContent = '')
{
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom(FROM_EMAIL, FROM_NAME);
    $email->setSubject($subject);
    $email->addTo($to, $toName);
    $email->addContent("text/plain", $plainContent);
    $email->addContent("text/html", $htmlContent);

    $sendgrid = new \SendGrid(SENDGRID_API_KEY);

    try {
        $response = $sendgrid->send($email);
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
?>