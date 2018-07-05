<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Cek MSISDN</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">MSISDN</div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="msisdn_cari" action="<?php echo site_url('Admin/sales_cek_msisdn/view') ?>">

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <input type="text" name="cek" id="cek" class="form-control" placeholder="Cari MSISDN">
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
                        <th>MSISDN</th>
                        <th>Nama Pelanggan</th>
                        <th>Branch</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Validasi</th>
                        <th>Tanggal Aktif</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
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
        var msisdn = $("#cek").val();
        if(msisdn!=""){
            if(msisdn.substring(0,3)!=628){
              alert("MSISDN harus diawali 628");
              return false;
            }else{
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
                        }else{
                            $("#Report-Table tbody").html("");    
                            swal("Alert","Sorry, No Data Found !", "info");    
                        }
                    }

                });
            }
        }else{
            swal("Alert","Please Insert MSISDN.", "info");      
        }

        return false;
    });
});

</script>

