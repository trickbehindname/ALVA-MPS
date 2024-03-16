<?php

namespace App\Models;

use CodeIgniter\Model;

class MstprodModel extends Model
{

    protected $table      = 'master_product';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'PRODUCT_ID';
	protected $returnType = 'object';
	protected $allowedFields = ['PRODUCT_NUMBER','PRODUCT_DESCRIPTION','PRODUCT_COLOR','PRODUCT_MODEL','PRODUCT_SIZE'] ;

    // protected $DBGroup          = 'default';
    // protected $table            = 'mstprods';
    // protected $primaryKey       = 'id';
    // protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    // protected $allowedFields    = [];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];
}
