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
                    <input type="text" class="form-control" placeholder="Tanggal" name="from-date" id="from-date"/>   
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
                <p class="card-head">Current Day PSB<br>
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
                <p class="card-head">Current Month PSB<br>
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
                <p class="card-head">Current Day Revenue<br>
                    <span class="card-value"></span></p>
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
                <p class="card-head">Current Month Revenue<br>
                    <span class="card-value"></span></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
<!--Start Income Vs Expense Line Chart-->
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
<!--End Income Col-->


<!--Start Branch-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Top Branch (<span id="nilaibranch"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body" style="height: 260px; overflow: auto;">
        <!--Branch Table-->
        <table class="table table-bordered" >
            <th>No</th>
            <th>Branch</th>
            <th>Bulan</th>
            <th class="text-right">Jumlah</th>
         <?php $no=1; foreach($top_branch as $top){ ?>   
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $top->nama_branch ?></td>
                <td><?php echo $top->m_name ?></td>
                <td class="text-right"><?php echo number_format($top->amount) ?></td>
            </tr>

          <?php } ?>  

        </table>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End Branch Col-->

<!--Start Expense-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Top 10 Paket (<span id="nilaipaket"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body" style="height: 260px; overflow: auto;">
        <table class="table table-bordered">
            <th>No</th>
            <th>Paket</th>
            <th>Bulan</th>
            <th class="text-right">Jumlah</th>
             <?php $no=1; foreach($top_paket as $paket){ ?>   
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $paket->nama_paket ?></td>
                <td><?php echo $paket->m_name ?></td>
                <td class="text-right"><?php echo number_format($paket->amount) ?></td>
            </tr>

          <?php } ?>  
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Expense Col-->

<!--Start Income Vs Expense Chart-->
<div class="col-md-6 col-sm-6 col-lg-6">
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Income Vs Expense - July 2015</div>
    <div class="panel-body">
        <div id="inc_vs_exp"></div>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Income Vs Expense Chart-->

<!--Start Account Status-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Financial Balance Status</div>
    <div class="panel-body financial-bal">
        <table class="table table-bordered ">
            <th>Account</th>
            <th class="text-right">Balance</th>
            <?php foreach($financialBalance as $balance) {?>
            <tr>
                <td><?php echo $balance->account ?></td>
                <td class="text-right"><?php echo get_current_setting('currency_code')." ".$balance->balance ?></td>
            </tr>

            <?php } ?>
   
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Account Status Col-->

</div>
<!--End Row-->
</div>
</section>
<!--End Main-content-->
<script type="text/javascript">
$(document).ready(function() {
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

            ['Pending', 
            <?php for($i=1;$i<=count($line_chart[1]);$i++){ 
            echo  $line_chart[1][$i]['amount'].",";
            } ?>
            ],

            
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
    if(tanggal!=""){
        
        //query data
        $.ajax({
            method : "POST",    
            url : link,
            data : $(this).serialize(),
            beforeSend : function(){
                $(".preloader").css("display","block");
                $(".block-ui").css('display','block');
            },success : function(data){
                history.pushState(null, null,link);  
                $('.asyn-div').load(link+'/view/'+tanggal,function() {
                    $(".block-ui").css('display','none');  
                    $("#from-date").val(tanggal);   
                    $("#nilaidaily").html(tanggal);   
                    $("#nilaibranch").html(tanggal);   
                    $("#nilaipaket").html(tanggal);   
                });
            }

        });
    }else{
        swal("Alert","Please Select Date Range.", "info");      
    }

    return false;
});


chart = c3.generate({
    bindto: '#inc_vs_exp',
    data: {
        columns: [
            ['Income', <?php echo $pie_data['income'] ?>],
            ['Expense', <?php echo $pie_data['expense'] ?>],
        ],
        type: 'donut',
        onclick: function(d, i) {
            console.log("onclick", d, i);
        },
        onmouseover: function(d, i) {
            console.log("onmouseover", d, i);
        },
        onmouseout: function(d, i) {
            console.log("onmouseout", d, i);
        }
    },
    color: {
        pattern: ['#23c6c8', '#f39c12']
    },
    donut: {
        title: "Income VS Expense"
    }
});
   
});

</script>