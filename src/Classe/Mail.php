<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $apiKey;
    private $apiSecret;

    public function __construct()
    {
        $this->apiKey = $_ENV['MAILJET_APIKEY'];
        $this->apiSecret = $_ENV['MAILJET_APISECRET'];
    }

    public function send($toEmail, $toName, $subject, $content): void
    {
        $mj = new Client($this->apiKey, $this->apiSecret, true, ['version' => 'v3.1']);
        
        $body = [
            'Messages' => [[
                'From' => ['Email' => "noreply@gymmanagement.com", 'Name' => "Gym Management"],
                'To' => [['Email' => $toEmail, 'Name' => $toName]],
                'Subject' => $subject,
                'HTMLPart' => $content
            ]]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }
}