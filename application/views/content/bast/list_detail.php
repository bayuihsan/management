<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Detail BAST : <?php echo $cno_bast?></h4></div>

<div class="col-md-4 col-lg-4 col-sm-4">
<!--Start Panel-->
  <div class="panel panel-default">
  <!-- Default panel contents -->
    <div class="panel-heading">Data BAST</div>
    <div class="panel-body add-client">
        <input type="hidden" name="id_header" id="id_header" value="<?php echo $edit_bast->id_header?>"/>    
        <div class="form-group"> 
          <label for="account">NO BAST</label>
          <input type="text" maxlength="30" class="form-control" name="bno_bast" id="bno_bast" value="<?php echo $edit_bast->no_bast?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="account">BRANCH</label>
          <input type="text" maxlength="30" class="form-control" name="bbranch" id="bbranch" value="<?php echo $edit_bast->nama_branch?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="account">FOS ALR</label>
          <input type="text" maxlength="30" class="form-control" name="bid_users" id="bid_users" value="<?php echo strtoupper($edit_bast->nama)?>" readonly/>   
        </div> 
        
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->       
</div>

<div class="col-md-4 col-lg-4 col-sm-4">
<!--Start Panel-->
  <div class="panel panel-default">
  <!-- Default panel contents -->
    <div class="panel-heading">-</div>
    <div class="panel-body add-client">
        
        <div class="form-group"> 
          <label for="account">TANGGAL MASUK</label>
          <input type="text" maxlength="30" class="form-control" name="btanggal_masuk" id="btanggal_masuk" value="<?php echo $edit_bast->tanggal_masuk?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="account">TANGGAL TERIMA</label>
          <input type="text" maxlength="30" class="form-control" name="btanggal_terima" id="btanggal_terima" value="<?php echo $edit_bast->tanggal_terima?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="account">FOS GRAPARI</label>
          <input type="text" maxlength="30" class="form-control" name="bid_penerima" id="bid_penerima" value="<?php echo strtoupper($edit_bast->nama_penerima)?>" readonly/>   
        </div>
        
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->       
</div>

<div class="col-md-4 col-lg-4 col-sm-4">
<!--Start Panel-->
  <div class="panel panel-default">
  <!-- Default panel contents -->
    <div class="panel-heading">-</div>
    <div class="panel-body add-client">
        <input type="hidden" name="xno_bast" id="xno_bast" value="<?php echo $cno_bast?>">
      <?php if(empty($edit_bast->tanggal_terima) && ($this->session->userdata('level')>5 || $this->session->userdata('level')==4)){ ?>
        <a class="mybtn btn-success bast-terima-btn" style="cursor: pointer;" data-toggle="tooltip" title="Click For Receive" href="<?php echo site_url('Admin/cek_bast/receive/'.$edit_bast->id_header) ?>">Terima</a>
      <?php } ?>
      <a href="<?php echo base_url()?>Admin/bast_export/<?php echo $cno_bast?>" style="cursor: pointer;" data-toggle="tooltip" title="Click For Export BAST" class="mybtn btn-success export-btn"><i class="fa fa-file-excel-o"></i> Export</a>
      <?php if($this->session->userdata('level')==4 || $this->session->userdata('level')==5){ ?>
        <a href="<?php echo base_url()?>Admin/bast_add/<?php echo $cno_bast?>" style="cursor: pointer;" data-toggle="tooltip" title="Click For Add MSISDN" class="mybtn btn-default"><i class="fa fa-plus"></i> Tambah</a>
      <?php } ?>
        <a href="<?php echo base_url()?>Admin/cek_bast" style="cursor: pointer;" data-toggle="tooltip" title="Click For Back" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
        
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->       
</div>



<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Detail MSISDN <div class="add-button">
    </div></div>
    <div class="panel-body">
        <div id="Table-div">
            <table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
                <thead>    
                    <th>NO</th>
                    <th>MSISDN</th>
                    <th>NAMA PELANGGAN</th>
                    <th>BRANCH</th>
                    <th>PAKET</th>
                    <th>TL</th>
                    <th>SALES PERSON</th>
                    <th>TANGGAL MASUK</th>
                    <th>TANGGAL VALIDASI</th>
                    <th>TANGGAL AKTIVASI</th>
                    <th>STATUS</th>
                    <th class="single-action">ACTION</th>
                </thead>

                <tbody>
                    <?php $no=1;
                        foreach($detail_bast as $new) { ?>    
                            <tr>
                                <td class="date"><?php echo $no++; ?></td>
                                <td><?php echo strtoupper($new->msisdn) ?></td>
                                <td><?php echo strtoupper($new->nama_pelanggan) ?></td>
                                <td><?php echo strtoupper($new->nama_branch) ?></td>
                                <td><?php echo strtoupper($new->nama_paket) ?></td>
                                <td><?php echo strtoupper($new->TL) ?></td>
                                <td><?php echo strtoupper($new->sales_person) ?></td>
                                <td><?php echo isset($new->tanggal_masuk) ? date('Y-m-d', strtotime($new->tanggal_masuk)) : ''; ?></td>
                                <td><?php echo isset($new->tanggal_validasi) ? date('Y-m-d', strtotime($new->tanggal_validasi)) : ''; ?></td>
                                <td><?php echo isset($new->tanggal_aktif) ? date('Y-m-d', strtotime($new->tanggal_aktif)) : ''; ?></td>
                                <td><?php echo strtoupper($new->status) ?></td>
                                <td>
                                  <?php if(($this->session->userdata('level')==4 || $this->session->userdata('level')>5) && $this->session->userdata('branch_id')==$new->branch_id && $edit_bast->tanggal_terima!=''){  
                                    if($new->status=='valid' && ($this->session->userdata('level')==7 || $this->session->userdata('level')==4)){ ?>
                                  <a class="mybtn btn-success btn-xs edit-btn" data-toggle="tooltip" 
                                title="Click For Aktifasi" data-dismiss="modal" href="<?php echo site_url('Admin/bast_aktivasi/'.$new->no_bast.'/'.$new->psb_id) ?>">Aktivasi</a>
                                  <?php }else{ 
                                        if($new->status!="sukses" && $this->session->userdata('level')==6){  ?>
                                  <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                                title="Click For Validasi" data-dismiss="modal" href="<?php echo site_url('Admin/bast_validasi/'.$new->no_bast.'/'.$new->psb_id) ?>">Validasi</a> <?php }}}?> </td>
                                
                            </tr>
                    <?php } ?>
                    
                </tbody>       

            </table>
        </div>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    

</div>


</div><!--End Inner container-->
</div> <!-- End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->


<script type="text/javascript">
$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });   
        $(".manage-client").niceScroll({
        cursorwidth: "8px",cursorcolor:"#7f8c8d"
    });

    $("#sales-table").DataTable();
    $(".dataTables_length select").addClass("show_entries");

    $(document).on('click','.bast-terima-btn',function(){  
        var xno_bast = $("#xno_bast").val();
        var main=$(this);
        swal({title: "Are you sure Want To Receive This Data?",
        text: "",
        type: "info",   showCancelButton: true,confirmButtonColor: "green",   
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
                    setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/bast_detail/'+xno_bast; }, 2000);
                }    
            });
        }); 
        return false;       
    });

    $(document).on('click','.export-btn',function(){

        var link=$(this).attr("href"); 
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                window.open(link+'/asyn','_blank');
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

