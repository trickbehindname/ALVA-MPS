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

class Mps extends BaseController
{
    protected $helpers = ['custom'];

    function __construct()
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

        $data['title'] ='MPS';

        return view('templates/header', $data)
        . view('mps/create'  )
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
        // $model = model(NewsModel::class);

        // $model->save([
        //     'title' => $post['title'],
        //     'slug'  => url_title($post['title'], '-', true),
        //     'body'  => $post['body'],
        // ]);

        // $data = $this->request->getPost(['title', 'body']);

        // $test = [];
        // for($i=1;$i<=31;$i++)
        // {
        //     $test[$i] = $post['quantity'.$i];

        // }

        $model = model(OpPrdPlanHdrModel::class);
        
        $data = $this->request->getPost(['model','color','product','txtd[]','tday']);
        $tday = explode(' ', $data['tday']);
     
        // print_r($tday);
        // die;
           $mth = (int)$tday[0];
        $yr = (int)$tday[1];
        $txtd =$data['txtd[]'];
       // $lbl = $data['lbl[]'];
        //$ndate = mktime(0,0,0,$mth,$dt,$yr);

        //$mthDtl = [];

        // for($i=0;$i<count($txtd);$i++)
        // {
        //     $dt = $i+1;
        //     $mthDtl[$i]=mktime(0,0,0,$mth,$dt,$yr);
        //     // print_r(date("d-m-Y",$mthDtl[$i])." ");
        // }
        
        for($i=0;$i<count($txtd);$i++)
        {
            $dt = $i+1;
            $prdd = date('Y-m-d',mktime(0,0,0,$mth,$dt,$yr));//date_format(mktime(0,0,0,$mth,$dt,$yr),"YYYY-MM-DD");
            $pld = date('Y-m-d');
            $model->save([
                'model_id' => $data['model'],
                'color_id' => $data['color'],
                'product_id' => $data['product'],
                'quantity' => $txtd[$i],
                'production_date' => date("Y-m-d",mktime(0,0,0,$mth,$dt,$yr)),
                'planning_date' => $pld//date("Y-m-d")
            ]);
            //print_r($pld);
        }

      //print_r(date("d-m-Y",$mthDtl[5]));
        // die;
            
//        exit;


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

    public function simulation()
    {
        helper('form');
        $data['title'] = 'SIMULATION'; // Capitalize the first letter

        $data['mstmdls'] = $this -> mstmdl -> findAll();
        $data['mstclrs'] = $this -> mstclr -> findAll();
        $data['mstprods'] = $this -> mstprod -> findAll();
        $data['OpPrdPlanHdrs'] = $this -> OpPrdPlanHdr 
            ->where('Month(production_date)','5')
            ->where('Year(production_date)','2024')
            ->findAll();
       
		$db      = \Config\Database::connect('lcl');
		$builder = $db->table('operation_production_plan_header');

		$builder->select('product_id,color_id,varian_id,model_id');
		$builder->distinct();
        $query= $builder->get();
		$data['products'] = $query->getResultArray();
        
        for($i=0;$i<count($data['products']);$i++)
        {
            //ambil row dari tabel operation_production_plan_header 
            //yang punya product-color-varian-bulan produksi yang sama

            $builder->select('day(production_date) as tgl,quantity');//,planning_date,production_sequence,JPH,VIN,status');
            $builder->where('product_id',$data['products'][$i]['product_id']);
            $builder->where('color_id',$data['products'][$i]['color_id']);
            $builder->where('varian_id',$data['products'][$i]['varian_id']);
            $builder->where('model_id',$data['products'][$i]['model_id']);
            // $builder->where('Month(production_date)','5');
            // $builder->where('Year(production_date)','2024');
            $query= $builder->get();           
            $dt=[]; 
            $dt = $query->getResultArray();
           

            $data['products'][$i]['prd']=$dt;
            $d=[];

            //print_r(count($dt));
            foreach($dt as $key=>$value)
            {
                $d=array_merge($d,[$value['tgl'] => $value['quantity']]);
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
            $data['products'][$i]['prd']=$d;
            
            // print_r($d);
            // print_r('---');
            
        }
        
        //  print_r($data['products'][0]);
        

		
		$db->close();


        return view('templates/header', $data)
            . view('mps/simulation')
            . view('templates/footer');
    }
}