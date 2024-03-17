<script type="text/javascript">
    function mth(updown) {
        //ambil selected calendar utk proses hari. calendar ada di tag h2
        var ud=0;
        if (updown==1) //btn up
            ud=2

        var vl = $('h2').html();

        $MY = vl;
        console.log("vl");
       
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
        vl=mth+" "+yr;

        retData = new Array();
       
        $.ajax({
            url : '<?php echo site_url('/loadsimulation')?>',
            type : 'POST',
            data : {tday:  vl},
            //async : true,
            dataType: 'json',
            success: function(data){
                //alert('Redirecting...');
                retData=data.results;
                console.log(retData.products[0]);

                // echo '<tr>';
                //         echo '<th scope="row">'.$products[$x]['product_id'];
                //         echo '</br>'.$products[$x]['varian_id'];
                //         echo '</br>'.$products[$x]['color_id'];
                //         echo '</br> <button>Edit</button> <button>Delete</button>'.'</th>';
                var res='';
                
                $.each (retData.products[0], function (key, value) {
                    console.log(key+'='+value);
                    res +=
            '<tr>'+
                '<th scope="row">'+value+
                '</br>'+value+
                '</br>'+value+
                '</br> <button>Edit</button> <button>Delete</button> </th>'+
           '</tr>';

   });

            $('tbody').html(res);

            },
            error: function(data){
                
                console.log("failed ajax");
            }
        });

        
        // $.ajax({
        //     url : '
        //     type : 'POST',
        //     data : {tday: vl},
        //     dataType: 'text',
        //     success: function(data){
        //         console.log("success ajax");
        //     },
        //     error: function(data){
        //         console.log("failed 2 ajax");
        //     }
        // });

           
        
        }

    $(document).ready(function(){
        // karena calendar loading setelah page ready, jadi harus ambil today month manual.
        // const monthNames = ["January", "February", "March", "April", "May", "June",
        // "July", "August", "September", "October", "November", "December"
        // ];

        today = new Date;
        year = today.getFullYear();
        month = today.getMonth()+1;

        var vl =month +" "+year ;
        document.getElementById("tday").value =month +" "+year ;
        
        $.ajax({
            url : '<?php echo site_url('/loadsimulation')?>',
            type : 'POST',
            data : {tday: vl},
            dataType: 'json',
            success: function(data){
                console.log("success ajax");
            },
            error: function(data){
                console.log("failed 2 ajax");
            }
        });

    });

</script>

<style>

    /* .table-striped-columns{
        overflow: auto;
    } */

</style>


    <input type=text id="tday" name="tday" value="">
    <div class="calendar calendar-first" id="calendar_first">
        <div class="calendar_header">
            <button class="switch-month switch-left" onclick="mth(0)"> <i class="fa fa-chevron-left"></i></button>
                <div class="mycal">
                    <h2 name='prodplanmonth'></h2>
                </div>
            <button class="switch-month switch-right" onclick="mth(1)"> <i class="fa fa-chevron-right"></i></button>
        </div>
    </div>




<div class="container-fluid">
 
<form>
   <div class="">
        <table class="table table-striped-columns table-hover table-fit align-middle">
            <thead>
                <tr>
                    <th scope="col" > Header </th>
                    <?php
                        for($i = 0;$i < 31;$i++) {
                            echo '<th scope="col" class ="col-1">' . $i + 1 . '</th>';
                        }
            ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                //foreach($products as $product)
                if(!empty($products) && is_array($products)) {
                    for($x = 0;$x < count($products);$x++) {
                        // echo '<tr>';
                        // echo '<td>'.$product['product_id'].'</td>';
                        // echo '</tr>';
                        // echo '<tr>';
                        // echo '<td>'.$product['varian_id'].'</td>';
                        // echo '</tr>';
                        // echo '<tr>';
                        // echo '<td>'.$product['color_id'].'</td>';
                        // echo '</tr>';
                        echo '<tr>';
                        echo '<th scope="row">'.$products[$x]['product_id'];
                        echo '</br>'.$products[$x]['varian_id'];
                        echo '</br>'.$products[$x]['color_id'];
                        echo '</br> <button>Edit</button> <button>Delete</button>'.'</th>';
                        //echo '<td>'.$product['prd']['1'].'</td>';

                        //print_r($product["prd"]);
                        for ($i = 0;$i < count($products[$x]["prd"]);$i++) {
                            // echo '<br>';
                            echo '<td> <br>'.$products[$x]["prd"][$i].'</td>';
                        }
                        //echo '<td>'.$product['prd'].'</td>';
                        echo '</tr>';


                    }
                } else {
                    echo '<tr>';
                    echo '<th scope="row">Tidak ada data</th>';
                    echo '</tr>';
                }
            ?>         
            </tbody>
        </table>

    </div>
    <input type="submit" value="Save">
</form>
</div>