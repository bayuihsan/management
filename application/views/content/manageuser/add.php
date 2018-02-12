<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Sales Person</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Sales Person</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_salesperson)){ ?>  
    <form id="add-salesperson">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="sales_id" id="sales_id" value=""/>    
      <div class="form-group">
        <label for="acc_name">User Sales</label>
        <input type="text" class="form-control" name="user_sales" id="user_sales">
      </div>
      <div class="form-group">
        <label for="balance">Nama Sales</label>
        <input type="text" class="form-control" name="nama_sales" id="nama_sales">
      </div>
      <div class="form-group">
        <label for="balance">Branch</label>
        <select name="branch_id" class="form-control" id="branch_id">
          <option value="0">-- Pilih Branch --</option>  
          <?php foreach ($pilihbranch as $pb) {?>
          <option value="<?php echo $pb->branch_id?>"><?php echo $pb->nama_branch ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">ID Users</label>
        <select name="id_users" class="form-control" id="id_users">
          <option value="0">-- Pilih ID User --</option>  
          <?php foreach ($pilihid as $pi) {?>
          <option value="<?php echo $pi->id_users?>"><?php echo $pi->nama ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">No Telp</label>
        <input type="text" class="form-control" name="no_telp" id="no_telp">
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank">
      </div>
      <div class="form-group">
        <label for="balance">No. Rekening</label>
        <input type="text" class="form-control" name="no_rekening" id="no_rekening">
      </div>
      <div class="form-group">
        <label for="balance">Atas Nama</label>
        <input type="text" class="form-control" name="atas_nama" id="atas_nama">
      </div>

<!--      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div> -->    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    <form id="add-salesperson">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="paket_id" id="paket_id" value="<?php echo $edit_paket->paket_id ?>"/>   
      <div class="form-group">
        <label for="nama_paket">Nama Paket</label>
        <input type="text" class="form-control" name="nama_paket" id="nama_paket" value="<?php echo $edit_paket->nama_paket ?>">
      </div>
      <div class="form-group">
        <label for="ketua">Harga Paket</label>
        <input type="text" class="form-control" name="harga_paket" id="harga_paket" value="<?php echo $edit_paket->harga_paket ?>">
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select name="aktif" class="form-control">
          <option value="">Pilih Status</option>
          <?php if($edit_paket->aktif == "y"){ ?>
          <option value="y" selected="selected">Aktif</option>
          <option value="n">Tidak Aktif</option>
          <?php }else{ ?>
          <option value="y">Aktif</option>
          <option value="n" selected="selected">Tidak Aktif</option>
          <?php } ?>
        </select>
      </div>
      <div class='form-group'>
        <label>Kategori</label>
        <select name="id_kategori" class="form-control" id="id_kategori">
          <option value="0">-- Pilih Kategori --</option>  
          <?php foreach ($kategori_paket as $kp) {
            if($edit_paket->id_kategori == $kp->id_kategori){
            ?>
          <option value="<?php echo $kp->id_kategori?>" selected><?php echo $kp->nama_kategori ?></option>
          <?php } else { ?>
          <option value="<?php echo $kp->id_kategori?>"><?php echo $kp->nama_kategori ?></option>  
          <?php }
          }?>
        </select>
      </div> 
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
          
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>

 <?php } ?>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>


</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->
<script type="text/javascript">
$(document).ready(function(){

if($(".sidebar").width()=="0"){
  $(".main-content").css("padding-left","0px");
} 

//for number only
$("#harga_paket").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});
$('#id_kategori').select2();
$('#id_users').select2();
$('#branch_id').select2();
$("#branch_id").change(function(){
    var branch_id = {branch_id:$("#branch_id").val()};
    //alert(sales_channel);
    $.ajax({
       type: "POST",
       url : "<?php echo base_url(); ?>sales_person/tl",
       data: branch_id,
       success: function(msg){
        $('#id_users').html(msg);
       }
    });
  });

$('#add-salesperson').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('salesperson/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully");
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#user_sales').val("");
        $('#nama_sales').val("");
        $("#branch_id").val("");
        $('#id_users').val("");      
        $('#no_telp').val("");      
        $('#status').val("");      
        $('#nama_bank').val("");      
        $('#no_rekening').val("");      
        $('#atas_nama').val("");      
      }
    }else{
      failedAlert2(data);
      $(".block-ui").css('display','none');
    }   
    }
  });    
  return false;

});

});
</script>