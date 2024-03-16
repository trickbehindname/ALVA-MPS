<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MstmdlModel;

class Mstmdls extends BaseController
{
    protected $helper   =['custom'];

    function __construct()
    {
        $this -> mstmdl  = new MstmdlModel();
    }

    public function index()
    {
        //
    }
}
