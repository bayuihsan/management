<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Grapari</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Grapari</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_grapari)){ ?>  
    <form id="add-grapari">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_grapari" id="id_grapari" value=""/>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) {?>
          <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="balance">Nama Grapari</label>
        <input type="text" class="form-control" name="nama_grapari" id="nama_grapari">
      </div>
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/grapari_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-grapari">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_grapari" id="id_grapari" value="<?php echo $edit_grapari->id_grapari ?>"/>   
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) { 
            if($edit_grapari->branch_id == $new->branch_id){ ?>
            <option value="<?php echo $new->branch_id ?>" selected><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php }else{ ?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
           } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="ketua">Nama Grapari</label>
        <input type="text" class="form-control" name="nama_grapari" id="nama_grapari" value="<?php echo $edit_grapari->nama_grapari ?>">
      </div> 
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/grapari_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
  $("#sdsd").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#branch').select2();

  $('#add-grapari').on('submit',function(){     
    $.ajax({
      method : "POST",
      url : "<?php echo site_url('Admin/grapari_add/insert') ?>",
      data : $(this).serialize(),
      beforeSend : function(){
        $(".block-ui").css('display','block'); 
      },success : function(data){ 
      if(data=="true"){  
        sucessAlert("Saved Sucessfully"); 
        $(".block-ui").css('display','none'); 
        if($("#action").val()!='update'){        
          $('#branch').select2("val","");
          $('#nama_grapari').val(""); 
        }
        swal("Saved!", "Saved Sucessfully", "success");
        setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/grapari_add'; }, 2000);
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