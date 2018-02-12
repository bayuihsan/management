
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
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
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
      <input type="hidden" name="id_users" id="id_users" value=""/>    
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" name="nama" id="nama">
      </div>
      <div class="form-group">
        <label for="no_hp">No HP</label>
        <input type="text" class="form-control" name="no_hp" id="no_hp">
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
        <label for="channel">Channel</label>
        <select name="channel" class="form-control" id="channel">  
          <option value="">Pilih Channel</option>
          <?php for($i=0; $i<count($channel); $i++) { ?>
          <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$channel[$i]; ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="level">Level</label>
        <select name="level" class="form-control" id="level">  
          <option value="">Pilih Level</option>
          <?php for($i=1; $i<count($level); $i++) { ?>
          <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$level[$i]; ?></option>
          <?php } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="no_rekening">No Rekening</label>
        <input type="text" class="form-control" name="no_rekening" id="no_rekening">
      </div>
      <div class="form-group">
        <label for="nama_bank">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank">
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <select name="keterangan" class="form-control" id="keterangan">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="nAktif">Tidak Aktif</option>
        </select>
      </div> 
      <div class="form-group">
        <label for="Input By">Input By</label>
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <hr>
      <p>Informasi Login</p>
      <div class="form-group">
        <label for="username">Username</label> *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="username" id="username">
        <input type="hidden" class="form-control" name="username1" id="username1">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password">
      </div>
      <div class="form-group">
        <label for="password">Confirm Password</label>
        <input type="password" class="form-control" name="repassword" id="repassword">
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
    </form>
    <?php }else{ ?>

    <form id="add-users">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_users" id="id_users" value="<?php echo $edit_users->id_users?>"/>    
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $edit_users->nama?>">
      </div>
      <div class="form-group">
        <label for="no_rekening">No HP</label>
        <input type="text" class="form-control" name="no_hp" id="no_hp" value="<?php echo $edit_users->no_hp?>">
      </div>
      <div class="form-group"> 
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch">  
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
        <label for="channel">Channel</label>
        <select name="channel" class="form-control" id="channel">  
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
        <label for="level">Level</label>
        <select name="level" class="form-control" id="level">  
          <option value="">Pilih Level</option>
          <?php for($i=1; $i<count($level); $i++) { 
            if($edit_users->level == $i){ $sel = "selected='selected'"; ?>
            <option value="<?php echo $i; ?>" <?php echo $sel?>><?php echo "(".$i.") ".$level[$i]; ?></option>
            <?php }else{ $sel = "";  ?>
            <option value="<?php echo $i; ?>" <?php echo $sel?>><?php echo "(".$i.") ".$level[$i]; ?></option>
          <?php   }
            } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="no_rekening">No Rekening</label>
        <input type="text" class="form-control" name="no_rekening" id="no_rekening" value="<?php echo $edit_users->no_rekening ?>">
      </div>
      <div class="form-group">
        <label for="nama_bank">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank" value="<?php echo $edit_users->nama_bank ?>">
      </div>
      <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <select name="keterangan" class="form-control" id="keterangan">
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
        <input type="text" class="form-control" name="update_by" id="update_by" value="<?php echo $this->session->userdata('username'); ?>" readonly>
      </div>    
      <hr>
      <p>Informasi Login</p>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $edit_users->username?>" readonly> 
        change *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="username1" id="username1" placeholder="Change Username">
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
$("#branch").select2();
$("#channel").select2();
$("#level").select2();

//for number only
$("#no_rekening").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});

//for number only
$("#no_hp").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});

$('#add-users').on('submit',function(){    
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
        $('#nama_users').val("");
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