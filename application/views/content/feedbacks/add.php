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
    <div class="panel-heading">Add Feedback</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_feedbacks)){ ?>  
    <form id="add-feedbacks">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_feedback" id="id_feedback" value=""/>    
      <div class="form-group">
        <label for="acc_name">No HP</label>
        <input type="text" class="form-control" name="no_hp" id="no_hp">
      </div>
      <div class="form-group">
        <label for="balance">Nama Pelanggan</label>
        <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan">
      </div>
      <div class="form-group">
        <label for="balance">Kota</label>
        <input type="text" class="form-control" name="kota" id="kota">
      </div>
      <div class="form-group">
        <label for="balance">Saran</label>
        <textarea id="saran" name="saran" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <label for="balance">ID Users</label>
        <input type="text" class="form-control" name="id_users" id="id_users" value="<?php echo $this->session->userdata('id_users')?>" readonly>
      </div>
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>feedbacks/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>
    <form id="add-feedbacks">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_feedback" id="id_feedback" value="<?php echo $edit_feedbacks->id_feedback ?>"/>    
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
  $("#no_hp").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#add-feedbacks').on('submit',function(){    
    $.ajax({
      method : "POST",
      url : "<?php echo site_url('feedbacks/add/insert') ?>",
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
      // alert(link);
      $.ajax({
          method : "POST",
          url : link,
          beforeSend : function(){
              $(".block-ui").css('display','block'); 
          },success : function(data){ 
              //var link = location.pathname.replace(/^.*[\\\/]/, ''); //get filename only  
              history.pushState(null, null,link);  
              $('.asyn-div').load(link+'/asyn',function() {
                  $(".block-ui").css('display','none');     
              });     
             
          }
      });

      return false;
  });

});
</script>