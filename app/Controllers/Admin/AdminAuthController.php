<?php namespace App\Controllers\Admin;
use CodeIgniter\API\ResponseTrait;
use App\Models\api\CustomersModel;
use App\Models\api\TempCustomersModel;
use App\Models\Admin\UsersModel;
use Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AdminAuthController extends ResourceController
{
    use ResponseTrait;

    public function login()
    {    
        $json = $this->request->getJSON();
        $model = new UsersModel();
        $con = [
            'email'=> $json->email
        ];
        $user = $model->where($con)->first();
        if($user){
            if (password_verify($json->password, $user['password']) && (
                $user['user_type'] == 'admin' || $user['user_type'] == 'owner' || $user['user_type'] == 'staff' || $user['user_type'] == 'store staff' || $user['user_type'] == 'store admin' || $user['user_type'] == 'store owner' || $user['user_type'] == 'super admin' || $user['user_type'] == 'location admin' || $user['user_type'] == 'warehouse admin' )) {
                $token = $this->generateToken($user['id'], $user['email']);
                $response_data=['token' => $token,'email' => $user['email'],"user_id"=>$user['id'],"name"=>$user['name']];
                $message = [
                    'message' => 'Login successfully!',
                    'status' => 200
                ];
                return $this->respond(['messageobject' => $message, 'data' => $response_data]);
            } else {
                $message = [
                    'message' => 'Invaild User!',
                    'status' => 400
                ];
    
                return $this->fail(['messageobject' => $message]);
            }
        }else{
            $message = [
                'message' => 'Invaild User!',
                'status' => 400
            ];
            return $this->fail(['messageobject' => $message]);
        }
      
    }

    // Method to generate JWT token
    protected function generateToken($userId, $username)
    {
        $config = config('Jwt');
        $key =$config->authKey;
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token valid for 1 hour
        $data = [
            'Id' => $userId,
            'Email' => $username,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];
        return JWT::encode($data, $key,$config->method);
    }

    public function register()
    {

        $json = $this->request->getJSON();
        $email = $json->Email;


        // Check if the email is already registered
        if ($this->isEmailRegistered($email)) {
            return $this->failResourceExists('Email is already registered');
        }

        $CustomerName = isset($json->CustomerName) ? $json->CustomerName : null;
        $ContactPerson = isset($json->ContactPerson) ? $json->ContactPerson : null;
        $Email = isset($json->Email) ? $json->Email : null;
        $Fax = isset($json->Fax) ? $json->Fax : null;
        $MobileNo = isset($json->MobileNo) ? $json->MobileNo : null;
        $PhoneNo = isset($json->PhoneNo) ? $json->PhoneNo : null;
        $Website = isset($json->Website) ? $json->Website : null;
        $Description = isset($json->Description) ? $json->Description : null;
        $Url = isset($json->Url) ? $json->Url : null;
        $IsUnsubscribe = isset($json->IsUnsubscribe) ? $json->IsUnsubscribe : null;
        $CustomerProfile = isset($json->CustomerProfile) ? $json->CustomerProfile : null;
        $Address = isset($json->Address) ? $json->Address : null;
        $CountryName = isset($json->CountryName) ? $json->CountryName : null;
        $CityName = isset($json->CityName) ? $json->CityName : null;
        $CountryId = isset($json->CountryId) ? $json->CountryId : null;
        $CityId = isset($json->CityId) ? $json->CityId : null;
        $IsWalkIn = isset($json->IsWalkIn) ? $json->IsWalkIn : null;
        $Password = isset($json->Password) ? $json->Password : null;


        // Generate a unique verification token
        $verificationToken = bin2hex(random_bytes(32));

        // Create a new user in the database with a verification token
        $tempUserModel = new TempCustomersModel();
        $guid = Uuid::uuid4()->toString();
        $userId = $tempUserModel->insert([
            'Id'=> $guid,
            'VerificationToken' => $verificationToken,
            'IsVarified' => 0, 
            'CustomerName' => $CustomerName,
            'ContactPerson' => $ContactPerson,
            'Email'=> $Email,
            'Fax' => $Fax,
            'MobileNo' => $MobileNo,
            'PhoneNo' => $PhoneNo, 
            'Website' => $Website, 
            'Description' => $Description, 
            'Url' => $Url, 
            'IsUnsubscribe' => $IsUnsubscribe, 
            'CustomerProfile' => $CustomerProfile, 
            'Address' => $Address, 
            'CountryName' => $CountryName, 
            'CityName' => $CityName, 
            'CountryId' => $CountryId, 
            'CityId' => $CityId, 
            'IsWalkIn' => $IsWalkIn, 
            'Password' => $Password
            // Add other fields as needed
        ]);

        // Send the verification email
        $emailStatus = $this->sendVerificationEmail($email, $verificationToken);
            if($emailStatus == 1){
                return $this->respondCreated(['status' => 'success', 'message' => 'Registration successful. Please check your email for verification instructions','emailStatus'=>$emailStatus]);
            }else{
                return $this->fail('Something when wrong please try again', 400);
            }
    }

    // Helper method to check if the email is already registered
    private function isEmailRegistered($email)
    {
        $userModel = new CustomersModel();

        $existingUser = $userModel->where('Email', $email)->first();

        return $existingUser !== null;
    }

    // Helper method to send a verification email
    private function sendVerificationEmail($emails, $verificationToken)
    {
        
        $verificationLink = "http://localhost:4100/auth/emailverified?token=$verificationToken";
        $email = \Config\Services::email();
        $email->setTo($emails);
        $email->setSubject('Verify your email');
        $email->setMessage("Click the following link to verify your email: $verificationLink");
        // $email->send();

        if ($email->send()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function verifyEmail()
    {
        $token = $this->request->getGet('token');
       
        // Find the user with the given verification token

        $tempUserModel = new TempCustomersModel();
        $userModel = new CustomersModel();

        $tempuser = $tempUserModel->where('VerificationToken', $token)->first();

        if (!$tempuser) {
            return $this->failNotFound('Invalid verification token');
        }

        // Update the user's status to verified
        $tempUserModel->update($tempuser['Id'], ['IsVarified' => 1, 'VerificationToken' => null]);

        $tempUserData = $tempUserModel->find($tempuser['Id']);

        $customerData=[
        'Id'=> $tempUserData['Id'],
        'CustomerName' => $tempUserData['CustomerName'],
        'ContactPerson' => $tempUserData['ContactPerson'],
        'Email' => $tempUserData['Email'],
        'Fax' => $tempUserData['Fax'],
        'MobileNo' => $tempUserData['MobileNo'],
        'PhoneNo' => $tempUserData['PhoneNo'],
        'Website' => $tempUserData['Website'],
        'Description' => $tempUserData['Description'],
        'Url' => $tempUserData['Url'],
        'IsVarified' => $tempUserData['IsVarified'],
        'IsUnsubscribe' => $tempUserData['IsUnsubscribe'] != null ? $tempUserData['IsUnsubscribe'] : 0 ,
        'CustomerProfile' => $tempUserData['CustomerProfile'],
        'Address' => $tempUserData['Address'],
        'CountryName' => $tempUserData['CountryName'],
        'CityName' => $tempUserData['CityName'],
        'CountryId' => $tempUserData['CountryId'],
        'CityId' => $tempUserData['CityId'],
        'IsWalkIn' => $tempUserData['IsWalkIn'] != null ? $tempUserData['IsWalkIn'] : 0,
        'CreatedDate' => $tempUserData['CreatedDate'] != null ? $tempUserData['CreatedDate'] : date('Y-m-d H:i:s'),
        'CreatedBy' => $tempUserData['CreatedBy'] ,
        'ModifiedDate' => $tempUserData['ModifiedDate'] != null ? $tempUserData['ModifiedDate'] : date('Y-m-d H:i:s'),
        'ModifiedBy' => $tempUserData['ModifiedBy'] ,
        'DeletedDate' => $tempUserData['DeletedDate'],
        'DeletedBy' => $tempUserData['DeletedBy'],
        'IsDeleted' => $tempUserData['IsDeleted'] != null ? $tempUserData['IsDeleted'] : 0,
        'Password' => $tempUserData['Password']
        ];

        $customerChk = $userModel->where('Id', $tempUserData['Id'])->first();

        if ($customerChk) {
            return $this->failNotFound('Invalid verification token');
        }else{
            $customer_Id = $userModel->insert($customerData);
            $token = $this->generateToken($customer_Id, $tempUserData['Email']);
            return $this->respond(['status' => 'success', 'message' => 'Email verification successful','token'=>$token,'Email'=>$tempUserData['Email']]);
        }

    }

    

     public function isEmailvalidation()
    {
        $email = $this->request->getGet('Email');
        $userModel = new CustomersModel();
        $existingUser = $userModel->where('Email', $email)->first();
        if($existingUser!==null){
            return $this->respond(['Email' => $existingUser['Email']]);

        }else{
            return $this->response->setStatusCode(400)->setJSON(['message' => "Invaild EmailId"]);

        }
    }  // ================ IS EMAILVALIDATION ==============
    public function isUpdatedEmailPassword()
    {
        $json = $this->request->getJSON();
        $con = [
            'Email'=> $json->Email,
            'Password'=>$json->Password
        ];
        $userModel = new CustomersModel();
        $existingUser = $userModel->where('Email', $con['Email'])->first();
        if($existingUser!==null){
          $existingUser['Password'] = $con['Password'];
            // Save the changes
            $userModel->save($existingUser);
            return $this->respond(['message' => 'Email updated successfully']);
        }else{
            return $this->response->setStatusCode(400)->setJSON(['message' => "Invaild EmailId"]);

        }
    }  // ============== ISUPDATEDeMAILPASSWORD ===========

  

    public function forgetPassword()
    {
        $json = $this->request->getJSON();

        $model = new UsersModel();
        $con = [
            'email' => $json->email
        ];
        $user = $model->where($con)->first();
        
        if ($user) {
            $model->set(['password' => password_hash($json->password,PASSWORD_BCRYPT)])->where($con)->update();
            $message = [
                'message' => 'password updated',
                'status' => 200
            ];
            return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);
        } else {
            $message = [
                'message' => 'Invalid Email',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }

    public function changePassword()
    {
        $json = $this->request->getJSON();

        $model = new UsersModel();
        $con = [
            'id' => $json->user_id,
        ];
        $user = $model->where($con)->first();
        if ($user) {
            $con = [
                'id' => $json->user_id
            ];
            $user = $model->where($con)->first();

            if (password_verify($json->old_password, $user['password'])) {
                $con = [
                    'id' => $json->user_id
                ];
                $model->set(['password' =>password_hash($json->new_password, PASSWORD_BCRYPT)])->where('id',$user['id'])->update();
                $message = [
                    'message' => 'Password changed successfully',
                    'status' => 200
                ];
                return $this->response->setStatusCode(200)->setJSON(['messageobject' => $message]);

            } else {
                $message = [
                    'message' => 'Wrong Password',
                    'status' => 400
                ];
                return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
            }

        } else {
            $message = [
                'message' => 'Invalid User',
                'status' => 400
            ];
            return $this->response->setStatusCode(400)->setJSON(['messageobject' => $message]);
        }
    }


    public function updateUserProfile()
    {
        $json = $this->request->getJSON();
    
       
        $userId = $json->id;
    

        if (!is_numeric($userId)) {
            return $this->fail('Invalid user id');
        }
   
        $userData = [
            'name' => $json->name ?? null,
            'email' => $json->email ?? null,
            'phone' => $json->phone ?? null,
        ];
    
       
        if (empty($userData['name']) && empty($userData['email']) && empty($userData['phone'])) {
            return $this->fail('No data to update');
        }
    
 
        $model = new UsersModel();
    
        
        $user = $model->find($userId);
        if (!$user) {
            return $this->fail('User not found', 404);
        }
    

        $updatedFields = [];
        foreach ($userData as $key => $value) {
            if (!is_null($value)) {
                $user->{$key} = $value;
                $updatedFields[] = $key;
            }
        }
    

        if (!$model->save($user)) {
            return $this->fail('Failed to update user profile');
        }
    
      
        return $this->respond([
            'message' => 'User profile updated successfully',
            'updated_fields' => $updatedFields,
        ]);
    }
    
}


    

