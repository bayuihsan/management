
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Branch</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add branch</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_branch)){ ?>  
    <form id="add-branch">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="branch_id" id="branch_id" value=""/>    
      <div class="form-group">
        <label for="acc_name">Nama Branch</label>
        <input type="text" class="form-control" name="nama_branch" id="nama_branch">
      </div>
      <div class="form-group">
        <label for="balance">Ketua</label>
        <input type="text" class="form-control" name="ketua" id="ketua">
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <option value="1">Aktif</option>
          <option value="2">Tidak Aktif</option>
        </select>
      </div> 
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    <form id="add-branch">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $edit_branch->branch_id ?>"/>   
      <div class="form-group">
        <label for="nama_branch">Nama Branch</label>
        <input type="text" class="form-control" name="nama_branch" id="nama_branch" value="<?php echo $edit_branch->nama_branch ?>">
      </div>
      <div class="form-group">
        <label for="ketua">Ketua</label>
        <input type="text" class="form-control" name="ketua" id="ketua" value="<?php echo $edit_branch->ketua ?>">
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <?php if($edit_branch->status == 1){ ?>
          <option value="1" selected="selected">Aktif</option>
          <option value="2">Tidak Aktif</option>
          <?php }else{ ?>
          <option value="1">Aktif</option>
          <option value="2" selected="selected">Tidak Aktif</option>
          <?php } ?>
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
$("#asdas").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});


$('#add-branch').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('branch/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully"); 
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#nama_branch').val("");
        $("#ketua").val("");
        $('#status').val("");      
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