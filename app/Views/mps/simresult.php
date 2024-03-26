<script type="text/javascript">
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
            url : '<?php echo site_url('/loadsimresult')?>',
            type : 'POST',
            data : {tday: vl},
            dataType: 'json',
            success: function(data){
                retData=data.results;
                console.log(retData);
                console.log("success 1 ajax");
                
	            var res='';
				// <table class="table table-striped-columns">
				res=res+
				'<table class="table table-striped-columns"> <tr>'+
                '    <th>  </th>';
				for(i = 0;i < 31;i++) {
					res+=
                    '    <th>'+(i+1)+'</th>';
				};
				res=res+'</tr>';

				if (retData.products.length > 0) {
					for(x=0;x<retData.products.length;x++)
					{
						console.log(retData.products[x]["prd"].length);
						res +=
						'<tr>'+
							'<th scope="row">'+retData.products[x]['product_id']+
							'</br>'+retData.products[x]['varian_id']+
							'</br>'+retData.products[x]['color_id']+
							'</br><div class="btn-group-sm" role="group">'+
                            '<form action ="simresdetail" method="post">' +
                            '<input type=text id="dtl" name="dtl" value="'+vl+' '+x+'" hidden >'+
                            '<button type="submit" class="btn btn-primary">Details</button>'+
                            '</form>'+
                            ' </th>';
							for (i = 0;i <retData.products[x]["prd"].length;i++) {
								console.log(retData.products[x]['prd'][i]);

                                if(i>=retData.products[x]['maxproddate']){
                                    
                                    res = res + '<td style="text-align:center;background-color:red;"> <br>'+retData.products[x]['prd'][i]+'</td>';
                                }else{
                                    res = res + '<td> <br>'+retData.products[x]['prd'][i]+'</td>';
                                }

								
							};
							res= res + '</tr>';

					}
					res+-'</table>';
				}
				else {
					res += '<tr><th scope="row">Tidak ada data</th></tr></table>';
				}


				$('#tablez').html(res);
				console.log("tablex");
            },
            error: function(data){
                alert("ajax error, json: " + data);
                console.log(data);
                console.log('masih error');
            }
        });
    

    });

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
            url : '<?php echo site_url('/loadsimresult')?>',
            type : 'POST',
            data : {tday:  vl},
            dataType: 'json',
            success: function(data){
                retData=data.results;
                console.log(retData);
                console.log("success 1 ajax");
	            var res='';
				// <table class="table table-striped-columns">
				res=res+
				'<table class="table table-striped-columns"> <tr>'+
                '    <th>  </th>';
				for(i = 0;i < 31;i++) {
					res+=
                    '    <th>'+(i+1)+'</th>';
				};
				res=res+'</tr>';

				if (retData.products.length > 0) {
					for(x=0;x<retData.products.length;x++)
					{
						console.log(retData.products[x]["prd"].length);
						res +=
						'<tr>'+
							'<th scope="row">'+retData.products[x]['product_id']+
							'</br>'+retData.products[x]['varian_id']+
							'</br>'+retData.products[x]['color_id']+
							'</br><div class="btn-group-sm" role="group">'+
                            '<form action ="mpssimresdetail" method="post">' +
                            '<input type=text id="dtl" name="dtl" value="'+vl+' '+x+'" hidden >'+
                            '<button type="submit" class="btn btn-primary">Details</button>'+
                            '</form>'+
                            ' </th>';
							for (i = 0;i <retData.products[x]["prd"].length;i++) {
								console.log(retData.products[x]['prd'][i]);

                                if(i>=retData.products[x]['maxproddate']){
                                    
                                    res = res + '<td style="text-align:center;background-color:red;"> <br>'+retData.products[x]['prd'][i]+'</td>';
                                }else{
                                    res = res + '<td> <br>'+retData.products[x]['prd'][i]+'</td>';
                                }

								
							};
							res= res + '</tr>';

					}
					res+-'</table>';
				}
				else {
					res += '<tr><th scope="row">Tidak ada data</th></tr></table>';
				}


				$('#tablez').html(res);
				console.log("table");

            },
            error: function(data){
                alert("ajax error, json: " + data);
                console.log(data);
                console.log("failed again");
            }
        });
    }

</script>

<input type=text id="tday" name="tday" value="" >
    <div class="calendar calendar-first" id="calendar_first">
        <div class="calendar_header">
            <button class="switch-month switch-left" onclick="mth(0)"> <i class="fa fa-chevron-left"></i></button>
                <div class="mycal">
                    <h2 name='prodplanmonth'></h2>
                </div>
            <button class="switch-month switch-right" onclick="mth(1)"> <i class="fa fa-chevron-right"></i></button>
        </div>
    </div>

    <div class ="container-fluid">
    <div id="tablez" >
        <table class="table table-striped-columns">
			<!-- <colgroup>
			<col span="1" style="background-color: #D6EEEE">
			</colgroup> -->
			<!-- <thead> -->
			<tr>
				<th>  </th>
				<?php
					for($i = 0;$i < 31;$i++) {
						echo '<th>' . $i + 1 . '</th>';
					}
				?>
			</tr>
            <!-- </thead> -->
            <tbody>
                    <?php
                // //foreach($products as $product)
                //print_r()
                if(!empty($products) && is_array($products)) {
                    for($x = 0;$x < count($products);$x++) {

                        echo '<tr>';
                        echo '<th scope="row">'.$products[$x]['product_id'];
                        echo '</br>'.$products[$x]['varian_id'];
                        echo '</br>'.$products[$x]['color_id'];
                        echo '</br> <button>Edit</button> <button>Delete</button>'.'</th>';

                        for ($i = 0;$i < count($products[$x]["prd"]);$i++) {
                            // echo '<br>';
                            if($i>=$products[$x]['maxproddate']){
                                echo '<td style="text-align:center;background-color:red;"> <br>'.$products[$x]["prd"][$i].'</td>';
                            }
                            else{
                                echo '<td> <br>'.$products[$x]["prd"][$i].'</td>';
                            } 
                                

                        }
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

    </div>