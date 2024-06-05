<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

use App\Models\Admin\UsersModel;
use App\Models\NotificationModel;

class UserController extends ResourceController
{


    public function getuserAddress()
    {
        try {

            $UsersModels = new UsersModel();

            $json = $this->request->getJSON();

            $user_id = $this->request->getGet('user_id');
            $reponse = $UsersModels->getuseraddressdetails($user_id);
            $message = [
                'message' => 'userAddress Successfully fetched dashboard count'
            ];

            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message, 'data' => $reponse]);

        } catch (\Exception $e) {
            print_r($e);
            $message = [
                'message' => 'Something when wrong please try again',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }




    //     public function sendEmailAndStoreData()
//     {

    //         $notifyModel = new NotificationModel();
//         $json = $this->request->getJSON();
//         $user_id = $this->request->getGet('user_id');
//         $type = $json->notify_type;
//         $order_date = $json->order_date;
//         $order_number = $json->order_number;
//         $text = $json->message;
//         $data = [

    //             'email' => $json->email,
//             'message' => $json->message,
//         ];
//         $notiMessage = $text;

    //         $object_data = [
//             'userId' => $user_id,
//             'type' => $type,
//             'order_date' => $order_date,
//             'order_number' => $order_number,
//             'content' => $notiMessage,
//         ];
//         $intsertedId = $notifyModel->insert($object_data);
//         if ($intsertedId) {
//             $message = [
//                 'message' => 'Inserted Notification ',
//                 'status' => 201
//             ];
//             return $this->response->setStatusCode(201)->setJSON(['messageobject' => $message]);


    //         }
//         try {


    //             if ($type == 'email') {





    //                 //sendgrid
// // SENDGRID_API_KEY="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"
// // SENDER_ID="NCM"
// // SENDGRID_FROM_EMAIL="maheshramo35@gmail.com"
// // SENDGRID_SUBJECT="YPC team:Admin credentials!"
// // SENDGRID_FROM_NAME ="YPC Team"
// // cc="hello"
// $email = \Config\Services::email();

    //             // Set your SendGrid API key
//             $email->initialize([
//                 'protocol' => 'smtp',
//                 'SMTPHost' => 'smtp.sendgrid.net',
//                 'SMTPPort' => 587,
//                 'SMTPUser' => 'apikey',
//                 'SMTPPass' => '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',
//                 'mailType' => 'html',
//                 'SMTPCrypto' => 'tls',
//                 'newline' => "\r\n"
//             ]);

    //             $email->setFrom('maheshramo35@gmail.com', 'FCGSS');
//             $email->setTo('ananthicmr@gmail.com');
//             $email->setSubject('Hello everyone');
//             $email->setMessage($notiMessage);

    //             }



    //             // $emailModel = new EmailModel();
//             // $emailModel->insert($data);

    //             echo 'Email sent and data stored successfully.';
//         } catch (\Exception $e) {
//             //throw $th;
//             $message = [
//                 'message' => 'Something went wrong. Please try again',
//                 'status' => 500
//             ];
//             return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);

    //         }

    //     }
    public function sendEmailAndStoreData()
    {
        $notifyModel = new NotificationModel();
        $json = $this->request->getJSON();
        $user_id = $this->request->getGet('user_id');
        $type = $json->notify_type;
        $order_date = $json->order_date;
        $order_number = $json->order_number;
        $text = $json->message;
        $phone=$json->phone;
        $email1 = $json->email;
        $data = [
            'email' => $json->email,
            'message' => $json->message,
        ];
        $notiMessage = $text;

        $object_data = [
            'userId' => $user_id,
            'type' => $type,
            'order_date' => $order_date,
            'order_number' => $order_number,
            'content' => $notiMessage,
        ];
  

            
$params=array(  
    'token' => 'fg1pv6zefmb9jpcr',  
    'to' => "+91$phone", 
    'body' => "$notiMessage" 
    );  
    $curl = curl_init();  
    curl_setopt_array($curl, array(  
      CURLOPT_URL => "https://api.ultramsg.com/instance85126/messages/chat",  
      CURLOPT_RETURNTRANSFER => true,  
      CURLOPT_ENCODING => "",  
      CURLOPT_MAXREDIRS => 10,  
      CURLOPT_TIMEOUT => 30,  
      CURLOPT_SSL_VERIFYHOST => 0,  
      CURLOPT_SSL_VERIFYPEER => 0,  
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,  
      CURLOPT_CUSTOMREQUEST => "POST",  
      CURLOPT_POSTFIELDS => http_build_query($params),  
      CURLOPT_HTTPHEADER => array(  
        "content-type: application/x-www-form-urlencoded"  
      ),  
    ));  
      
    $response = curl_exec($curl);  
    $err = curl_error($curl);  
      
    curl_close($curl);  
      
    if ($err) {  
      echo "cURL Error #:" . $err;  
    } else {  
    //   echo $response;  
    } 
        try {

            $insertedId = $notifyModel->insert($object_data);
            if (!$insertedId) {
                throw new \Exception('Failed to insert notification data');
            }
            if ($type == 'email') {

                $email = \Config\Services::email();


                $email->initialize([
                    'protocol' => 'smtp',
                    'SMTPHost' => 'smtp.sendgrid.net',
                    'SMTPPort' => 587,
                 
                    'SMTPAuth'   => true,
                    'SMTPUser' => 'apikey',
                    'SMTPPass' => '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',
                    'mailType' => 'html',
                    'SMTPCrypto' => 'tls',
                    'newline' => "\r\n"
                ]);


                $email->setFrom('akramshakina3055@gmail.com', 'Kaveri Store');
                $email->setTo("$email1");
                $email->setSubject('Kaveri Store.');
                $email->setMessage($notiMessage);


                if (!$email->send()) {
                    $errorMessage = $email->printDebugger(['headers']);


                    $errorMessage = substr($errorMessage, strpos($errorMessage, 'Unable to send email using SMTP.'));


                    $inserteddata = $notifyModel->set('error_message', $errorMessage)->where("id", $insertedId)->update();
                    if (!$inserteddata) {
                        // throw new \Exception('Failed to insert error data');
                        return $this->response->setStatusCode(201)->setJSON(['error' => 'Failed to insert error data']);

                    }

                    // throw new \Exception('Failed to send email: ' . $errorMessage);
                    return $this->response->setStatusCode(201)->setJSON(['error' => $inserteddata]);

                }

            }

            return $this->response->setStatusCode(200)->setJSON(['message' => 'Email sent and data stored successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }


    public function getmailcheck()
    {
        try {
            $UsersModels = new UsersModel();


            $email = $this->request->getGet('email');


            $user = $UsersModels->getUserByEmail($email);

            if ($user) {

                $message = [
                    'message' => 'Email already exists',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            } else {

                $message = [
                    'message' => 'Email not found',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
            }
        } catch (\Exception $e) {

            $message = [
                'message' => 'Something went wrong. Please try again',
                'status' => 500
            ];
            return $this->response->setStatusCode(500)->setJSON(['messageobject' => $message]);
        }
    }


}