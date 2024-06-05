<?php
namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
class S3Service
{
    public $s3Service;

    public function __construct()
    {
        $config = config('S3');
        $credentials = new Credentials($config->S3_ACCESS_KEY, $config->S3_SECRET_KEY);   // local Usages
        $s3 = new S3Client([
            'region' => $config->S3_REGION,
            'version' => 'latest',
            'credentials' => $credentials
        ]);    
    
    }




    public function commonfileupload($folder,$file,$id){
        $config = config('S3');
          $awsResult['status']="0";
          if($file->isValid()){
                    $credentials = new Credentials($config->S3_ACCESS_KEY, $config->S3_SECRET_KEY);   // local Usages
                    $s3 = new S3Client([
                    'region' => $config->S3_REGION,
                    'version' => 'latest',
                    'credentials' => $credentials
                    ]);
                    $bucket = $config->S3_BUCKET_NAME;
                    $fileName= $id;
                    $key =$folder."/".$fileName;
                    try {
                        $result = $s3->putObject([
                            'Bucket' => $bucket,
                            'Key'    => $key,
                            'SourceFile'=>$file,
                            
                        ]);
                        $awsResult['status']="1";
                        $awsResult['data']="https://bucket.s3.".$config->S3_REGION.".amazonaws.com/".$folder."/".$fileName;
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
          }else{
            $awsResult['status']="0";
            $awsResult['data']= 'Invalid file';
          } 
          return $awsResult;

    }
 }

