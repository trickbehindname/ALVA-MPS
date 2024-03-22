<!-- <h2><?= esc($title) ?></h2> -->
<div class="container">
    <?php
    $today = date("d-M-Y");
echo '<h4> Current Date : '.$today.'</h4>'
?>
</div>
<!--?= session()->getFlashdata('error'); ?-->
<!--?= validation_list_errors() ?-->

<script type="text/javascript">

    const numDays = (y, m) => new Date(y, m, 0).getDate();

    function set_cal(month, year)
    {
            var opt = $("input[name='working-days']:checked").val();
            var rbopt = 0;
            switch(opt) {
                case 'option1':
                    // code block
                    rbopt = 1;
                    break;
                case 'option2':
                    // code block
                    rbopt = 2;
                    break;
                case 'option3':
                    // code block
                    rbopt = 3;
                    break;
                    // code block
            }
            val = month;
            console.log(numDays(year, month));
            mDays =numDays(year, month);

            var i;
            for (i = 1; i <=31; i++) {
                var d = new Date;//(yr,mon,i);
                d.setDate(i);
                d.setMonth(month-1);
                d.setFullYear(year);
                //console.log(d);
                // do something with `substr[i]`
                if (i<=numDays(year, val))
                {
                    document.getElementById("chkbox-"+i).disabled = false;
                    document.getElementById("chkbox-"+i).hidden = false;
                    document.getElementById("lbl-"+i).hidden = false;
                    document.getElementById("txtd-"+i).disabled = false;
                    document.getElementById("txtd-"+i).hidden = false;

                    //cek radio button, set weekdays or all week 
                    if(rbopt==1)
                    {
                        //all week
                        document.getElementById("chkbox-"+i).checked = true;
                    }
                    if(rbopt==2)
                    {
                        //weekday only
                        if(d.getDay()>0 && d.getDay()<6)
                        {
                            document.getElementById("chkbox-"+i).checked = true;
                        }
                        else
                        {
                            document.getElementById("chkbox-"+i).checked = false;

                        }
                    }
                }
                else
                {
                    document.getElementById("chkbox-"+i).disabled = true;
                    document.getElementById("chkbox-"+i).hidden = true;
                    document.getElementById("lbl-"+i).hidden = true;
                    document.getElementById("txtd-"+i).disabled = true;
                    document.getElementById("txtd-"+i).hidden = true;
                }
            }
            
            
            

    }


    
    function set_day(rbOpt)
    {
        var vl = $('h2').html();
        var ret = vl.split(" ");
        var mon = ret[0];
        var yr = parseInt(ret[1]);
        var mth = new Date(Date.parse(mon +" 1, 2012")).getMonth();
       
         console.log("bulan "+mon);
        var i;
        for(i=1;i<=31; i++)
        {
            var d = new Date;//(yr,mon,i);
            d.setDate(i);
            d.setMonth(mth);
            d.setFullYear(yr);

            if (i<=numDays(year, val))
            {
                if(rbOpt==1)
                {
                    //all week
                    document.getElementById("chkbox-"+i).checked = true;
                }
                if(rbOpt==2)
                {
                    //weekday only
                    if(d.getDay()>0 && d.getDay()<6)
                    {
                        document.getElementById("chkbox-"+i).checked = true;
                    }
                    else
                    {
                        document.getElementById("chkbox-"+i).checked = false;

                    }
                }
            }
        }
    }

    function mth(updown) {

        //ambil selected calendar utk proses hari. calendar ada di tag h2
        var ud=0;
        if (updown==1) //btn up
            ud=2

        var vl = $('h2').html();

        $MY = vl;

       // document.getElementById("calvl").value;

        var ret = vl.split(" ");
        var mon = ret[0];
        var yr = parseInt(ret[1]);
        var mth = new Date(Date.parse(mon +" 1, 2012")).getMonth()+ud;
        if(mth==0){mth=12; yr=yr-1};
        if(mth==13){mth=1; yr=yr+1};

        document.getElementById("tday").value = mth+" "+yr;

        console.log("year " + yr);
        console.log("bulan (num) " + mth);
        set_cal(mth, yr);

        // var opt = $("input[name='working-days']:checked").val();
        //     var rbopt = 0;
        //     switch(opt) {
        //         case 'option1':
        //             // code block
        //             rbopt = 1;
        //             break;
        //         case 'option2':
        //             // code block
        //             rbopt = 2;
        //             break;
        //         case 'option3':
        //             // code block
        //             rbopt = 3;
        //             break;
        //             // code block
        //     }

        // set_day(rbopt);
    }
    


    $(document).ready(function(){
        // karena calendar loading setelah page ready, jadi harus ambil today month manual.
        // const monthNames = ["January", "February", "March", "April", "May", "June",
        // "July", "August", "September", "October", "November", "December"
        // ];

        today = new Date;
        year = today.getFullYear();
        month = today.getMonth()+1;

        var vl =month + " " + year;
        document.getElementById("tday").value = vl ;
        set_cal(month, year);

    });

    $(document).ready(function(){
        $("select#Month-select").change(function(){


        });
    });

    function rn(){
        var qty = document.getElementById("qty").value;

        var wd=0;
        var i=0;
        var ipd=0;//item per day
        var res=0;//residual item per mth
        for(i=1;i<=31; i++)//get the number of workdays
        {
            if(document.getElementById("chkbox-"+i).checked==true && document.getElementById("chkbox-"+i).disabled==false)
            {
                wd=wd+1;
            }
        }
        
        ipd = Math.floor(qty/wd);

        res = qty%wd;

        for(i=1;i<=31; i++)//set the number of item per day
        {
            if(document.getElementById("chkbox-"+i).checked==true && document.getElementById("chkbox-"+i).disabled==false)
            {
                document.getElementById("txtd-"+i).value = ipd;
            }

        }

        i=1 //reset i
        while (res>0)
        {
            if(document.getElementById("chkbox-"+i).checked==true && document.getElementById("chkbox-"+i).disabled==false)
            {
                document.getElementById("txtd-"+i).value = parseInt(document.getElementById("txtd-"+i).value)+1;
                res=res-1;
            }
            i++;
        }

        // for(i=1;i<=res; i++)//set the residual item per mth
        // {
        //     if(document.getElementById("chkbox-"+i).checked==true && document.getElementById("chkbox-"+i).disabled==false)
        //     {
        //         document.getElementById("txtd-"+i).value = parseInt(document.getElementById("txtd-"+i).value) +1;
        //     }
        //     else
        //     {
        //         i=i-1;
        //     }
        // }

        //document.getElementById("jph").value = wd;
        console.log("working day:"+ wd +" per day:" + ipd+" residual:" + res);

    }
