<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Helpers\TwilioHelper;

date_default_timezone_set('Asia/Kolkata');


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */

    public $Authkey = '';
    protected $helpers = [];
    protected $uri;
    protected $BaseModel;
    protected $SignupModel;
    protected $CategoryModel;
    protected $LoginHistoryModel;
    protected $JpSignupModel;
    protected $JobDetailsModel;
    protected $authheader;
    protected $userheader;
    protected $TwilioService;
    protected $UserDataModel;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */





    public function __construct()
    {
        $this->BaseModel = new \App\Models\BaseModel();
     

        // $this->TwilioService = new \App\Libraries\TwilioService();
        $this->request = \Config\Services::request();

        helper(['api']);
        helper(['request']);
        helper(['date']);

        $response = service('response');
        $request = service('request');

        // $headers = $request->getHeader('Authkey');
        // $response->setHeader('Authkey', $headers);
        // print_r($headers );
        $router = service('router');

    }




    public function authkey_setting()
    {
        $response = service('response');
        $request = service('request');
        $authheader = $request->getHeader('Authkey');
        $userheader = $request->getHeader('Userservice');
        $headers = $this->request->headers();
        array_walk($headers, function (&$value, $key) {
            $value = $value->getValue();
        });


        if ($authheader == "" || $userheader == "") {
            $status = "Authentication Failure";
            return $status;
        } else {
            if ($headers['Userservice'] == 'jSuser' && $headers['Authkey'] == 'jobseeker') {
                $status = "1";
                $this->authheader = $headers['Userservice'];
                $this->userheader = $headers['Authkey'];
                return $status;
            } elseif ($headers['Userservice'] == 'jPuser' && $headers['Authkey'] == 'jobprovider') {
                $status = "1";
                $this->authheader = $headers['Userservice'];
                $this->userheader = $headers['Authkey'];
                return $status;
            } else {
                $status = "0";
                return $status;
            }
        }
    }



    public function UUID4()
    {
        $data = isset($data) ?? random_bytes(16);
        assert(strlen($data) == 16);
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    public function get_rand_number($length = 6)
    {
        return substr(str_shuffle("0123456789"), 0, $length);
    }


    public function get_api_error($status_code = NULL, $status_message = NULL, $show = FALSE)
    {
        if ($status_message == NULL) {
            switch ($status_code) {
                case '400':
                    $message = 'Some informations missing. Please try again later.';
                    break;
                case '401':
                    $message = 'Something went wrong. Please try again later.';
                    break;
                case '402':
                    $message = 'Authentication Failure.';
                    break;
                case '403':
                    $message = 'Authentication Missing.';
                    break;
                case '404':
                    $message = 'Unknown Method.';
                    break;
                case '405':
                    $message = 'Account modified, Login again.';
                    break;
                case '406':
                    $message = 'Account was used in another device.';
                    break;
                case '407':
                    $message = 'Server couldnot process this request.';
                    break;
            }
        } else {
            $message = $status_message;
        }
        if ($show) {
            $response = service('response');
            $response->setStatusCode($status_code)->setBody(json_encode(['error' => $message]))->setHeader('Content-type', 'application/json')->noCache()->send();
            exit();
        }
        return $message;
    }


    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }






}
