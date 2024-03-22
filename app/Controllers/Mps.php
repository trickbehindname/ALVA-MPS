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
        $builder ->select('opph_id,production_date');
        $builder -> where('model_id', $data['model']);
        $builder -> where('color_id', $data['color']);
        $builder -> where('product_id', $data['product']);
        $builder -> where('month(production_date)', $mth);
        $builder -> where('year(production_date)', $yr);

        $check = $builder->get()->getResultArray();

        if (count($check) > 0) {
            //data exists, update

            //$dtins[];

            foreach($check as $row) {
                $opph_id = $row['opph_id'];
                $production_date = $row['production_date'];
                $i=intval(substr($production_date,-2)-1);
                $qty= $txtd[$i];
                $dtins = [
                    'quantity' => $qty
                ];
                
                print_r($opph_id." ".$qty." ". $production_date."\n");
                
               $model->update($opph_id, $dtins);
            }
    
        } else {
            //data does not exist, insert
            for($i = 0;$i < count($txtd);$i++) {
                $dt = $i + 1;
                $prdd = date('Y-m-d', mktime(0, 0, 0, $mth, $dt, $yr));//date_format(mktime(0,0,0,$mth,$dt,$yr),"YYYY-MM-DD");
                $pld = date('Y-m-d');
                $clr= $data['color'];



                $model->save([
                    'model_id' => $data['model'],
                    'color_id' => $clr,
                    'product_id' => $data['product'],
                    'quantity' => $txtd[$i],
                    'production_date' => date("Y-m-d", mktime(0, 0, 0, $mth, $dt, $yr)),
                    'planning_date' => $pld//date("Y-m-d")
                ]);
                //print_r($pld);
            }

        }




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

   
    public function loadsimresult($tday=null)
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
        $result = array("results"=>$data);

        header("Content-Type: application/json");
        echo json_encode($result,JSON_PRETTY_PRINT);

        // return view('templates/header', $data)
        // . view('mps/simresult', $data)
        // . view('templates/footer');
    }

    public function simresult($tday=null)
    {
        // Capitalize the first letter

        //helper('form');
        $data = $this->request->getPost(['tday']);

        //$data = $this->processsimresult($data);

        //print_r($data); 
        $data['title'] = 'SIMULATION RESULT';

        //$result = array("results" => $data);

        return view('templates/header', $data)
        . view('mps/simresult', $data)
        . view('templates/footer');
    }

    public function processsimresult($data)
    {

        
        $mmyy = explode(' ', $data['tday']);
        if (count($mmyy) < 2) {

            $mmyy[1] = "";
        }

        $db      = \Config\Database::connect('lcl');
        $builder = $db->table('operation_production_plan_header');

        $sqls='SELECT ITEM_ID,sum(oi.IN_QTY) as inv_qty,
            mbm.BOM_ITEM_QTY,(sum(oi.IN_QTY)/mbm.bom_item_qty) as maxprodqty
            FROM master_bom mbm
            inner join o_inventory oi on mbm.BOM_ITEM_ID=oi.ITEM_ID
            where mbm.BOM_PRODUCT_ID=1 
            group by ITEM_ID
            ORDER BY maxprodqty';
        
        $query = $db->query($sqls);

        $tmp = $query->getResultArray();
        $maxprodqty = intval($tmp[0]['maxprodqty']);

        //example : starting from now(march 2024)
      //  $mmyy[0]=3;//month
      //  $mmyy[1]=2024;//year

        //get this month simulation data
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

            }

            $data['products'][$i]['prd'] = $d;


        }

        $db->close();

       $prodqty=0;
        //set maximum date for production(don't count if no prod this month)
        for($i = 0;$i < count( $data['products'][0]['prd'] );$i++) {
            $prodqty=$prodqty+$data['products'][0]['prd'][$i];
            //print_r($maxprodqty);

            if($prodqty==$maxprodqty) {
                $i=$i+1;
                break;
            }
            if($prodqty>=$maxprodqty) {

                break;

            }

        };

        //max production date for this product(should change to dynamic)
        $data['products'][0]['maxproddate']=$i;



        //$result = array("results" => $data);

        return $data;

    }

    public function processsimulation()
    {

        $tday = $_POST['tday'];

        $data = $this->request->getPost(['tday']);

        $mmyy = explode(' ', $data['tday']);
        if (count($mmyy) < 2) {

            $mmyy[1] = "";
        }

        print_r($mmyy);

        $data['title'] = 'Process'; // Capitalize the first letter
        return view('templates/header', $data)
        . view('mps/simresult', $data)
        . view('templates/footer');
    }
}
