
<div class="container-fluid">
 

    <div>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col" colspan="2" > Header </th>
                    <?php
                        for($i=0;$i<31;$i++)
                        {
                            echo '<th scope="col">' . $i+1 . '</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                        foreach($products as $product)
                        {
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
                            echo '<th scope="row">'.$product['product_id'];
                            echo '</br>'.$product['varian_id'];
                            echo '</br>'.$product['color_id'];
                            echo '</br> <button>Edit</button> <button>Delete</button>'.'</th>';
                            echo '</tr>';

                        }
                    ?>         
            </tbody>
        </table>

    </div>
    <!-- <div> -->
        <!-- page body -->
        <!--div class="row"><-- header tanggal -->
            <!-- <div class="form-inline"> -->
                <!--?php
                    for($x=1; $x<= 31; $x++) {
                        echo '<label id="lbl[]" >'.$x.'</label>';
                    }
                ?-->

            <!-- </div> -->

        <!-- </div> -->
    <!-- </div> -->
</div>