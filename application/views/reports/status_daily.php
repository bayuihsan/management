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
    <div class="panel-heading">Paket</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="sales_cari" action="<?php echo site_url('Reports/paket/view') ?>">

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

            <div id="Table-div" style="overflow: auto;">
                <table class="table table-bordered hoverTable">
                    <thead>
                        <th>Status</th>
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
                        $tahun = '2017'; //Mengambil tahun saat ini
                        $bulan = '07'; //Mengambil bulan saat ini
                        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                        for ($i=1; $i < $tanggal+1; $i++) { ?>
                        <th><?php echo $i?></th>
                        <?php }
                        ?>
                    </thead>
                    <tbody>
                        <?php foreach($status as $st){ ?>
                        <tr>
                            <td><?php echo $st?></td>
                            <?php for ($i=1; $i < $tanggal+1; $i++) { ?>
                            <td>
                                <?php 
                                if($st == "sukses" || $st == "bentrok" || $st == "blacklist"){
                                    $tgl = "tanggal_aktif";
                                }else if($st == "valid" || $st == "cancel" || $st == "reject" || $st == "pending" || $st == "retur"){
                                    $tgl = "tanggal_validasi";
                                }else if($st == "masuk"){
                                    $tgl = "tanggal_masuk";
                                }
                                $q_jumlah = $this->db->query("select count(psb_id) jumlah 
                                        from new_psb 
                                        where status = '".$st."' and branch_id='5'
                                        and DATE_FORMAT(".$tgl.",'%Y')='".$tahun."'
                                        and DATE_FORMAT(".$tgl.", '%m')='".$bulan."'
                                        and DATE_FORMAT(".$tgl.", '%d')='".$i."' ")->row(); echo $q_jumlah->jumlah;?>
                                    
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
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

$('#sales_cari').on('submit',function(){
    var link=$(this).attr("action");
    var to_date = $("#vto-date").val();
    var last_month = new Date(to_date).getMonth()-1;
    var this_month = new Date(to_date).getMonth();
    var NamaBulan = new Array("Januari", "Februari", "Maret", "April", "Mei",
"Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    if(to_date!=""){
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
                    $("#Report-Table tbody").html(data);
                    $("#last_month").html(NamaBulan[last_month]);
                    $("#this_month").html(NamaBulan[this_month]);
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

