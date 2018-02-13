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
      <input type="hidden" name="id_feedbacks" id="id_feedbacks" value=""/>    
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
        <textarea value="saran" style="width: 630px; height: 80px"></textarea>
      </div>
      <div class="form-group">
        <label for="balance">ID Users</label>
        <input type="text" class="form-control" name="id_users" id="id_users">
      </div>
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    
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