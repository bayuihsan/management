
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>users</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<?php 
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'FOS ALR', 6=>'Validasi GraPARI', 7=>'FOS GraPARI', 8=>'Admin CTP');
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom');
 ?>
<div class="col-md-8 col-lg-8 col-sm-8 users-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Users</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_users)){ ?>
    <p>Informasi Pribadi</p>  
    <form id="add-users">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="uid_users" id="uid_users" value=""/>    
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" name="unama" id="unama">
      </div>
      <div class="form-group">
        <label for="no_hp">No HP</label>
        <input type="text" class="form-control" name="uno_hp" id="uno_hp">
      </div>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="ubranch_id" class="form-control" id="ubranch_id">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) {?>
          <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="channel">Channel</label>
        <select name="uchannel" class="form-control" id="uchannel">  
          <option value="">Pilih Channel</option>
          <?php for($i=0; $i<count($channel); $i++) { ?>
          <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$channel[$i]; ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="level">Level</label>
        <select name="ulevel" class="form-control" id="ulevel">  
          <option value="">Pilih Level</option>
          <?php for($j=1; $j<=count($level); $j++) { ?>
          <option value="<?php echo $j; ?>"><?php echo "(".$j.") ".$level[$j]; ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <select name="uketerangan" class="form-control" id="uketerangan">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="nAktif">Tidak Aktif</option>
        </select>
      </div> 
      <div class="form-group">
        <label for="Input By">Input By</label>
        <input type="text" class="form-control" name="uupdate_by" id="uupdate_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <hr>
      <p>Informasi Login</p>
      <div class="form-group">
        <label for="username">Username</label> *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="uusername" id="uusername">
        <input type="hidden" class="form-control" name="uusername1" id="uusername1">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="upassword" id="upassword">
      </div>
      <div class="form-group">
        <label for="password">Confirm Password</label>
        <input type="password" class="form-control" name="urepassword" id="urepassword">
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>users/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-users">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="uid_users" id="uid_users" value="<?php echo $edit_users->id_users?>"/>    
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" name="unama" id="unama" value="<?php echo $edit_users->nama?>">
      </div>
      <div class="form-group">
        <label for="no_rekening">No HP</label>
        <input type="text" class="form-control" name="uno_hp" id="uno_hp" value="<?php echo $edit_users->no_hp?>">
      </div>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="ubranch_id" class="form-control" id="ubranch_id">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) {
            if($edit_users->branch_id == $new->branch_id){ 
              $sel = "selected='selected'";
              ?>
            <option value="<?php echo $new->branch_id ?>" <?php echo $sel?>><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }else{ 
              $sel = "";
              ?>
            <option value="<?php echo $new->branch_id ?>" <?php echo $sel?>><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
            ?>
            
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="uchannel">Channel</label>
        <select name="uchannel" class="form-control" id="uchannel">  
          <option value="">Pilih Channel</option>
          <?php for($i=0; $i<count($channel); $i++) { 
            if($edit_users->channel == $i){ $sel = "selected='selected'"; ?>
            <option value="<?php echo $i; ?>" <?php echo $sel?>><?php echo "(".$i.") ".$channel[$i]; ?></option>
            <?php }else{ $sel = ""; ?> 
            ?>
            <option value="<?php echo $i; ?>" <?php echo $sel?>><?php echo "(".$i.") ".$channel[$i]; ?></option>
          <?php }
            } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="ulevel">Level</label>
        <select name="ulevel" class="form-control" id="ulevel">  
          <option value="">Pilih Level</option>
          <?php for($j=1; $j<count($level); $j++) { 
            if($edit_users->level == $j){ $sel = "selected='selected'"; ?>
            <option value="<?php echo $j; ?>" <?php echo $sel?>><?php echo "(".$j.") ".$level[$j]; ?></option>
            <?php }else{ $sel = "";  ?>
            <option value="<?php echo $j; ?>" <?php echo $sel?>><?php echo "(".$j.") ".$level[$j]; ?></option>
          <?php   }
            } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <select name="uketerangan" class="form-control" id="uketerangan">
          <option value="">Pilih Keterangan</option>
          <?php if($edit_users->keterangan=="Aktif"){ ?>
          <option value="Aktif" selected="selected">Aktif</option>
          <option value="nAktif">Tidak Aktif</option>
          <?php }else{?>
          <option value="Aktif">Aktif</option>
          <option value="nAktif" selected="selected">Tidak Aktif</option>
          <?php } ?>
          
        </select>
      </div> 
      <div class="form-group">
        <label for="Input By">Input By</label>
        <input type="text" class="form-control" name="uupdate_by" id="uupdate_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <hr>
      <p>Informasi Login</p>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="uusername" id="uusername" value="<?php echo $edit_users->username?>" readonly> 
        change *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="uusername1" id="uusername1" placeholder="Change Username">
      </div>
      
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>users/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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

  $("#ubranch_id").select2();
  $("#uchannel").select2();
  $("#ulevel").select2();

  //for number only
  $("#uno_rekening").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });

  //for number only
  $("#uno_hp").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });

  $('#add-users').on('submit',function(){    
    var hp = $("#uno_hp").val();
    if(hp.substring(0,3)!=628){
      alert("No HP harus diawali 628");
      return false;
    }else{
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('users/add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='update'){        
            $('#unama').val("");
            $("#uno_hp").val("");
            $('#ubranch_id').val("");      
            $('#uchannel').val("");      
            $('#ulevel').val("");      
            $('#uno_rekening').val("");      
            $('#unama_bank').val("");      
            $('#uketerangan').val("");      
            $('#uusername').val("");      
            $('#upassword').val("");      
            $('#urepassword').val("");      
          }
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