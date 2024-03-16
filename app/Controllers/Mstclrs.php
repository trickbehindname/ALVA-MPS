<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\MstclrModel;

class Mstclrs extends BaseController
{
    protected $helper   =['custom'];

    function __construct()
    {
        $this -> mstclr  = new MstclrModel();
    }

    public function index()
    {
        //
    }
}
