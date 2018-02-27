<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Cek BAST</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">BERITA ACARA SERAH TERIMA</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="msisdn_cari" action="<?php echo site_url('bast/cek_bast/view') ?>">

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <input type="text" name="cek" id="cek" class="form-control" placeholder="Cari Nomor BAST">
                </div>

                <div class="col-md-1 col-lg-1 col-sm-1"> 
                <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
                </div>
            </form>
        </div>

        <div id="load_popup_modal_show_msisdn" class="modal fade" tabindex="-1"></div>

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
            <div id="Table-div">
                <table class="table table-bordered hoverTable">
                    <thead>
                        <th>No</th>
                        <th>NO BAST</th>
                        <th>BRANCH</th>
                        <th>TANGGAL MASUK</th>
                        <th>TANGGAL TERIMA</th>
                        <th>FOS ALR</th>
                        <th>FOS GRAPARI</th>
                        <th>JUMLAH</th>
                        <th class="text-right">ACTION</th>
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

<!--<script src="<?php //echo base_url() ?>/theme/js/pdf/jspdf.debug.js"></script>-->

<script type="text/javascript">
$(document).ready(function() {

    $('#msisdn_cari').on('submit',function(){
        var link=$(this).attr("action");
        var no_bast = $("#cek").val();
        if(no_bast!=""){
            
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
                    }else if(data=="terima"){
                        swal("Alert","Received Sucessfully", "success");
                    }else{
                        $("#Report-Table tbody").html("");    
                        swal("Alert","Sorry, No Data Found !", "info");    
                    }
                }

            });
        }else{
            swal("Alert","Please Insert No BAST.", "info");      
        }

        return false;
    });

    $(document).on('click','.bast-terima-btn',function(){  
        var xno_bast = $("#xno_bast").val();
        var main=$(this);
        swal({title: "Are you sure Want To Receive This Data?",
        text: "",
        type: "warning",   showCancelButton: true,confirmButtonColor: "green",   
        confirmButtonText: "Yes, receive it!",closeOnConfirm: false,
        showLoaderOnConfirm: true }, function(){ 
            ///////////////     
            var link=$(main).attr("href");    
            $.ajax({
                url : link,
                beforeSend : function(){
                    $(".block-ui").css('display','block'); 
                },success : function(data){ 
                    //sucessAlert("Remove Sucessfully"); 
                    $(".system-alert-box").empty();
                    swal("Received!", "Receive Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                    setTimeout(function(){ document.location.href = '<?php echo base_url()?>bast/detail/'+xno_bast; }, 2000);
                }    
            });
        }); 
        return false;       
    }); 
});

</script>

