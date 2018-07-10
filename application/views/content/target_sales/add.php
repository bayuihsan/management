<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Target</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-8 col-lg-8 col-sm-8 branch-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Target</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_target)){ ?>  
    <form id="add-target">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_target" id="id_target" value=""/>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id_t" class="form-control" id="branch_id_t">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) {?>
          <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
          <label for="spaket">Tahun</label>
          <select name="tahun_target" class="form-control" id="tahun_target">  
            <option value="">Pilih Tahun</option>
            <?php for ($i=date('Y'); $i>=date('Y')-3; $i--){?>
            <option value="<?php echo $i?>"><?php echo $i ?></option>
            <?php } ?>
          </select>
      </div> 
      <div class="form-group"> 
          <label for="spaket">Bulan</label>
          <select name="bulan_target" class="form-control" id="bulan_target">  
            <option value="">Pilih Bulan</option>
            <?php for ($j=1; $j<=12; $j++){?>
            <option value="<?php echo $j?>"><?php echo $j ?></option>
            <?php } ?>
          </select>
      </div> 
      <div class="form-group">
        <label for="balance">Nilai target</label>
        <input type="text" class="form-control" name="nilai_target" id="nilai_target">
      </div>
      <div class="form-group">
        <label for="note">Update By</label>
        <input type="text" class="form-control" name="updated_by_x" value="<?php echo $this->session->userdata('nama')?>" readonly>
        <input type="hidden" class="form-control" name="updated_by" id="updated_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>    
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/target_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-target">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_target" id="id_target" value="<?php echo $edit_target->id_target ?>"/>   
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id_t" class="form-control" id="branch_id_t">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) { 
            if($edit_target->branch_id == $new->branch_id){ ?>
            <option value="<?php echo $new->branch_id ?>" selected><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php }else{ ?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
           } ?>
        </select>      
      </div>
      <div class="form-group"> 
          <label for="spaket">Tahun</label>
          <select name="tahun_target" class="form-control" id="tahun_target">  
            <option value="">Pilih Tahun</option>
            <?php for ($i=date('Y'); $i>=date('Y')-3; $i--){
              if($edit_target->tahun_target == $i){ ?>
                <option value="<?php echo $i ?>" selected><?php echo $i?></option>
              <?php }else{ ?>
                <option value="<?php echo $i ?>"><?php echo $i?></option>
              <?php }
              ?>
            <?php } ?>
          </select>
      </div> 
      <div class="form-group"> 
          <label for="spaket">Bulan</label>
          <select name="bulan_target" class="form-control" id="bulan_target">  
            <option value="">Pilih Bulan</option>
            <?php for ($j=1; $j<=12; $j++){
              if($edit_target->bulan_target == $j){ ?>
                <option value="<?php echo $j?>" selected><?php echo $j?></option>
              <?php }else{ ?>
                <option value="<?php echo $j?>"><?php echo $j?></option>
              <?php } 
            } ?>
          </select>
      </div> 
      <div class="form-group">
        <label for="balance">Nilai target</label>
        <input type="text" class="form-control" name="nilai_target" id="nilai_target" value="<?php echo $edit_target->nilai_target ?>">
      </div>
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="updated_by_x" value="<?php echo $this->session->userdata('nama')?>">
        <input type="hidden" class="form-control" name="updated_by" id="updated_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/target_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
  $("#nilai_target").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#branch_id_t').select2();
  $('#tahun_target').select2();
  $('#bulan_target').select2();

  $(".date").datepicker();
  $('#add-target').on('submit',function(){     
    var branch_id_t = $('#branch_id_t').val(); 
    var tahun_target = $('#tahun_target').val(); 
    var bulan_target = $('#bulan_target').val(); 
    var nilai_target = $('#nilai_target').val(); 

    if(branch_id_t == '' || tahun_target == '' || bulan_target == '' || nilai_target == ''){
      swal({title: "Add Failed", text: "Form tambah target harus diisi<br><br>", type:"warning", html:true});
      return false;
    }else{
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('Admin/target_add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
          if(data == "exist"){
            $(".block-ui").css('display','none'); 
            swal({title: "Add Failed", text: "Data target sudah ada<br><br>", type:"warning", html:true});
            return false;
          }else if(data=="true"){  
            // sucessAlert("Saved Sucessfully"); 
            $(".block-ui").css('display','none'); 
            if($("#action").val()!='update'){        
              $('#branch_id_t').select2("val","");
              $('#tahun_target').select2("val","");
              $('#bulan_target').select2("val","");
              $('#nilai_target').val(""); 
            }
            swal("Saved!", "Saved Sucessfully", "success");
            setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/target_view'; }, 2000);
          }else{
            failedAlert2(data);
            $(".block-ui").css('display','none');
          }   
        }
      });    
      return false;
    }
    
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