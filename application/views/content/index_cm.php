<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Dashboard</h4></div>


</div>
<!--End Card box-->
<div class="col-md-12 col-lg-12 col-sm-12">
    <form id="pencarian" action="<?php echo site_url('Admin/dashboard') ?>">
        <div class="col-md-3 col-lg-3 col-sm-3"> 
            <div class="form-group"> 
                <div class='input-group date' id='date'>
                    <input type="text" class="form-control" placeholder="Month to date" name="from-date" id="from-date" value="<?php echo $max_tanggal?>"/>   
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div> 
        </div>
        <div class="col-md-1 col-lg-1 col-sm-1"> 
            <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
        </div>
    </form>
</div>
<div class="preloader"><img src="<?php echo base_url() ?>theme/images/ring.gif"></div>
<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-speedometer cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Current Sales  <br>
                    <span class="card-value"><?php echo $cart_summery['current_day_psb']; ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-speedometer cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Month Sales<br>
                    <span class="card-value"><?php echo $cart_summery['current_month_psb']; ?></span></p>
            </div>
        </div>
    </div>
</div>
<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-social-bitcoin cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Current Revenue<br>
                    <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_day_revenue']; ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-social-bitcoin cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Month Revenue<br>
                    <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_month_revenue']; ?></span></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
<!--Start Daily Report Line Chart-->
<div class="col-md-12 col-sm-12 col-lg-12">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    
    <!-- Default panel contents -->
    <div class="panel-heading">Daily Report (<span id="nilaidaily"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body">
        <!--<canvas id="inc_vs_exp2"></canvas>-->
        <div id="inc_vs_exp2"></div>
        <br>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End Daily Report Col-->

<!--Start CM Ekstrak-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    
    <div class="panel-heading">SALES CM EKTRAK (<span id="nilaibranch"><?php echo $max_tanggal?></span>) </div>
    <div class="panel-body financial-bal" style="font-size: 11px;">
        <!--Branch Table-->
        <table class="table table-bordered" >
            <tr>
                <th style="background-color: black; color: white">KETERANGAN</th>
                <th style="background-color: black; color: white">JUMLAH</th> 
            </tr>
            <tr>
                <td>SALES</td>
                <td><?php $sales_cm = $cm_ekstrak['sales_cm']; echo number_format($sales_cm);?></td>
            </tr>
            <tr>
                <td>CHURN</td>
                <td><?php $churn_cm = $cm_ekstrak['churn_cm']; echo number_format($churn_cm);?></td>
            </tr>
            <tr>
                <td>NETADD</td>
                <td><?php $netadd = $sales_cm-$churn_cm; echo number_format($netadd); ?></td>
            </tr>
            <tr>
                <td>REVENUE</td>
                <td><?php $revenue_cm = $cm_ekstrak['revenue_cm']; echo number_format($revenue_cm);?></td>
            </tr>
        </table>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End CM Ekstrak Col-->

</div>
<!--End Row-->
</div>
</section>
<!--End Main-content-->
<script type="text/javascript">
$(document).ready(function() {

    $(".financial-bal").niceScroll({
        cursorwidth: "8px",cursorcolor:"#7f8c8d"
    });


    var chart;
    chart = c3.generate({
        bindto: '#inc_vs_exp2',
        data: {
            x: 'x',
    //        xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
            columns: [
                ['x'

                <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
                 echo ",";    
                 echo "'".$line_chart[0][$i]['date']."'"; 
                } ?>

                ],

                ['Aktif', 
                <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
                echo  $line_chart[0][$i]['amount'].",";
                } ?>
                ],

                // ['Pending', 
                // <?php // for($i=1;$i<=count($line_chart[1]);$i++){ 
                // echo  $line_chart[1][$i]['amount'].",";
                // } ?>
                // ],

                
            ]
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d'
                }
            }
        }
    });


    $("#date").datepicker();

    $('#pencarian').on('submit',function(){
        var link=$(this).attr("action");
        var tanggal = $("#from-date").val();
        $(".block-ui").css('display','block'); 
        if(tanggal!=""){
            history.pushState(null, null,link);  
            $('.asyn-div').load(link+'/view/'+tanggal,function() {
                $(".block-ui").css('display','none');  
                $("#from-date").val(tanggal);   
                $("#nilaidaily").html(tanggal);   
                $("#nilaibranch").html(tanggal);   
                $("#nilaipaket").html(tanggal);   
            });
        }else{
            swal("Alert","Please Select Date Range.", "info");      
        }

        return false;
    });


    chart = c3.generate({
        bindto: '#persen_status',
        data: {
            columns: [
                ['Sukses', <?php echo $pie_data['sukses'] ?>],
                ['Reject', <?php echo $pie_data['reject'] ?>],
                ['Retur', <?php echo $pie_data['retur'] ?>],
                ['Pending', <?php echo $pie_data['pending'] ?>],
                ['Cancel', <?php echo $pie_data['cancel'] ?>],
                ['Bentrok', <?php echo $pie_data['bentrok'] ?>],
                ['Masuk', <?php echo $pie_data['masuk'] ?>],
                ['Blacklist', <?php echo $pie_data['blacklist'] ?>],
                ['Valid', <?php echo $pie_data['valid'] ?>],
            ],
            type: 'donut',
            onclick: function(d, i) {
                alert(d['name']+' : '+d['value']);
                // console.log("onclick", d, i);
            },
            onmouseover: function(d, i) {
                document.getElementById("info_status").innerHTML = d['name']+' : '+d['value'].toLocaleString(undefined,{ minimumFractionDigits: 0 }
);
                // console.log("onmouseover", d, i);
            },
            // onmouseout: function(d, i) {
            //     console.log("onmouseout", d, i);
            // }
        },
        // color: {
        //     pattern: ['#23c6c8', '#f39c12']
        // },
        donut: {
            title: "% Status Sales"
        }
    });
   
});

</script>