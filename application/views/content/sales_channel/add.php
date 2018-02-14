<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Sales Channel</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<?php $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom'); ?>
<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Sales Channel</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_sales_channel)){ ?>  
    <form id="add-sales_channel">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_channel" id="id_channel" value=""/>    
      <div class="form-group"> 
        <label for="sales_channel">Channel</label>
        <select name="sales_channel" class="form-control" id="sales_channel">  
          <option value="">Pilih Channel</option>
          <?php for($i=0; $i<count($channel); $i++) { ?>
          <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$channel[$i]; ?></option>
          <?php } ?>
        </select>      
      </div>
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
        <label for="balance">Sub Channel</label>
        <input type="text" class="form-control" name="sub_channel" id="sub_channel">
      </div>
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>sales_channel" class="mybtn btn-warning"><i class="fa fa-check"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-sales_channel">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $edit_sales_channel->id_channel ?>"/>   
      <div class="form-group"> 
        <label for="sales_channel">Channel</label>
        <select name="sales_channel" class="form-control" id="sales_channel">  
          <option value="">Pilih Channel</option>
          <?php for($i=0; $i<count($channel); $i++) { 
              if($edit_sales_channel->sales_channel == $i){ ?>
            <option value="<?php echo $i; ?>" selected><?php echo "(".$i.") ".$channel[$i]; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$channel[$i]; ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) { 
            if($edit_sales_channel->branch_id == $new->branch_id){ ?>
            <option value="<?php echo $new->branch_id ?>" selected><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php }else{ ?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
          
           } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="ketua">Sub Channel</label>
        <input type="text" class="form-control" name="sub_channel" id="sub_channel" value="<?php echo $edit_sales_channel->sub_channel ?>">
      </div> 
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
          
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>sales_channel" class="mybtn btn-warning"><i class="fa fa-check"></i> Back</a>
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
$('#sales_channel, #branch').select2();

$('#add-sales_channel').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('sales_channel/add/insert') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Saved Sucessfully"); 
      $(".block-ui").css('display','none'); 
      if($("#action").val()!='update'){        
        $('#nama_sales_channel').val("");
        $('#harga_sales_channel').val("");
        $("#aktif").val("");
        $('#id_kategori').val("");      
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