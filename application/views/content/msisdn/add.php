<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>MSISDN</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->
<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add MSISDN</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_msisdn)){ ?>  
    <form id="add-paket">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_haloinstan" id="id_haloinstan" value=""/>    
      <div class="form-group">
        <label for="acc_name">MSISDN</label>
        <input type="text" class="form-control" name="msisdn" id="msisdn">
      </div>
      <div class="form-group">
        <label for="balance">Tipe</label>
        <input type="text" class="form-control" name="tipe" id="tipe">
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="aktif" class="form-control">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
      </div>
      <div class='form-group'>
        <label>ID Users</label>
        <input type="text" class="form-control" name="id_users" id="id_users" value="<?php echo $this->session->userdata('id_users');?>" readonly>
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>

    </div>
  <div class="row">  
   <div class="col-md-6"> 
   <div class="card">
  <div class="card-body">
      <ul class="list-group">
<button type="button" class="list-group-item list-group-item-dark">
    Masukan Data MSISDN Dari Excel
  </button>
  <li class="list-group-item">
<?php echo form_open_multipart('upload/update');?>
<input type="file" name="file" size="20" id='files'/>
<br/><br/>
<button type="submit" class="btn btn-primary">Save</button>
</form></li>   
</ul>
</div></div>

 </div></div>
</div>
    <?php }else{ ?>

    <form id="add-paket">
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

$('#add-paket').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('msisdn/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully"); 
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#msisdn').val("");
        $('#tipe').val("");
        $("#status").val("");
        $('#id_users').val("");      
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
<script type="text/javascript">
      var file = document.getElementById('files');
         file.onchange = function(e){
            var ext = this.value.match(/\.([^\.]+)$/)[1];
            switch(ext)
            {
                case 'xlsx':
                    //alert('allowed');
                    break;
                default:
                    alert('Maaf file bukan .xlsx');
                    this.value='';
            }
    };
</script>
