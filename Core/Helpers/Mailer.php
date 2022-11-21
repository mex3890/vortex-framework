<?php

namespace Core\Helpers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mail;

    /**
     * @var int $debug_verbose
     * 0 - Debug Off <br>
     * 1 - Debug Client<br>
     * 2 - Debug Server<br>
     * 3 - Debug Connection<br>
     * 4 - Debug Low Level
     */
    public function __construct(int $debug_verbose = 0)
    {
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPDebug = $debug_verbose;
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = $_ENV['MAIL_SMTP_SECURE'];
        $this->mail->Port = $_ENV['MAIL_PORT'];
    }

    /**
     * @throws Exception
     */
    public function make(string $destination_mail, string $destination_name = null, string $from = null, string $reply_to_mail = null, string $from_mail_name = null): Mailer
    {
        $this->mail->setFrom($from ?? $_ENV['MAIL_USERNAME']);
        $destination_name ?
            $this->mail->addAddress($destination_mail, $destination_name) :
            $this->mail->addAddress($destination_mail);
        $this->mail->addReplyTo($reply_to_mail ?? $_ENV['MAIL_USERNAME'], $from_mail_name ?? $_ENV['APP_NAME']);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function withAttachments(array|string $attachments): Mailer
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment => $name) {
                $this->mail->addAttachment($attachment, $name);
            }
        } else {
            $this->mail->addAttachment($attachments);
        }
        return $this;
    }

    public function withEmbeddedImage(array $image_name_path)
    {
        foreach ($image_name_path as $name => $path) {
            $this->mail->addEmbeddedImage($path, $name);
        }

        return $this;
    }

    public function mount(string $subject, string $body, bool $is_Html = false, bool $use_template_file = false): Mailer
    {
        $this->mail->Subject = $subject;
        if ($is_Html) {
            $this->mail->isHTML();
            if ($use_template_file) {
                $this->mail->Body = file_get_contents($body);
            } else {
                $this->mail->Body = $body;
            }
        } else {
            $this->mail->Body = $body;
            $this->mail->AltBody = $body;
        }

        return $this;
    }

    /**
     * @return bool|Exception
     */
    public function send(): bool|Exception
    {
        try {
            $this->mail->send();
            return 'Message has been sent';
        } catch (Exception) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
