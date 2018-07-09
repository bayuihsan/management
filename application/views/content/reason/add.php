
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Reason</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<?php $status=array('sukses'=>'sukses',
  'valid'=>'valid',
  'cancel'=>'cancel',
  'reject'=>'reject',
  'pending'=>'pending',
  'retur'=>'retur',
  'blacklist'=>'blacklist',
  'bentrok'=>'bentrok',
  'masuk'=>'masuk'); ?>
<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Reason</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_reason)){ ?>  
    <form id="add-reason">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_reason" id="id_reason" value=""/>  
      <div class="form-group"> 
        <label for="status">Status</label>
        <select name="sstatus" class="form-control" id="sstatus">  
          <option value="">Pilih Status</option>
          <?php foreach($status as $status) { ?>
            <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
          <?php } ?>
        </select>      
      </div>  
      <div class="form-group">
        <label for="acc_name">Reason</label>
        <input type="text" class="form-control" name="nama_reason" id="nama_reason">
      </div> 
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/reason_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-reason">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_reason" id="id_reason" value="<?php echo $edit_reason->id_reason ?>"/>   
      <div class="form-group"> 
        <label for="status">Status</label>
        <select name="sstatus" class="form-control" id="sstatus">  
          <option value="">Pilih Status</option>
          <?php foreach($status as $status) { 
            if($edit_reason->status == $status){ ?>
            <option value="<?php echo $status; ?>" selected><?php echo $status; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
            <?php }
          } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="nama_reason">Reason</label>
        <input type="text" class="form-control" name="nama_reason" id="nama_reason" value="<?php echo $edit_reason->nama_reason ?>">
      </div>
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/reason_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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

    $("#sstatus").select2();
    //for number only
    $("#asdas").keypress(function (e) {
      //if the letter is not digit then display error and don't type anything
      if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
      }
    });


    $('#add-reason').on('submit',function(){    
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('Admin/reason_add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          // sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='update'){        
            $('#sstatus').select2("val","");    
            $('#nama_reason').val("");    
          }
          swal("Saved!", "Saved Sucessfully", "success");
          setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/reason_view'; }, 2000);
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