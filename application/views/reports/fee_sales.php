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
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Fee Sales</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="sales_cari" action="<?php echo site_url('Reports/fee_sales_tsa/view') ?>">

                <div class="col-md-3 col-lg-3 col-sm-3"> 
                    <select class="form-control" name="vbranch" id="vbranch">
                        <?php foreach($branch as $row){ 
                            if($vbranch == $row->branch_id){ ?>
                            <option value="<?php echo $row->branch_id?>" selected><?php echo $row->branch_id.' - '.$row->nama_branch?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $row->branch_id?>"><?php echo $row->branch_id.' - '.$row->nama_branch?></option>
                         <?php } 
                        }?>
                    </select> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <div class='input-group'>
                            <input type="text" class="form-control" placeholder="<?php echo isset($bto_date) ? $bto_date : "Date To" ?>" name="vto-date" id="vto-date"/> 
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


        <div class="Report-Toolbox col-md-6 col-lg-6 col-sm-6 col-md-offset-6 col-lg-offset-6 col-sm-offset-6">
        <button type="button" class="btn btn-primary print-btn"><i class="fa fa-print"></i> Print</button>
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
            <div id="Table-div" style="overflow: auto;">
                <table class="table table-bordered hoverTable">
                    <thead>
                        <tr>    
                            <th rowspan="2" style="background-color: purple; color: white;"><b>NO</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>BRANCH</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>TL</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>NAMA SALES</b></th>
                            <th colspan="3" style="background-color: purple; color: white;" class="text-center"><b>KATEGORI PAKET</b></th>
                            <th colspan="2" style="background-color: purple; color: white;" class="text-center"><b>KATEGORI TRANSPORT</b></th>
                            <th colspan="4" style="background-color: purple; color: white;" class="text-center"><b>FEE</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>NAMA BANK</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>NO REKENING</b></th>
                            <th rowspan="2" style="background-color: purple; color: white;"><b>ATAS NAMA</b></th>
                        </tr>
                        <tr>
                            <th style="background-color: orange; color: white;" nowrap=""><b>PAKET < 100</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>PAKET > 100</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>JUMLAH</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>KATEGORI</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>TRANSPORT</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>PAKET < 100</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>PAKET > 100</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>TRANSPORT</th>
                            <th style="background-color: orange; color: white;" nowrap=""><b>GRAND TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <p id="informasi">Information : <br> Selama proses berlangsung, diharapkan menunggu +- 4-8 menit. Silahkan untuk melakukan aktifitas yang lain.</p>
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

