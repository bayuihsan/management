<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Detail BAST : <?php echo $cno_bast?></h4></div>
<div class="col-md-5 col-lg-5 col-sm-5">
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
          <input type="text" maxlength="30" class="form-control" name="bid_users" id="bid_users" value="<?php echo $edit_bast->nama?>" readonly/>   
        </div> 
        
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->       
</div>
<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
  <div class="panel panel-default">
  <!-- Default panel contents -->
    <div class="panel-heading">-</div>
    <div class="panel-body add-client">
        
        <div class="form-group"> 
          <label for="account">Tanggal Masuk</label>
          <input type="text" maxlength="30" class="form-control" name="btanggal_masuk" id="btanggal_masuk" value="<?php echo $edit_bast->tanggal_masuk?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="account">Tanggal Terima</label>
          <input type="text" maxlength="30" class="form-control" name="btanggal_terima" id="btanggal_terima" value="<?php echo $edit_bast->tanggal_terima?>" readonly/>   
        </div> 
        <a href="<?php echo base_url()?>bast/cek_bast" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
                                <td><?php echo strtoupper($new->status) ?></td>
                                <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                                title="Click For Edit" data-dismiss="modal" href="<?php echo site_url('bast/validasi/'.$new->no_bast.'/'.$new->psb_id) ?>">validasi</a> </td>
                                
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
});

</script>

