<?php

namespace App\Models;

use CodeIgniter\Model;

class OpPrdPlanHdrModel extends Model
{
    protected $DBGroup          = 'lcl';
    protected $table            = 'operation_production_plan_header';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id','quantity','production_sequence','production_date','planning_date','jph','vin','model_id','varian_id','timestamp','status'];

    // Dates
    // protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'timestamp';
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
