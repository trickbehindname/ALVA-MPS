<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Models\MstmdlModel;
use App\Models\MstclrModel;
use App\Models\MstprodModel;
use App\Models\NewsModel;
use App\Models\OpPrdPlanHdrModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Database\RawSql;

class Mps extends BaseController
{
    protected $helpers = ['custom'];

    public function __construct()
    {
        $this -> mstmdl  = new MstmdlModel();
        $this -> mstclr  = new MstclrModel();
        $this -> mstprod  = new MstprodModel();
        $this -> newss  = new NewsModel();
        $this  -> OpPrdPlanHdr = new OpPrdPlanHdrModel();
    }

    public function index()
    {
        helper('form');
        $data['mstmdls'] = $this -> mstmdl -> findAll();
        $data['mstclrs'] = $this -> mstclr -> findAll();
        $data['mstprods'] = $this -> mstprod -> findAll();
        $data['OpPrdPlanHdrs'] = $this -> OpPrdPlanHdr -> findAll();

        $data['title'] = 'MPS';

        return view('templates/header', $data)
        . view('mps/create')
        . view('templates/footer');
    }

    public function create()
    {
        helper('form');
        helper('date');
        $data['mstmdls'] = $this -> mstmdl -> findAll();
        $data['mstclrs'] = $this -> mstclr -> findAll();
        $data['mstprods'] = $this -> mstprod -> findAll();
        $data['OpPrdPlanHdrs'] = $this -> OpPrdPlanHdr -> findAll();

        $db      = \Config\Database::connect('lcl');
        $builder = $db->table('operation_production_plan_header');

        $model = model(OpPrdPlanHdrModel::class);

        $data = $this->request->getPost(['model','color','product','txtd[]','tday']);
        $tday = explode(' ', $data['tday']);

        // print_r($data['color']);
        // die;
        $mth = (int)$tday[0];
        $yr = (int)$tday[1];
        $txtd = $data['txtd[]'];

        //check if data already exists for this month and year
        //if not exists then create new sequence
        //if already exists, get sequence and check for update or add new sequence

        $db      = \Config\Database::connect('lcl');
        $builder = $db->table('operation_production_plan_header');

        $sqls = 'SELECT month(production_date) as prdmonth, 
			year(production_date) as prdyear, max(production_sequence) as maxseq
            from operation_production_plan_header where 
			month(production_date) = '. $mth.' and 
			year(production_date) = '. $yr. '
			GROUP by prdmonth, prdyear';

			// -- model_id = ' . $data['model'].'and 
			// -- color_id = '. $data['color'].'and 
			// -- product_id = '. $data['product'] . 'and 
		//print_r($sqls);
		//die;
        $query = $db->query($sqls);

        $tmp = $query->getResultArray();
// print_r($tmp);
// die;

        $upd = 0;
        $maxseq = 0;

        if(count($tmp) > 0) {
            $maxseq = intval($tmp[0]['maxseq']);
            //if exists, check model, color, variant, product id. if diff, -> new plan, if not -> update plan
            $builder -> select('model_id, color_id, varian_id, product_id');
            $builder -> where('model_id', $data['model']);
            $builder -> where('color_id', $data['color']);
            $builder -> where('product_id', $data['product']);
            $builder -> where('month(production_date)', $mth);
            $builder -> where('year(production_date)', $yr);
            $check = $builder->get()->getResultArray();

            if (count($check) > 0) {
                $upd = 1;//= intval($check['production_sequence']);
            } else {
                $upd = 0;
            }



        } else {
            $maxseq = 0;
        }

        //if maxseq = 0 or not update -> create new sequence
        if(($maxseq == 0) || $upd == 0) {
            $maxseq = $maxseq + 1;

            for($i = 0;$i < count($txtd);$i++) {
                $dt = $i + 1;
                $prdd = date('Y-m-d', mktime(0, 0, 0, $mth, $dt, $yr));//date_format(mktime(0,0,0,$mth,$dt,$yr),"YYYY-MM-DD");
                $pld = date('Y-m-d');
                $clr = $data['color'];



                $model->save([
                    'model_id' => $data['model'],
                    'color_id' => $clr,
                    'product_id' => $data['product'],
                    'quantity' => $txtd[$i],
                    'production_date' => date("Y-m-d", mktime(0, 0, 0, $mth, $dt, $yr)),
                    'planning_date' => $pld,//date("Y-m-d")
                    'production_sequence' => $maxseq
                ]);
                //print_r($pld);
            }

        } else { //else (maxseq>0 or upd>0)-> update

            $builder ->select('opph_id,production_date,production_sequence');
            $builder -> where('model_id', $data['model']);
            $builder -> where('color_id', $data['color']);
            $builder -> where('product_id', $data['product']);
            $builder -> where('month(production_date)', $mth);
            $builder -> where('year(production_date)', $yr);
            //if($maxseq > 0){
            $builder -> where('production_sequence', $maxseq);
            //}

            $check = $builder->get()->getResultArray();

            //if (count($check) > 0) {
            //data exists, update

            //$dtins[];

            foreach($check as $row) {
                $opph_id = $row['opph_id'];
                $production_date = $row['production_date'];
                $i = intval(substr($production_date, -2) - 1);
                $qty = $txtd[$i];
                $dtins = [
                    'quantity' => $qty
                ];

                //print_r($opph_id." ".$qty." ". $production_date."\n");

                $model->update($opph_id, $dtins);
            }

            //};

        }

        // $builder ->select('opph_id,production_date,production_sequence');
        // $builder -> where('model_id', $data['model']);
        // $builder -> where('color_id', $data['color']);
        // $builder -> where('product_id', $data['product']);
        // $builder -> where('month(production_date)', $mth);
        // $builder -> where('year(production_date)', $yr);
        // if($maxseq > 0){
        // 	$builder -> where('production_sequence', $yr);
        // }

        // $check = $builder->get()->getResultArray();

        // if (count($check) > 0) {
        //     //data exists, update

        //     //$dtins[];

        //     foreach($check as $row) {
        //         $opph_id = $row['opph_id'];
        //         $production_date = $row['production_date'];
        //         $i=intval(substr($production_date,-2)-1);
        //         $qty= $txtd[$i];
        //         $dtins = [
        //             'quantity' => $qty
        //         ];

        //         print_r($opph_id." ".$qty." ". $production_date."\n");

        //        $model->update($opph_id, $dtins);
        //     }

        // } else {
        //     //data does not exist, insert
        //     for($i = 0;$i < count($txtd);$i++) {
        //         $dt = $i + 1;
        //         $prdd = date('Y-m-d', mktime(0, 0, 0, $mth, $dt, $yr));//date_format(mktime(0,0,0,$mth,$dt,$yr),"YYYY-MM-DD");
        //         $pld = date('Y-m-d');
        //         $clr= $data['color'];



        //         $model->save([
        //             'model_id' => $data['model'],
        //             'color_id' => $clr,
        //             'product_id' => $data['product'],
        //             'quantity' => $txtd[$i],
        //             'production_date' => date("Y-m-d", mktime(0, 0, 0, $mth, $dt, $yr)),
        //             'planning_date' => $pld//date("Y-m-d")
        //         ]);
        //         //print_r($pld);
        //     }

        // }




        $data['title'] = 'Simulation';
        return view('templates/header', $data)
        . view('mps/simulation', $data)
        . view('templates/footer');

    }

