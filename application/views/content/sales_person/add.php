<!--Statt Main Content-->
<script src="<?php echo base_url() ?>/theme/js/jquery.chained.min.js"></script>
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
    <div class="panel-heading">Add Sales Person</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_salesperson)){ ?>  
    <form id="add-salesperson">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_sales" id="id_sales" value=""/>    
      <div class="form-group">
        <label for="user_sales">User Sales</label> *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="user_sales" id="user_sales">
      </div>
      <div class="form-group">
        <label for="nama_sales">Nama Sales</label>
        <input type="text" class="form-control" name="nama_sales" id="nama_sales">
      </div>
      <div class="form-group">
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch_id" >
          <option value="">Pilih Branch</option>
          <?php foreach ($pilihbranch as $pb) {?>
          <option value="<?php echo $pb->branch_id?>"><?php echo $pb->nama_branch ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="TL">TL</label>
        <select name="id_users" class="form-control" id="id_users">
          <option value="">Pilih TL</option>
          <?php foreach ($pilihid as $pi) {?>
          <option value="<?php echo $pi->id_users?>" class="<?php echo $pi->branch_id; ?>"><?php echo '('.$pi->id_users.') '.$pi->nama; ?></option>
          <?php } ?>
        </select>
      </div>      
      <div class="form-group">
        <label for="balance">No Telp</label>
        <input type="text" class="form-control" name="no_telp" id="no_telp">
      </div>
      <div class="form-group">
        <label for="balance">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank">
      </div>
      <div class="form-group">
        <label for="balance">No. Rekening</label>
        <input type="text" class="form-control" name="no_rekening" id="no_rekening">
      </div>
      <div class="form-group">
        <label for="balance">Atas Nama</label>
        <input type="text" class="form-control" name="atas_nama" id="atas_nama">
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="nAktif">Tidak Aktif</option>
        </select>
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>salesperson/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-salesperson">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_sales" id="id_sales" value="<?php echo $edit_salesperson->id_sales;?>"/>    
      <div class="form-group">
        <label for="user_sales">User Sales</label>
        <input type="text" class="form-control" name="user_sales" id="user_sales" value="<?php echo $edit_salesperson->user_sales?>" readonly> 
        change *Jika menggunakan spasi, otomatis akan diganti menjadi Underscore (_)
        <input type="text" class="form-control" name="user_sales1" id="user_sales1" placeholder="Change Username">
      </div>
      <div class="form-group">
        <label for="nama_sales">Nama Sales</label>
        <input type="text" class="form-control" name="nama_sales" id="nama_sales" value="<?php echo $edit_salesperson->nama_sales;?>">
      </div>
      <div class="form-group">
        <label for="branch">Branch</label>
        <select name="branch_id" class="form-control" id="branch_id" >
          <option value="">Pilih Branch</option>
          <?php foreach ($pilihbranch as $pb) { 
            if($edit_salesperson->branch_id == $pb->branch_id){ ?>
              <option value="<?php echo $pb->branch_id?>" selected><?php echo $pb->nama_branch ?></option>
            <?php }else{ ?>
              <option value="<?php echo $pb->branch_id?>"><?php echo $pb->nama_branch ?></option>
            <?php }
          } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="TL">TL</label>
        <select name="id_users" class="form-control" id="id_users">
          <option value="">Pilih TL</option>
          <?php foreach ($pilihid as $pi) { 
            if($edit_salesperson->id_users == $pi->id_users){ ?>
              <option value="<?php echo $pi->id_users?>" class="<?php echo $pi->branch_id; ?>" selected><?php echo '('.$pi->id_users.') '.$pi->nama; ?></option>
            <?php }else{ ?>
              <option value="<?php echo $pi->id_users?>" class="<?php echo $pi->branch_id; ?>"><?php echo '('.$pi->id_users.') '.$pi->nama; ?></option>
            <?php }
          } ?>
        </select>
      </div>      
      <div class="form-group">
        <label for="balance">No Telp</label>
        <input type="text" class="form-control" name="no_telp" id="no_telp" value="<?php echo $edit_salesperson->no_telp;?>">
      </div>
      <div class="form-group">
        <label for="balance">Nama Bank</label>
        <input type="text" class="form-control" name="nama_bank" id="nama_bank" value="<?php echo $edit_salesperson->nama_bank;?>">
      </div>
      <div class="form-group">
        <label for="balance">No. Rekening</label>
        <input type="text" class="form-control" name="no_rekening" id="no_rekening" value="<?php echo $edit_salesperson->no_rekening;?>">
      </div>
      <div class="form-group">
        <label for="balance">Atas Nama</label>
        <input type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo $edit_salesperson->atas_nama;?>">
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <?php if($edit_salesperson->status=="Aktif"){ ?>
            <option value="Aktif" selected>Aktif</option>
            <option value="nAktif">Tidak Aktif</option>
          <?php }else{ ?>
            <option value="Aktif" >Aktif</option>
            <option value="nAktif" selected>Tidak Aktif</option>
            <?php }?>
        </select>
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>salesperson/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
  //TL berdasarkan branch
  $("#id_users").chained("#branch_id");

  //for number only
  $("#no_telp, #no_rekening").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });

  $('#id_users').select2();
  $('#branch_id').select2();

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