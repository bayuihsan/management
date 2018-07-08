
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Region</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 region-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Region</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_region)){ ?>  
    <form id="add-region">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_region" id="id_region" value=""/>    
      <div class="form-group">
        <label for="acc_name">Nama region</label>
        <input type="text" class="form-control" name="nama_region" id="nama_region">
      </div>
      <div class="form-group">
        <label for="status_region">Status</label>
        <select name="status_region" class="form-control">
          <option value="">Pilih Status</option>
          <option value="1">Aktif</option>
          <option value="2">Tidak Aktif</option>
        </select>
      </div> 
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by_x" id="update_by_x" value="<?php echo $this->session->userdata('nama'); ?>" readonly>
        <input type="hidden" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/region_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-region">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_region" id="id_region" value="<?php echo $edit_region->id_region ?>"/>   
      <div class="form-group">
        <label for="nama_region">Nama region</label>
        <input type="text" class="form-control" name="nama_region" id="nama_region" value="<?php echo $edit_region->nama_region ?>">
      </div>
      <div class="form-group">
        <label for="status_region">Status</label>
        <select name="status_region" class="form-control">
          <option value="">Pilih Status</option>
          <?php if($edit_region->status_region == 1){ ?>
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
        <input type="text" class="form-control" name="update_by_x" id="update_by_x" value="<?php echo $this->session->userdata('nama'); ?>" readonly>
        <input type="hidden" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>    
          
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/region_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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


    $('#add-region').on('submit',function(){    
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('Admin/region_add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='update'){        
            $('#nama_region').val("");
            $('#status_region').val("");      
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