<?php


namespace App\Controllers\Admin;

use App\Models\Admin\CounterNumberModel;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class CounterNumberController extends Controller
{
    use ResponseTrait;

    protected $counternumberModel;
  

    public function __construct()
    {
        $this->counternumberModel = new CounterNumberModel();
    
    }

    public function getCounterNumber()
    {
        $counter = $this->counternumberModel->findAll();
        return $this->respond($counter);
    }


}