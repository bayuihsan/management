<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Data CTP</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-6 col-lg-6 col-sm-6 ctp-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add CTP</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_ctp)){ ?>  
    <form id="add-ctp">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_ctp" id="id_ctp" value=""/>    
      <div class="form-group">
        <label for="acc_name">Order ID</label>
        <input type="text" class="form-control" name="order_id" id="order_id">
      </div>
      <div class="form-group">
        <label>Order Submit Date</label>
        <div class='input-group date' id='date'>
        <input type='text' name="order_submit_date" id="order_submit_date" class="form-control" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
     </div>
      <div class="form-group">
        <label>Order Completed Date</label>
        <div class='input-group date' id='date2'>
        <input type='text' name="order_completed_date" id="order_completed_date" class="form-control" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
     </div>
      <div class="form-group">
        <label for="balance">MSISDN</label>
        <input type="text" class="form-control" name="msisdn_ctp" id="msisdn_ctp">
      </div>
      <div class="form-group">
        <label for="balance">Nama Pelanggan</label>
        <input type="text" class="form-control" name="nama_pelanggan_ctp" id="nama_pelanggan_ctp">
      </div>
      <div class="form-group">
        <label for="balance">Order Type Name</label>
        <input type="text" class="form-control" name="order_type_name" id="order_type_name">
      </div>
      <div class="form-group">
        <label for="balance">User ID</label>
        <input type="text" class="form-control" name="user_id" id="user_id">
      </div>
      <div class="form-group">
        <label for="balance">Employee Name</label>
        <input type="text" class="form-control" name="employee_name" id="employee_name">
      </div>
      <div class='form-group'>
        <label>Paket</label>
        <select name="paket_id" class="form-control" id="paket_id">
          <option value="0">-- Pilih Paket --</option>  
          <?php foreach ($paket as $p) {?>
          <option value="<?php echo $p->paket_id?>"><?php echo $p->nama_paket ?></option>
          <?php } ?>
        </select>
      </div>
      <div class='form-group'>
        <label>Admin CTP</label>
        <select name="id_users" class="form-control" id="id_users">
          <option value="0">-- Pilih Admin CTP --</option>  
          <?php foreach ($users as $u) {?>
          <option value="<?php echo $u->id_users?>"><?php echo $u->nama ?></option>
          <?php } ?>
        </select>
      </div>
      <div class='form-group'>
        <label>Branch</label>
        <select name="cbranch_id" class="form-control" id="cbranch_id">
          <option value="0">-- Pilih Branch --</option>  
          <?php foreach ($branch as $b) {?>
          <option value="<?php echo $b->branch_id?>"><?php echo $b->nama_branch ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">Tanggal Upload</label>
        <input type="text" class="form-control" name="tgl_upload" id="tgl_upload" value="<?php echo date('Y-m-d')?>" readonly>
      </div>

      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>ctp/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>

    <?php }else{ ?>
    <form id="add-ctp">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_ctp" id="id_ctp" value="<?php echo $edit_ctp->id_ctp ?>"/>    
      <div class="form-group">
        <label for="acc_name">No HP</label>
        <input type="text" class="form-control" name="no_hp" id="no_hp" value="<?php echo $edit_feedbacks->no_hp ?>">
      </div>
      <div class="form-group">
        <label for="balance">Nama Pelanggan</label>
        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" value="<?php echo $edit_feedbacks->nama_pelanggan ?>">
      </div>
      <div class="form-group">
        <label for="balance">Kota</label>
        <input type="text" class="form-control" name="kota" id="kota" value="<?php echo $edit_feedbacks->kota ?>">
      </div>
      <div class="form-group">
        <label for="balance">Saran</label>
        <textarea id="saran" name="saran" class="form-control" style="width: 630px; height: 80px"><?php echo $edit_feedbacks->saran ?></textarea>
      </div>
      <div class="form-group">
        <label for="balance">ID Users</label>
        <input type="text" class="form-control" name="id_users" id="id_users" value="<?php echo $this->session->userdata('id_users')?>" readonly>
      </div>
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>feedbacks/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
         
 <?php } ?>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>

<div  class="col-lg-6">
<p>Do you want to upload MSISDN File?</p>
<form action="upload" method="post" enctype="multipart/form-data">  
<table class="table table-bordered">
  <tr>
    <td width="150">MSISDN FILE</td>
    <td>
      <div class="col-md-10">  
        <input type="file" name="msisdn_file" accept=".xls,.csv" style="margin-top:15px;" required="required" />  (Format .xls)
        <input type="hidden" name="id_users_up" value="<?php echo $this->session->userdata('id_users');?>">
        <input type="hidden" name="branch_id_up" value="<?php echo $this->session->userdata('branch_id');?>">
      </div>
    </td>
  </tr>
  <tr>
    <td></td>
    <td>
      <div class="col-md-5">  
        <input type="submit" name="upload" value="Upload" style="margin-top:10px;" class="btn btn-info" />  
      </div>  
      <div class="col-md-6">  
        <a href="<?php echo base_url()?>assets/ctp/template/file_upload.xls" download="file_upload.xls">Download Format File</a>
        <a href="<?php echo base_url()?>assets/ctp/template/Kode_Paket.xls" download="Kode_Paket.xls"><p style="color:red;">Download Kode paket</p></a>
      </div>
      <div style="clear:both"></div>  
    </td>
  </tr>
</table>
</form>
 </div>


</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->
<script type="text/javascript">
$(document).ready(function(){
$("#date").datepicker();
$("#date2").datepicker();
$('#id_users').select2();
$('#paket_id').select2();
$('#cbranch_id').select2();

  if($(".sidebar").width()=="0"){
    $(".main-content").css("padding-left","0px");
  } 
  //for number only
  $("#no_hp").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#add-ctp').on('submit',function(){    
    $.ajax({
      method : "POST",
      url : "<?php echo site_url('ctp/add/insert') ?>",
      data : $(this).serialize(),
      beforeSend : function(){
        $(".block-ui").css('display','block'); 
      },success : function(data){ 
      if(data=="true"){  
        sucessAlert("Saved Sucessfully"); 
        $(".block-ui").css('display','none'); 
        if($("#action").val()!='update'){        
          $('#no_telp').val("");
          $('#nama_pelanggan').val("");
          $("#kota").val("");
          $('#saran').val("");      
        }
      }else{
        failedAlert2(data);
        $(".block-ui").css('display','none');
      }   
      }
    });    
    return false;

  });
  $(document).on('click','.kembali',function(){

      var link=$(this).attr("href"); 
      
      $(".block-ui").css('display','block'); 
   
      history.pushState(null, null,link);  
      $('.asyn-div').load(link+'/asyn',function() {
          $(".block-ui").css('display','none');     
      });     
            

      return false;
  });

});
</script>