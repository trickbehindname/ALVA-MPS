<?php

namespace App\Models;

use CodeIgniter\Model;

class OInventoryModel extends Model

{
    protected $DBGroup          = 'lcl';
    protected $table            = 'o_inventory';
    protected $primaryKey       = 'inv_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ITEM_ID',	'ITEM_LPN','IN_QTY',	'IN_IU_ID',	'INV_QTY',	'NEW_QTY',	'TRANS_QTY',	
                                    'BOOKED_QTY',	'OUT_QTY',	'IU_ID',	'LOC_ID',	'LPN_ID',	'PALLET_ID',	'CONT_HEADER_ID',
									'INV_TIMESTAMP',	'PUT_TIMESTAMP',	'ITEM_BATCH_NUMBER',	'ITEM_EXPIRY_DATE',	'ITEM_SERIAL_NUMBER_1',	
									'ITEM_SERIAL_NUMBER_2',	'ITEM_USER_DEF1',	'ITEM_USER_DEF2',	'ITEM_USER_DEF3',	'ITEM_USER_DEF4',	'ITEM_USER_DEF5',	
									'PICKING_TIMESTAMP',	'FINAL_LOC',	'INV_TYPE',	'INV_CC_STATUS',	'RETURN_TIMESTAMP',	'DOCUMENT_NUMBER',	
									'ITEM_QUALITY_ID',	'INV_PREDECESSOR',	'PROD_SCHED_NO',	'LOT_NUMBER',	'IN_WEIGHT','OUT_WEIGHT','LAST_MOVEMENT',
									'IS_QC','INV_COMMENT'];

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