</script>

<?php
$Month		= //isset($_POST['Month-select']) ;
$MY = '';
$doM        = 0;
$d          = 0;

// $_POST['Month-select'] : $month;
?>


<!-- <form method="post" action="/mps"> -->
    <!--?= csrf_field() ?-->
    <div class="container">
        <!--?=print_r($OpPrdPlanHdrs)?-->
        <!-- <form method="post" action="/mps"> -->
                       
        <div class="form-horizontal border-top border-bottom">



        <div class="widget">
            <div class="widget-body">
                <div class="row">
                    <div class="col">
                        <div class="control-group">
                            <label class="control-label" for="Month">Month</label>
                            <div class ="row">
                                <div class="col-md-5">
                                    <div class="calendar calendar-first" id="calendar_first">
                                        <div class="calendar_header">
                                            <button class="switch-month switch-left" onclick="mth(0)"> <i class="fa fa-chevron-left"></i></button>
                                                <div class="mycal">
                                                    <h2 name='prodplanmonth'></h2>
                                                </div>
                                            <button class="switch-month switch-right" onclick="mth(1)"> <i class="fa fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- <select class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" id="Month-select" name="Month-select">
                                <option selected>Select Month</option> -->
                                <!-- <?php
                                        for ($x = 1; $x <= 12; $x++) {
                                            $month_name = date("F", mktime(0, 0, 0, $x));
                                            // echo "The number is: $x <br>";
                                            if($x == $Month) {
                                                if($x > 0) {
                                                    $d = cal_days_in_month(CAL_GREGORIAN, $x, 2024);
                                                }
                                                echo '<option value="' . $x . '" selected>' . $month_name . '</option>';
                                            } else {
                                                if($x > 0) {
                                                    $d = cal_days_in_month(CAL_GREGORIAN, $x, 2024);
                                                }
                                                echo "<option value=".$x.">".$month_name."-".$d."</option>";
                                            }
                                        }

