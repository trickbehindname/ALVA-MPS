<?php

namespace App\Models;

use CodeIgniter\Model;

class MstclrModel extends Model
{
    protected $table      = 'master_color';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'COLOR_ID';
	protected $returnType = 'object';
	protected $allowedFields = ['COLOR_NAME','COLOR_DESCRIPTION','COLOR_TYPE','COLOR_VERSION','COLOR_REVISION'] ;

    // protected $DBGroup          = 'default';
    // protected $table            = 'mstclrs';
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