    // public function view($page='home')
    // {
    //     if (! is_file(APPPATH . 'Views/mps/' . $page . '.php')) {
    //         // Whoops, we don't have a page for that!
    //         throw new PageNotFoundException($page);
    //     }

    //     $data['title'] = ucfirst($page); // Capitalize the first letter

    //     // $db = \Config\Database::connect('lcl');

    //     // $db->close();

    //     // $model = model(NewsModel::class);

    //     // $data = [
    //     //     'news' => $model->getNews(),
    //     //     'title'=> 'News Archive',
    //     // ];// = $model->getNews();


    //     // $data['news'] = $this -> newss -> findAll();

    //     // print_r($data);
    //     // die;

    //     return view('templates/header', $data)
    //         . view('mps/' . $page)
    //         . view('templates/footer');
    // }

    public function loadsimulation($tday = null)
    {
        // if ($tday == null) {
        //     $tday = date('Y-m-d');
        // }

        $data = $this->request->getPost(['tday']);

        $mmyy = explode(' ', $data['tday']);
        if (count($mmyy) < 2) {

            $mmyy[1] = "";
        }


        helper('form');
        helper('date');
        $data['title'] = 'SIMULATION'; // Capitalize the first letter

        $data['mstmdls'] = $this -> mstmdl -> findAll();
        $data['mstclrs'] = $this -> mstclr -> findAll();
        $data['mstprods'] = $this -> mstprod -> findAll();
        $data['OpPrdPlanHdrs'] = $this -> OpPrdPlanHdr
            ->where('Month(production_date)', '5')
            ->where('Year(production_date)', '2024')
            ->findAll();

        $db      = \Config\Database::connect('lcl');
        $builder = $db->table('operation_production_plan_header');

        $builder->select('product_id,color_id,varian_id,model_id');
        $builder->distinct();
        $builder->where('Month(production_date)', $mmyy[0]);
        $builder->where('Year(production_date)', $mmyy[1]);
        $query = $builder->get();
        $data['products'] = $query->getResultArray();

        for($i = 0;$i < count($data['products']);$i++) {
            //ambil row dari tabel operation_production_plan_header
            //yang punya product-color-varian-bulan produksi yang sama

            $builder->select('day(production_date) as tgl,quantity');//,planning_date,production_sequence,JPH,VIN,status');
            $builder->where('product_id', $data['products'][$i]['product_id']);
            $builder->where('color_id', $data['products'][$i]['color_id']);
            $builder->where('varian_id', $data['products'][$i]['varian_id']);
            $builder->where('model_id', $data['products'][$i]['model_id']);
            $builder->where('Month(production_date)', $mmyy[0]);
            $builder->where('Year(production_date)', $mmyy[1]);
            $query = $builder->get();
            $dt = [];
            $dt = $query->getResultArray();


            $data['products'][$i]['prd'] = $dt;
            $d = [];

            //print_r(count($dt));
            foreach($dt as $key => $value) {
                $d = array_merge($d, [$value['tgl'] => $value['quantity']]);
                //$d[]=array($value['tgl']=>$value['quantity']);
            }
            //print_r($d);
            // print_r('---');

            // $test = array(
            //     "dog" => "cat",
            //     "anjing" => "kucing"
            // );
            // //$test["anjing"]=["kucing"];
            // //array_push($test,$testpush);
            $data['products'][$i]['prd'] = $d;

            // print_r($d);
            // print_r('---');

        }

        //  print_r($data['products'][0]);



        $db->close();

        //echo json_encode($data);
        $result = array("results" => $data);

        header("Content-Type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);


        // return view('templates/header', $data)
        //     . view('mps/simulation', $data)
        //     . view('templates/footer');
    }

    public function simulation($tday = null)
    {
        helper('form');
        $data['title'] = 'SIMULATION'; // Capitalize the first letter
        return view('templates/header', $data)
        . view('mps/simulation', $data)
        . view('templates/footer');
    }


    public function loadsimresult($tday = null)
    {
        helper('form');
        $data = $this->request->getPost(['tday']);
        // $mmyy = explode(' ', $data['tday']);
        // if (count($mmyy) < 2) {
        //     $mmyy[1] = "";
        // }


        $data = $this->processsimresult($data);

        $data['title'] = 'SIMULATION RESULLT'; // Capitalize the first letter

        //print_r($data);
        // die;
        $result = array("results" => $data);

        header("Content-Type: application/json");
        echo json_encode($result, JSON_PRETTY_PRINT);

        // return view('templates/header', $data)
        // . view('mps/simresult', $data)
        // . view('templates/footer');
    }

    public function simresult($tday = null)
    {
        // Capitalize the first letter

        //helper('form');
        $data = $this->request->getPost(['tday']);

        $data = $this->processsimresult($data);

        //print_r($data);
        $data['title'] = 'SIMULATION RESULT';

        //$result = array("results" => $data);

        return view('templates/header', $data)
        . view('mps/simresult', $data)
        . view('templates/footer');
    }

    public function processsimresult($data)
    {


       //need to delete the data for this month first. 
        
        $mmyy = explode(' ', $data['tday']);
        if (count($mmyy) < 2) {

            $mmyy[1] = "";
        }

        $db      = \Config\Database::connect('lcl');
        


        //check sim header, if exist, delete first.
        $builder = $db->table('operation_simulation_header osh');

        $builder->select('count(*)')
                ->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id')
                ->where('Month(production_date)', $mmyy[0])
                ->where('Year(production_date)', $mmyy[1]);

        $query = $builder->get();
        $chksh = $query->getResult();
        if (count($chksh) > 0) {
            $query = $db->table('operation_simulation_header osh')
                        ->select('osh_id')
                        ->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id')
                        ->where('Month(production_date)', $mmyy[0])
                        ->where('Year(production_date)', $mmyy[1]);

            $sql = $db->table('operation_simulation_details')
                ->setQueryAsData($query,'opsh')
                ->onConstraint('osh_id')
                ->where('operation_simulation_details.osh_id = opsh.osh_id')
                ->deleteBatch();

            $query = $db->table('operation_production_plan_header opph')
                ->select('opph_id,product_id')
                //->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id')
                ->where('Month(production_date)', $mmyy[0])
                ->where('Year(production_date)', $mmyy[1]);
            
            $sql = $db->table('operation_simulation_header')
                ->setQueryAsData($query,'opph')
                ->onConstraint('opph_id')
                ->where('operation_simulation_header.opph_id = opph.opph_id')
                ->where('operation_simulation_header.product_id = opph.product_id')
                ->deleteBatch();
            $query2 = $db->getLastQuery();
               // die;
        }

        
        //load plan header to simulation header
        //load all required qty to simulation details
        //then process simulation per item id ORDER BY production_sequence


        //turned off for testing purpose
        //INSERT INTO operation_simulation_header(opph_id,product_id)
        $builder = $db->table('operation_simulation_header');
        $query = 'SELECT opph_id,product_id 
            FROM operation_production_plan_header opph
            WHERE MONTH(opph.production_date)='.$mmyy[0].' AND 
            YEAR(opph.production_date)='. $mmyy[1];


        $sql = $builder->ignore(true)
        ->setQueryAsData(new RawSql($query), null, 'opph_id, product_id')
        ->insertBatch();

        //end turned off for testing purpose
        //die;


        //insert sim detail
        $builder = $db->table('operation_simulation_header osh');
        $builder->select('osh_id,osh.opph_id,osh.product_id');
        $builder->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id');
        $builder->where('month(opph.production_date)',$mmyy[0]);
        $builder->where('year(opph.production_date)',$mmyy[1]);
        // =373 and opph.product_id = 1');
        // $query = $builder->get();

        // $sqls = 'SELECT osh_id,osh.opph_id,osh.product_id 
        //     from operation_simulation_header osh
        //     inner join operation_production_plan_header opph
        //     on osh.opph_id = opph.opph_id and osh.product_id = opph.product_id
        //     where 
		// 	month(opph.production_date) = '.  $mmyy[0].' and 
		// 	year(opph.production_date) = '.  $mmyy[1]. '';
        $query = $builder->get();
        $simheader = $query->getResultArray();

        // turned off for testing purpose
        //foreach simheader, insert simdetails required qty
        foreach ($simheader as $row) {
            $osh_id = $row['osh_id'];
            $builder = $db->table('operation_simulation_details');
            $query  = 'SELECT osh.osh_id, mbm.BOM_ITEM_ID as item_id,(mbm.BOM_ITEM_QTY * opph.quantity) as req_qty
                FROM master_bom mbm
                INNER JOIN operation_simulation_header osh ON 
                mbm.BOM_PRODUCT_ID=osh.product_id
                INNER JOIN operation_production_plan_header opph ON 
                osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id 
                WHERE osh.osh_id='. $osh_id;
            $sql = $builder->ignore(true)
            ->setQueryAsData(new RawSql($query), null, 'osh_id,item_id, req_qty')
            ->insertBatch();
            $query2 = $db->getLastQuery();
        }
        // end turned off for testing purpose


        //foreach item order by production_sequence and then order by prod_date
        $builder = $db->table('operation_production_plan_header opph');
        $builder->select('*');
        $builder->orderBy('opph.production_date, opph.production_sequence');
        // $sqls = 'SELECT * FROM operation_production_plan_header opph
        //     ORDER BY opph.production_date, opph.production_sequence';
        $query = $builder->get();
        $opph = $query->getResultArray();

        foreach ($opph as $row) {
            $builder = $db->table('operation_simulation_details osd');
            $builder->select('osd.*,opph.production_sequence as production_sequence, opph.production_date as production_date,osh.osh_id as osh_id');
            $builder->join('operation_simulation_header osh', 'osd.osh_id=osh.osh_id');
            $builder->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id');
            $builder->where('opph.opph_id',$row['opph_id']);
            $builder->where('opph.product_id',$row['product_id']);
            
            //$oshsimresult=0;

            $query = $builder->get();
            $osd = $query->getResultArray();
            //check sim table if not exist, get from o_inventory ORDER BY 
            //PER item
            foreach($osd as $osdrow) {

                //if sequence = 1 -> get LAST DAY AVAILABLE QTY
                //if sequence > 1 -> get TODAY SEQUENCE AVAILABLE QTY

                $builder = $db->table('operation_simulation_details osd');
                $builder->select('osd.end_bal_qty as avail_qty');
                $builder->join('operation_simulation_header osh', 'osd.osh_id=osh.osh_id');
                $builder->join('operation_production_plan_header opph', 'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id');
                $builder->where('osd.item_id',$osdrow['item_id']);
                if ($osdrow['production_sequence'] == 1) {
                    $builder->where('opph.production_date = DATE_SUB("'.$osdrow['production_date'].'", INTERVAL 1 DAY)');//if seq = 1
                }
                else {
                    $builder->where('opph.production_date = "'.$osdrow['production_date'].'"');//if seq > 1
                    $builder->where('opph.production_sequence ',($osdrow['production_sequence']-1));//if seq > 1
                }

                $builder->orderBy('opph.production_date DESC, opph.production_sequence DESC');
                $query = $builder->get();
                $avail = $query->getResultArray();

                $sql = $db->getLastQuery();
                
                $dtpart = explode('-', $osdrow['production_date']);

                if(((count($avail) == 0) || ($avail[0]['avail_qty'] == 0)) && 
                    (intval($dtpart[2])==1 && $osdrow['production_sequence'] == 1)) 
                {
                    //get from o_inventory, if 1st day of month and seq = 1
                    $builder = $db->table('o_inventory oi');
                    $builder->select('oi.item_id, sum(oi.INV_QTY) AS avail_qty');
                    $builder->where('oi.ITEM_ID',$osdrow['item_id']);
                    $builder->groupBy('oi.item_id');
                    $query = $builder->get();
                    $avail = $query->getResultArray();
                    if((count($avail) == 0) || ($avail[0]['avail_qty'] == 0)) {
                        $avail_qty = 0;
                    }else
                    {
                        $avail_qty= $avail[0]['avail_qty'];
                    }
                }else
                {
                    $avail_qty= $avail[0]['avail_qty'];
                }

                //get bookd qty
                $builder = $db->table('operation_simulation_details osd');
                $builder->select('osd.item_id,sum(osd.req_qty) as booked_qty');
                $builder->join('operation_simulation_header osh', 'osd.osh_id=osh.osh_id');
                $builder->join('operation_production_plan_header opph', 
                    'osh.opph_id = opph.opph_id AND osh.product_id = opph.product_id');
                $builder->where('osd.osh_id != ',$osdrow['osh_id']);
                $builder->where('osd.item_id = ',$osdrow['item_id']);
                $builder->where('opph.production_date',$osdrow['production_date']);
                $builder->where('opph.production_sequence < ',$osdrow['production_sequence']);
                $builder->groupby('osd.item_id');
                $query = $builder->get();
                $booked = $query->getResultArray();

                $sql = $db->getLastQuery();

                if((count($booked) == 0) || ($booked[0]['booked_qty'] == 0)) {
                    $booked_qty = 0;
                } else {
                    $booked_qty = $booked[0]['booked_qty'];
                }

                
                //after get avail_qty,booked_qty calculate end_bal_qty then update into sim_details
                $end_bal_qty = $avail_qty //- $booked_qty 
                    - $osdrow['req_qty'];
                $dtins=[];

                if($end_bal_qty<0){
                    //not enough qty available (set end_bal_qty to 0?)
                    $dtins =[
                        'avail_qty' => $avail_qty,
                        'booked_qty' => $booked_qty,
                        'end_bal_qty' => 0,
                    ];
                }
                else{
                    //enough qty, update into sim_details
                    $dtins =[
                        'avail_qty' => $avail_qty,
                        'booked_qty' => $booked_qty,
                        'end_bal_qty' => $end_bal_qty,
                    ];

                
                }
                $osd_id = $osdrow['osd_id'];
                $builder = $db->table('operation_simulation_details osd');
                $builder->where('osd.osd_id', $osd_id);
                $builder->update($dtins);

            
            }

            //after simdetail, update simheader, check result
            //find osh id where end_bal_qty < 0 then get the prod date.
            $builder = $db->table('operation_simulation_details osd');
            $builder->select('osd.item_id,(osd.avail_qty-osd.req_qty) as calc_end, 
                osd.req_qty,osd.avail_qty,osd.booked_qty, osd.end_bal_qty')
            ->join('operation_simulation_header osh', 'osd.osh_id=osh.osh_id')
            ->where('osh.opph_id',$row['opph_id'])
            ->where('osh.product_id',$row['product_id'])
            ->where('((osd.avail_qty-osd.req_qty) < 0 or osd.avail_qty <=0)');
            $query = $builder->get();
            $oshsimsresult = $query->getResultArray();
            $sql = $db->getLastQuery();
            $dtins = [];

            if (count($oshsimsresult) > 0) {
                // there's shortage, simresult = 0
                $dtins =[
                    'simulation_result' => 0,
                 ];
            }
            else{
                // there's no shortage, simresult = 1
               
                //$osh_id = $oshsimsresult[0]['osd_id'];
                $dtins =[
                   'simulation_result' => 1,
                ];
                
               

            }
            $builder = $db->table('operation_simulation_header osh');
            $builder->where('osh.opph_id', $row['opph_id']);
            $builder->where('osh.product_id', $row['product_id']);
            $builder->update($dtins);
            

            
            

            

        }

        //die;

        //example : starting from now(march 2024)
        //  $mmyy[0]=3;//month
        //  $mmyy[1]=2024;//year

        //get this month simulation data
        $builder = $db->table('operation_production_plan_header opph');
        $builder->select('product_id,color_id,varian_id,model_id,production_sequence');
        $builder->distinct();
        $builder->where('Month(production_date)', $mmyy[0]);
        $builder->where('Year(production_date)', $mmyy[1]);
        $query = $builder->get();
        $data['products'] = $query->getResultArray();

        for($i = 0;$i < count($data['products']);$i++) {
            //ambil row dari tabel operation_production_plan_header
            //yang punya product-color-varian-bulan produksi yang sama

            $builder->select('day(production_date) as tgl,quantity,simulation_result')    //,planning_date,production_sequence,JPH,VIN,status');
                ->join('operation_simulation_header osh',
                    'osh.opph_id = opph.opph_id and osh.product_id = opph.product_id')
                ->where('opph.product_id', $data['products'][$i]['product_id'])
                ->where('color_id', $data['products'][$i]['color_id'])
                ->where('varian_id', $data['products'][$i]['varian_id'])
                ->where('model_id', $data['products'][$i]['model_id'])
                ->where('Month(production_date)', $mmyy[0])
                ->where('Year(production_date)', $mmyy[1]);
            $query = $builder->get();
            $dt = [];
            $dt = $query->getResultArray();


            $data['products'][$i]['prd'] = $dt;
            $d = [];

            //print_r(count($dt));
            $maxproddate=0;

            foreach($dt as $key => $value) {
                $d = array_merge($d, [
                    $value['tgl'] => $value['quantity']                    
                ]);
                $maxproddate=$maxproddate+intval($value['simulation_result']);

            }


            $data['products'][$i]['prd'] = $d;
            $data['products'][$i]['maxproddate'] = $maxproddate;
        };

        // for($i = 0;$i < count($data['products']);$i++) 
        // {
        //     $x=0;
        //     foreach($data['products'][$i]['prd'] as $rowPrd)
        //     {
        //         $x++;
        //         if($rowPrd['sim_result']==0){
        //             $maxproddate = $x;
        //             break;
        //         }
                
        //     }
        // }
        //die;

        //max production date for this product(should change to dynamic)
        //$data['products'][0]['maxproddate'] = $i;




        $db->close();

        // $prodqty = 0;
        // //set maximum date for production(don't count if no prod this month)
        // for($i = 0;$i < count($data['products'][0]['prd']);$i++) {
        //     $prodqty = $prodqty + $data['products'][0]['prd'][$i];
        //     //print_r($maxprodqty);

        //     if($prodqty == $maxprodqty) {
        //         $i = $i + 1;
        //         break;
        //     }
        //     if($prodqty >= $maxprodqty) {

        //         break;

        //     }

        // };





        //$result = array("results" => $data);

        return $data;

    }

    public function processsimulation()
    {

        // $tday = $_POST['tday'];
        $data = $this->request->getPost(['tday']);

        if($data['tday'] != '') {

            $mmyy = explode(' ', $data['tday']);
            if (count($mmyy) < 2) {

                $mmyy[1] = "";
            }



        }

        $data['title'] = 'Process'; // Capitalize the first letter
        return view('templates/header', $data)
        . view('mps/simresult', $data)
        . view('templates/footer');
    }
}
