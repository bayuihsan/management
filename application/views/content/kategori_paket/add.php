<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Add Kategori Paket</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 kategori-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Kategori Paket</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_kategori)){ ?>  
    <form id="add-kategori">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_kategori" id="id_kategori" value=""/>    
      <div class="form-group">
        <label for="acc_name">Nama Kategori</label>
        <input type="text" class="form-control" name="nama_kategori" id="nama_kategori">
      </div>
      <div class="form-group">
        <label for="balance">Harga Kategori</label>
        <input type="text" class="form-control" name="harga_kategori" id="harga_kategori">
      </div> 
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    <form id="add-kategori">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_kategori" id="id_kategori" value="<?php echo $edit_kategori->id_kategori ?>"/>   
      <div class="form-group">
        <label for="nama_kategori">Nama Kategori</label>
        <input type="text" class="form-control" name="nama_kategori" id="nama_kategori" value="<?php echo $edit_kategori->nama_kategori?>">
      </div>
      <div class="form-group">
        <label for="harga_kategori">Harga Kategori</label>
        <input type="text" class="form-control" name="harga_kategori" id="harga_kategori" value="<?php echo $edit_kategori->harga_kategori ?>">
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
$("#harga_kategori").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});


$('#add-kategori').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('kategori_paket/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully"); 
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#nama_Kategori').val("");
        $("#harga_Kategori").val("");      
      }
      document.location.href = '<?php echo base_url()?>/kategori_paket';
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