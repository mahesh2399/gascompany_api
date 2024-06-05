<?php
namespace App\Libraries;

use Twilio\Rest\Client;

class TwilioService
{
    public $twilio;

    public function __construct()
    {
        // // Load your Twilio credentials from the CI4 Config file.
        $config = config('Twilio');
       


        // // Initialize the Twilio client with your Account SID and Auth Token.
        try {
          $twilio = new Client($config->accountSid, $config->authToken);
      } catch (Twilio\Exceptions\ConfigurationException $e) {
          echo 'Configuration Exception: ' . $e->getMessage();
      }
    }

    public function sendSMS($to, $message)
    {
        // Implement your SMS sending logic here using the $this->twilio object.
        // Example:
        // $this->twilio->messages->create(
        //     $to,
        //     [
        //         'from' => $config->phoneNumber,
        //         'body' => $message,
        //     ]
        // );  
        $config = config('Twilio');

        try {
          $twilio = new Client($config->accountSid,$config->authToken);
          $message = $twilio->messages->create(
            $to, 
        array(
          "from" =>$config->phoneNumber,
          "body" => $message
        )
              // Message parameters
          );
      
          // Now you can access the 'messages' property of the $twilio object.
      } catch (Twilio\Exceptions\ConfigurationException $e) {
          echo 'Configuration Exception: ' . $e->getMessage();
      }
    



    }
}
