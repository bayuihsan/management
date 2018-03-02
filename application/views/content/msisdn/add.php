<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>MSISDN</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->
<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add MSISDN</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_msisdn)){ ?>  
    <form id="add-msisdn">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_haloinstan" id="id_haloinstan" value=""/>    
      <div class="form-group">
        <label for="acc_name">MSISDN</label>
        <input type="text" class="form-control" name="msisdn" id="msisdn" maxlength="14">
      </div>
      <div class='form-group'>
        <label>Branch</label>
        <select name="mbranch_id" id="mbranch_id" class="form-control">
          <option value="">Pilih Branch</option>
          <?php foreach($branch as $row) { ?>
          <option value="<?php echo $row->branch_id?>"><?php echo $row->nama_branch?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="note">Tipe</label>
        <select name="tipe" class="form-control">
          <option value="">Pilih Tipe</option>
          <option value="HaloInstan">Halo Instan</option>
          <option value="HaloReguler">Halo Reguler</option>
        </select>
      </div>
      <div class='form-group'>
        <label>ID TL</label>
        <select name="mid_users" id="mid_users" class="form-control">
          <option value="">Pilih TL</option>
          <?php foreach($TL as $row) { ?>
          <option value="<?php echo $row->id_users?>"><?php echo $row->nama?></option>
          <?php } ?>
        </select>
      </div>
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>msisdn/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    <br>*Note : Input MSISDN Harus menggunakan format 628<br> 
    <br>*Note : Jika ingin menginputkan MSISDN lebih dari 1, gunakan (,) dan (spasi).<br> 
     Contoh : 62811123124, 628112234467
    </form>
    </div>
</div>
</div>
</div>

<div  class="col-lg-6">
<ul class="list-group">
<button type="button" class="list-group-item list-group-item-dark">
    MSISDN Upload From Excel
  </button>
  <li class="list-group-item">
<form>
<?php echo form_open_multipart('upload/update');?>
<input type="file" name="file" size="20" id='files'/>
<br /><br />
<button type="submit" class="mybtn btn-submit">Update</button>
</form></li>   
</ul>
</div></div>
 </div></div>
</div>
 </div>

    <?php }else{ ?>

    <form id="add-msisdn">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_haloinstan" id="id_haloinstan" value="<?php echo $edit_msisdn->id_haloinstan ?>"/>   
      <div class="form-group">
        <label for="nama_paket">MSISDN</label>
        <input type="text" class="form-control" name="msisdn" id="msisdn" value="<?php echo $edit_msisdn->msisdn ?>" maxlength="14">
      </div>
      <div class='form-group'>
        <label>Branch</label>
        <select name="mbranch_id" id="mbranch_id" class="form-control">
          <option value="">Pilih Branch</option>
          <?php foreach($branch as $row) { 
            if($edit_msisdn->branch_id == $row->branch_id){ ?>
            <option value="<?php echo $row->branch_id?>" selected><?php echo $row->nama_branch?></option>
            <?php }else{ ?>
            <option value="<?php echo $row->branch_id?>"><?php echo $row->nama_branch?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="tipe">Tipe</label>
        <select name="tipe" class="form-control">
          <option value="0">Pilih Tipe</option>
          <?php if($edit_msisdn->tipe == "HaloInstan"){ ?>
          <option value="HaloInstan" selected="selected">Halo Instan</option>
          <option value="HaloReguler">Halo Reguler</option>
          <?php }else{ ?>
          <option value="HaloInstan">Halo Instan</option>
          <option value="HaloReguler" selected="selected">Halo Reguler</option>
          <?php } ?>
        </select>
      </div>

      <div class='form-group'>
        <label>TL</label>
        <select name="mid_users" class="form-control" id="mid_users">
          <option value="0">Pilih TL</option>  
          <?php foreach ($TL as $row) {
            if($edit_msisdn->id_users == $row->id_users){
            ?>
          <option value="<?php echo $row->id_users?>" selected><?php echo $row->nama ?></option>
          <?php } else { ?>
          <option value="<?php echo $row->id_users?>"><?php echo $row->nama ?></option>  
          <?php }
          }?>
        </select>
      </div>    
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>msisdn/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
  $("#harga_paket").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });
  $('#mbranch_id').select2();
  $('#mid_users').select2();

  $('#add-msisdn').on('submit',function(){    
    var msisdn = $("#msisdn").val();
    if(msisdn.substring(0,3)!=628){
      alert("MSISDN harus diawali 628");
      return false;
    }else{
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('msisdn/add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='update'){        
            $('#msisdn').val('');
            $('#tipe').select('val','');
            $('#mid_users').select2('val','');
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
<!-- <script type="text/javascript">
      var file = document.getElementById('files');
         file.onchange = function(e){
            var ext = this.value.match(/\.([^\.]+)$/)[1];
            switch(ext)
            {
                case 'xlsx':
                    //alert('allowed');
                    break;
                default:
                    alert('Maaf file bukan .xlsx');
                    this.value='';
            }
    };
</script> -->