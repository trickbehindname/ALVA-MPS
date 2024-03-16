<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\MstprodModel;

class Mstprods extends BaseController
{
    protected $helper   =['custom'];

    function __construct()
    {
        $this -> mstprod  = new MstprodModel();
    }

    public function index()
    {
        //
    }
}
