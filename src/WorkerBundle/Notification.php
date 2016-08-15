<?php

namespace WorkerBundle;

class Notification {
    
    private $mailer;
    private $sender;
    private $recipients;
    
    public function __construct($mailer, $sender, $recipients) {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->recipients = $recipients;
    }
    
    public function send($subject, $message) {
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->sender[0], $this->sender[1])
            ->setTo($this->recipients)
            ->setBody(
                $message,
                'text/html'
            ));
    }
    
}