?> -->
                                <!-- </select> -->

                        </div>
                        <form method="post" action="/mps">
                            <div >
                                <input type=text id = "tday" name="tday" value="">
                            </div>
                        <div class="control-group">
                            <label class="control-label" for="model">Model</label>
                            <select name="model" class="rounded">
                                <option selected>Select Model</option>
                                <option value="" hidden></option>
                                <?php foreach ($mstmdls as $key => $value) :?>
                                        <option value="<?=$value->MODEL_ID?>"><?=$value->MODEL_NAME?></option>
                                <?php endforeach; ?> 
                            </select>
                            <!-- <input class="rounded" type="text" id="model" value=""> -->
                        </div> 
                        <div class="control-group">
                            <label class="control-label" for="Color">Color</label>
                            <select name="color" class="rounded">
                                <option selected>Select Color</option>
                                <option value="" hidden></option>
                                <?php foreach ($mstclrs as $key => $value) :?>
                                    <option value="<?=$value->COLOR_ID?>"><?=$value->COLOR_NAME?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="product">Product</label>
                            <select name="product" class="rounded">
                                <option selected>Select Product</option>
                                <option value="" hidden></option>
                                <?php foreach ($mstprods as $key => $value) :?>
                                    <option value="<?=$value->PRODUCT_ID?>">
                                        <?=$value->PRODUCT_DESCRIPTION?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col ">
                        <div class="control-group">
                        <label class="control-label" for="qty">Quantity</label>
                                <input type="text" class="rounded" id="qty" name = "qty" value ="<?= set_value('qty') ?>">
                        </div>
                        <div class="control-group">
                        <label class="control-label" for="jph">JPH</label>
                                <input type="text" class="rounded" id="jph" value ="">
                        </div>
                        <div class="control-group">
                        <label class="control-label" for="target">Target</label>
                                <input type="text" class="rounded" id="target" value ="">
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="working-days">Working Days</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="working-days" id="working-days-1" value="option1" onclick="set_day(1)" checked>
                                <label class="form-check-label" for="working-days-1">
                                    All
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="working-days" id="working-days-2" value="option2" onclick="set_day(2)">
                                <label class="form-check-label" for="working-days-2">
                                    Week Days
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="working-days" id="working-days-3" value="option3" onclick="set_day(3)">
                                <label class="form-check-label" for="working-days-3">
                                    Custom
                                </label>
                            </div>

                        </div>

                        <div class ="control-group">
                            <button type="button" class = "btn btn-primary btn-sm ms-auto" onclick="rn()">Run</button>              
                        </div>
                    </div>
                </div>
                </div>


            </div>
        </div>
    
        <br/>

        <div class="row">
        <!-- <div class="col"></div> -->
            <?php
                //$hdn='hidden="true"';
                $hdn = '';
//create textbox and checkbox
for ($x = 1; $x <= $d; $x++) {
    echo '<div class="col">';
    echo '<div class="d-flex justify-content-center">';
    echo '<input type="text"  id = "txtd-'.$x.'" '.$hdn.' disabled=true name="txtd[]" value="'.set_value('quantity'.$x.'').'" size="2">';
    echo '</div>';
    echo '<div class="d-flex justify-content-center">';
    echo '<label name = "lbl[]" id = "lbl-'.$x.'" '.$hdn.' disabled=true >'.$x.'</label>';
    echo '</div>';
    echo '<div class="form-check d-flex justify-content-center">';
    // <input class="form-check-input" type="checkbox" value="{{obj.total}}" id="flexCheckDefault" checked> <!-- CENTER THIS ITEM -->
    echo '<input type="checkbox" class="form-check-input" id = "chkbox-'.$x.'" '.$hdn.' disabled=true value=false>';
    echo '</div>';
    echo '</div>';
    if(round($x % 16) == 0) {
        echo '<div class="w-100"></div> <br><br>';
    }
}
?>


        

        </div>
        

        <div class="row">
                <div class="col">
                <label for="total-hours">Total Hours</label>
                    <div class="form-group">
                        
                        <div class="controls">
                            <input type="text" class="rounded" id="total-hours" value ="">

                        </div>


                    </div>
                </div>
                <div class="col">
                <div class="control-group">
                    <label class="control-label" for="total-days">Total Days</label>
                    <div class="controls">
                        <input type="text" class="rounded" id="total-days" value ="">
            
                    </div>
                </div>

            </div>
            <div class="col">

                <label class="control-label" for="total-jph">JPH</label>
                <input type="text" class="rounded" id="total-jph" value ="">

            </div>

            <div class="col">
                <!-- <button type="button" class="btn btn-primary vertical-center">
                    Save
                </button> -->
                <input type="submit" class="btn btn-primary vertical-center" id="save" name="save" value="Save">
            </div>

        </div>

        </form>




    </div>
<!-- </form> -->

<!-- end of container -->


<!-- var loctext = $('#select-location').find("option:selected").text();
$('#select-icc').val(icc);	 -->
<div>
    <!-- <?php if (! empty($news) && is_array($news)): ?>

    <?php foreach ($news as $news_item): ?>

        <h3><?= esc($news_item['title']) ?></h3>

        <div class="main">
            <?= esc($news_item['body']) ?>
        </div>
        <p><a href="/news/<?= esc($news_item['slug'], 'url') ?>">View article</a></p>

    <?php endforeach ?>

    <?php else: ?> -->

    <!-- <h3>No News</h3>

    <p>Unable to find any news for you.</p> -->

    <!-- <?php endif ?> -->

</div>

