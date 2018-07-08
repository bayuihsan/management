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
<div class="panel panel-default" style="font-size: 10px; font-family: tahoma;">
    <!-- Default panel contents -->
    <div class="panel-heading">Summary by branch</div>
    <div class="panel-body" >
        <div class="col-md-12 col-lg-12 col-sm-12 report-params" >
            <form id="sales_cari" action="<?php echo site_url('Admin/reports_allbranch/view') ?>">

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <select class="form-control" name="opsi" id="opsi">
                        <option value="">Pilih Opsi</option>
                        <option value="opsi1">Opsi 1</option>
                        <option value="opsi2">Opsi 2</option>
                        <option value="opsi3">Opsi 3</option>
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
        <a class="mybtn btn-default export-btn" style="float: right" href="<?php echo site_url('Admin/reports_allbranch') ?>" >Export to Excel</a>
        </div>
        <div id="Report-Table" class="col-md-12 col-lg-12 col-sm-12" style="overflow: auto;">
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
            <div id="Table-div" >
                <table class="table table-bordered hoverTable">
                    <thead style="background-color: whitesmoke;">
                        <th>NO</th>
                        <th>BRANCH</th>
                        <th class="text-right">AKTIF</th>
                        <th class="text-right">VALID</th>
                        <th class="text-right">CANCEL</th>
                        <th class="text-right">REJECT</th>
                        <th class="text-right">PENDING</th>
                        <th class="text-right">RETUR</th>
                        <th class="text-right">BENTROK</th>
                        <th class="text-right">BLACKLIST</th>
                        <th class="text-right">ON PROCESS</th>
                        <th class="text-right"><span id="total">DATA MASUK</span></th>
                        <th class="text-right">% SUKSES</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <p>Keterangan : <br> 
                    <ol>
                        <li>OPSI 1 : Semua nilai berdasarkan dari tanggal masuk</li>
                        <li>OPSI 2 : Semua nilai dari tanggal masuk tapi Nilai data sukses berdasarkan tanggal aktif</li>
                        <li>OPSI 3 : Semua nilai berdasarkan tanggal terakhir transaksi per status</li>
                    </ol>
                </p>
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
    $("#vto-date").datepicker(); 
    $("#opsi").select2({
    minimumResultsForSearch: Infinity    
    });

    $('#sales_cari').on('submit',function(){
        var link=$(this).attr("action");
        if($("#vto-date").val()!="" && $("#opsi").val()!=""){
            //query data
            if($("#opsi").val()=="opsi3"){
                $("#total").html("TOTAL DATA");
            }else{
                $("#total").html("DATA MASUK");
            }
            $.ajax({
                method : "POST",    
                url : link,
                data : $(this).serialize(),
                beforeSend : function(){
                    $(".preloader").css("display","block");
                },success : function(data){
                    $(".preloader").css("display","none"); 
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
            swal("Alert","Please Select Opsi & Date Range.", "info");      
        }

        return false;
    });

    $(document).on('click','.export-btn',function(){

        var link=$(this).attr("href"); 
        var opsi = $("#opsi").val();
        var vtodate = $("#vto-date").val();
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                window.open(link+'/export/'+opsi+'/'+vtodate);
                $(".block-ui").css('display','none');               
            }
        });

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

