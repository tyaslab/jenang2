<?php

namespace Jenang2\Mailer;


class Mailer {
    private $transport;

    public function __construct($transport_type='smtp', $param=NULL) {
        if ($transport_type == 'smtp') {
            if (!$param || !isset($param['host'])) {
                $host = getenv('EMAIL_HOST');
            } else {
                $host = $param['host'];
            }

            if (!$param || !isset($param['port'])) {
                $port = getenv('EMAIL_PORT');
            } else {
                $port = $param['port'];
            }

            if (!$param || !isset($param['username'])) {
                $username = getenv('EMAIL_HOST_USER');
            } else {
                $username = $param['username'];
            }

            if (!$param || !isset($param['password'])) {
                $password = getenv('EMAIL_HOST_PASSWORD');
            } else {
                $password = $param['password'];
            }

            if (!$param || !isset($param['use_tls'])) {
                $use_tls = getenv('EMAIL_USE_TLS') == 'yes';
            } else {
                $use_tls = $param['use_tls'];
            }

            $this->setSmtpTransport($host, $port, $username, $password, $use_tls);
        } elseif ($transport_type == 'sendmail') {
            if (!$param) {
                $sendmail_param = getenv('EMAIL_SENDMAIL_PARAM');
            } else {
                $sendmail_param = $param;
            }

            $this->setSendmailTransport($sendmail_param);
        }
    }

    public function setSmtpTransport($host, $port, $username, $password, $use_tls) {
        $this->transport = (new \Swift_SmtpTransport($host, $port))
          ->setUsername($username)
          ->setPassword($password);
    }

    public function setSendmailTransport($param) {
        $this->transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
    }

    public function send($subject, $from, $to, $body, $content_type='text/html') {
        if (!$this->transport) throw new \Jenang2\MailerException("Transport not configured!");

        $mailer = new \Swift_Mailer($this->transport);

        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $content_type);

        return $mailer->send($message);
    }
}
