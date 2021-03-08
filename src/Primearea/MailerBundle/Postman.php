<?php namespace Primearea\MailerBundle;


use PHPMailer;
use Psr\Log\LoggerInterface;

class Postman
{
    /** @var LoggerInterface */
    private $logger;

    /** @var PHPMailer */
    private $mailer;

    /**
     * @param string $host
     * @param int $port
     * @param string $secure
     * @param string $user
     * @param string $pass
     * @param bool $smtp_auth
     * @param string $senderEmail
     * @param string $senderName
     * @param string $projectDir
     * @param LoggerInterface $logger
     */
    public function __construct(
        string $host,
        int $port,
        string $secure,
        string $user,
        string $pass,
        bool $smtp_auth,
        string $senderEmail,
        string $senderName,
        string $projectDir,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;

        require_once $projectDir . '/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        $this->mailer = new PHPMailer();
        $this->mailer->Host = $host;
        $this->mailer->Port = $port;
        $this->mailer->SMTPAuth = $smtp_auth;
        $this->mailer->Username = $user;
        $this->mailer->Password = $pass;
        $this->mailer->Mailer = 'smtp';
        $this->mailer->SMTPSecure = $secure;
        $this->mailer->ContentType = 'text/html';
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->From = $senderEmail;
        $this->mailer->FromName = $senderName;
        $this->mailer->Timeout = 5;
        $this->mailer->SMTPDebug = 4;
        $this->mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
    }

    /**
     * Send mail
     *
     * @param string $subject
     * @param string $message
     * @param string $to
     *
     * @return bool
     */
    public function send(string $subject, string $message, string $to) : bool
    {
        ob_start();
        $this->mailer->ClearAddresses();
        $this->mailer->ClearAttachments();
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;
        $this->mailer->AddAddress($to, '');

        $sent = $this->mailer->send();
        $debug = ob_get_contents();
        ob_end_clean();

        if (!$sent) {
            $debug = explode("\n", $debug);
            $this->logger->critical('Cannot send mail', $debug);

            return false;
        }

        return true;
    }
}
