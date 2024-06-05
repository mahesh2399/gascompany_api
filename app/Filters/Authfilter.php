<?php
  
namespace App\Filters;
  
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;
  
class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
       
		$config = config('Jwt');
        $key = $config->authKey;
        $header = $request->getServer('HTTP_AUTHORIZATION');
        $userAuthKey = $request->getServer('HTTP_AUTHKEY');
        $authKey = getenv('AUTHKEY');
        if(!$header || $userAuthKey != $authKey) return Services::response()
                            ->setJSON(['msg' => 'Unauthorized'])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        $token = explode(' ', $header)[1];
        try {
            $decodedToken =  JWT::decode($token, new Key($key, $config->method));
        } catch (\Throwable $th) { 
            return Services::response()
                            ->setJSON(['msg' => 'Unauthorized'])
                            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
        $request->user = $decodedToken;
        return $request;
    }
  
    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}