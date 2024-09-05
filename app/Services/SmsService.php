<?php

namespace App\Services;


use Twilio\Rest\Client;

class SmsService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $this->from = env('TWILIO_PHONE_NUMBER');
    }

    public function sendVerificationCode($to, $code)
    {
        return $this->client->messages->create(
            $to,
            [
                'from' => $this->from,
                'body' => "Your verification code is: $code"
            ]
        );
    }
}
