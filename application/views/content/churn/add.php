<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Churn</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<div class="col-md-6 col-lg-6 col-sm-6">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Churn</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_churn)){ ?>  
    <form id="add-churn">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_churn" id="id_churn" value=""/>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id_c" class="form-control" id="branch_id_c">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) {?>
          <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
          <label for="spaket">Tanggal Churn</label>
          <div class='input-group date' >
              <input type="text" class="form-control" placeholder="Tanggal Churn" name="tanggal_churn" id="tanggal_churn" />   
              <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
      </div> 
      <div class="form-group">
        <label for="balance">Nilai Churn</label>
        <input type="text" class="form-control" name="nilai_churn" id="nilai_churn">
      </div>
      <div class="form-group">
        <label for="note">Update By</label>
        <input type="text" class="form-control" name="updated_by_x" value="<?php echo $this->session->userdata('nama')?>" readonly>
        <input type="hidden" class="form-control" name="updated_by" id="updated_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>    
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/churn_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
      </form>
    </div>
  </div>
          <div  class="col-lg-6">
           <ul class="list-group">
          <button type="button" class="list-group-item list-group-item-dark">
              Upload Churn From Excel
          </button>
            <li class="list-group-item">
              <?php echo form_open_multipart('Uploadchurn/update');?>
                <input type="file" name="file" size="20" id='files'/>
                <input type="hidden" name="id_users_up" value="<?php echo $this->session->userdata('id_users');?>">
          <br>
              <a href="<?php echo base_url()?>assets/ctp/template/upload_msisdn.xls" download="upload_msisdn.xls">Download Format File</a>
          <br>
              <button type="submit" class="mybtn btn-submit">Save</button>
          </form>
            </li>
          </ul>
        </div>
    <?php }else{ ?>
    <form id="add-churn">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_churn" id="id_churn" value="<?php echo $edit_churn->id_churn ?>"/>   
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id_c" class="form-control" id="branch_id_c">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) { 
            if($edit_churn->branch_id == $new->branch_id){ ?>
            <option value="<?php echo $new->branch_id ?>" selected><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php }else{ ?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
           } ?>
        </select>      
      </div>
      <div class="form-group"> 
          <label for="spaket">Tanggal Churn</label>
          <div class='input-group date' >
              <input type="text" class="form-control" placeholder="Tanggal Churn" name="tanggal_churn" id="tanggal_churn" value="<?php echo $edit_churn->tanggal_churn ?>" readonly />   
              <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
      </div> 
      <div class="form-group">
        <label for="balance">Nilai Churn</label>
        <input type="text" class="form-control" name="nilai_churn" id="nilai_churn" value="<?php echo $edit_churn->nilai_churn ?>">
      </div>
      <div class="form-group">
        <label for="update_by">Update By</label>
        <input type="text" class="form-control" name="updated_by_x" value="<?php echo $this->session->userdata('nama')?>">
        <input type="hidden" class="form-control" name="updated_by" id="updated_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/churn_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
  $("#nilai_churn").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#branch_id_c').select2();
  $(".date").datepicker();
  $('#add-churn').on('submit',function(){     
    var tanggal_churn = $('#tanggal_churn').val(); 
    var nilai_churn = $('#nilai_churn').val(); 

    var date1           = new Date(tanggal_churn);

    var today = new Date(); 
    if(tanggal_churn == ''){
      swal({title: "Add Failed", text: "Tanggal Churn harus diisi<br><br>", type:"warning", html:true});
      return false;
    }else if(date1 >= today){
      swal({title: "Add Failed", text: "Tanggal Churn tidak valid<br><br>", type:"warning", html:true});
      return false;
    }else if(nilai_churn == ''){
      swal({title: "Add Failed", text: "Nilai Churn harus diisi<br><br>", type:"warning", html:true});
      return false;
    }else{
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('Admin/churn_add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
          if(data == "exist"){
            $(".block-ui").css('display','none'); 
            swal({title: "Add Failed", text: "Data churn sudah ada<br><br>", type:"warning", html:true});
            return false;
          }else if(data=="true"){  
            // sucessAlert("Saved Sucessfully"); 
            $(".block-ui").css('display','none'); 
            if($("#action").val()!='update'){        
              $('#branch_id_c').select2("val","");
              $('#tanggal_churn').val(""); 
              $('#nilai_churn').val(""); 
            }
            swal("Saved!", "Saved Sucessfully", "success");
            setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/churn_view'; }, 2000);
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