<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Report Viewer</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<?php 
$status=array('sukses'=>'sukses',
  'valid'=>'valid',
  'cancel'=>'cancel',
  'reject'=>'reject',
  'pending'=>'pending',
  'retur'=>'retur',
  'blacklist'=>'blacklist',
  'bentrok'=>'bentrok',
  'masuk'=>'masuk');
$tgl = array("tanggal_aktif"=>"tanggal_aktif", "tanggal_validasi"=>"tanggal_validasi", "tanggal_masuk"=>"tanggal_masuk");
?>
<div class="panel panel-default"  style="font-size: 10px; font-family: tahoma;">
    <!-- Default panel contents -->
    <div class="panel-heading">SCN Report Weekly</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="sales_cari" action="<?php echo site_url('Reports/reports_scn_weekly/view') ?>">
                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <div class='input-group'>
                            <input type="text" class="form-control" placeholder="<?php echo isset($bto_date) ? $bto_date : "Date To" ?>" name="vto-date" id="vto-date" readonly/> 
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
        <div id="Report-Table" class="col-md-12 col-lg-12 col-sm-12">
            <div class="preloader"><img src="<?php echo base_url() ?>theme/images/ring.gif"></div>
            <div class="report-heading">
                <!-- <h4>Branch</h4> -->
            </div>
            <style style="text/css">
                /* Define the hover highlight color for the table row */
                .hoverTable tr:hover {
                    background-color: #b8d1f3;
                }
            </style>
            <div id="Table-div" style="overflow: auto; font-size: 9px; font-family: tahoma; font-style: italic; height: 0.1%;">
                <table class="table table-bordered  hoverTable">
                    <thead>
                        <tr>
                            <th rowspan="3" style="background-color: #1e3799; color: white;" class="text-center"><b>AREA-2</b></th>
                            <th colspan="7" style="background-color: #1e3799; color: white;" class="text-center"><b>CURRENT MONTH</b></th>
                            <th colspan="5" style="background-color: #1e3799; color: white;" class="text-center"><b>CUMMULATIVE</b></th>
                            <th colspan="4" style="background-color: #1e3799; color: white;" class="text-center"><b>FY 2018</b></th>
                        </tr>
                        <tr>
                            <th colspan="3" style="background-color: #1e3799; color: white;" class="text-center"><b>1-8 Juni 2018</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>1-8 Mei 2018</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>MoM Growth</b></th>
                            <th rowspan="3" style="background-color: #1e3799; color: white;" class="text-center"><b>1-8 Juli 2017</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>YoY Growth</b></th>
                            <th colspan="3" style="background-color: #1e3799; color: white;" class="text-center"><b>Jan - Juni 2018</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>Jan - Juni 2017</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>YtD Growth</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>Tgt</b></th>
                            <th rowspan="2" style="background-color: #1e3799; color: white;" class="text-center"><b>Ach</b></th>

                        </tr>
                        <tr>
                            <th style="background-color: orange; color: white;" nowrap=""><b>Act</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>Tgt</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>GAP</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>Act</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>Tgt</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>GAP</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div> 
    </div>
<!--End Panel Body-->
</div>     
    

<!--End Panel-->       
</div>
</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->

<!--<script src="<?php echo base_url() ?>/theme/js/pdf/jspdf.debug.js"></script>-->

<script type="text/javascript">
$(document).ready(function() {
    $("#informasi").css("display","none");
$("#vto-date").datepicker(); 
$("#vtanggal, #vstatus").select2({
minimumResultsForSearch: Infinity    
});

$('#sales_cari').on('submit',function(){
    var link=$(this).attr("action");
    if($("#vto-date").val()!=""){
        //query data
        $.ajax({
            method : "POST",    
            url : link,
            data : $(this).serialize(),
            beforeSend : function(){
                $(".preloader").css("display","block");
                $("#informasi").css("display","block");
            },success : function(data){
                $(".preloader").css("display","none"); 
                $("#informasi").css("display","none");
                if(data!="false"){
                    $("#Report-Table tbody").html(data);
                    // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());
                }else{
                    $("#Report-Table tbody").html("");
                    // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());    
                    swal("Alert","Sorry, No Data Found !", "info");    
                }
            }

        });
    }else{
        swal("Alert","Please Select Date Range.", "info");      
    }

    return false;
});


});

 function Print(data) 
    {
        var w = (screen.width);
        var h = (screen.height);
        var mywindow = window.open('', 'Print-Report', 'width='+w+',height='+h);
        mywindow.document.write('<html><head><title>Print-Report</title>');
        mywindow.document.write('<link href="<?php echo base_url() ?>/theme/css/bootstrap.css" rel="stylesheet">');
        mywindow.document.write('<link href="<?php echo base_url() ?>/theme/css/my-style.css" rel="stylesheet">');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }


</script>

