<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
    public function index(): string
    {
        return view('welcome_message');
    }

   
}
