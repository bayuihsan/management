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
?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Daily Status</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="status_cari" action="<?php echo site_url('Reports/status_daily/view') ?>">

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
                        <select name="bulan" id="bulan" class="form-control">
                          <?php
                          $xbulan=array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                          $jlh_bln=count($xbulan);
                          for($c=1; $c<$jlh_bln; $c++){
                              echo"<option value=$c> $xbulan[$c] </option>";
                          }
                          ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
                          <?php
                          $now=date('Y');
                          for ($a=$now;$a>=2016;$a--)
                          {
                               echo "<option value='$a'>$a</option>";
                          }
                          ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-1 col-lg-1 col-sm-1"> 
                <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
                </div>
            </form>
        </div>


        <div class="Report-Toolbox col-md-6 col-lg-6 col-sm-6 col-md-offset-6 col-lg-offset-6 col-sm-offset-6">
        <?php /*<button type="button" class="btn btn-primary print-btn"><i class="fa fa-print"></i> Print</button>
        <button type="button" class="btn btn-info pdf-btn"><i class="fa fa-file-pdf-o"></i> PDF Export</button>*/?>
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

            <div id="status-table-div" style="overflow: auto;">
                <table class="table table-bordered hoverTable">
                    <thead>
                        <th style="background-color: whitesmoke">STATUS</th>
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
                        ?>
                            <th style="background-color: whitesmoke" class="text-center">Tanggal</th>
                        
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
$("#vto-date").datepicker(); 
$("#vbranch").select2();
$("#vtanggal, #vstatus").select2({
minimumResultsForSearch: Infinity    
});

$('#status_cari').on('submit',function(){
    var link=$(this).attr("action");
    //query data
    $.ajax({
        method : "POST",    
        url : link,
        data : $(this).serialize(),
        beforeSend : function(){
            $(".preloader").css("display","block");
        },success : function(data){
            $(".preloader").css("display","none"); 
            if(data!="false"){
                $("#Report-Table #status-table-div").html(data);
                // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());
            }else{
                $("#Report-Table #status-table-div").html("");
                // $(".report-heading p").html("Date From "+$("#from-date").val()+" To "+$("#to-date").val());    
                swal("Alert","Sorry, No Data Found !", "info");    
            }
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

