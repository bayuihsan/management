
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>generate_table</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 generate_table-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Add Generate</div>
    <div class="panel-body add-client">
    <?php if(!isset($edit_generate_table)){ ?>  
    <form id="add-generate_table">
      <input type="hidden" name="action" id="action" value="insert"/>  
      <input type="hidden" name="id_temp" id="id_temp" value=""/>    
      <div class="form-group">
        <label for="balance">Bulan</label>
        <select name="bulan" id="bulan" class="form-control">
          <?php
          $xbulan=array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
          $jlh_bln=count($xbulan);
          for($c=1; $c<$jlh_bln; $c++){
              echo"<option value=$c> $xbulan[$c] </option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">Tahun</label>
        <select name="tahun" id="tahun" class="form-control">
          <?php
          $now=date('Y');
          for ($a=$now;$a>=2016;$a--)
          {
               echo "<option value='$a'>$a</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <option value="Aktif">Aktif</option>
          <option value="nAktif">Tidak Aktif</option>
        </select>
      </div> 
      <div class="form-group">
        <label for="note">Input By</label>
        <input type="text" class="form-control" name="input_by" id="input_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>    
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>generate_table/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </form>
    <?php }else{ ?>

    <form id="add-generate_table">
      <input type="hidden" name="action" id="action" value="update"/>  
      <input type="hidden" name="id_temp" id="id_temp" value="<?php echo $edit_generate_table->id_temp ?>"/>   
      <div class="form-group">
        <label for="balance">Bulan</label>
        <select name="bulan" id="bulan" class="form-control">
          <?php
          $xbulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
          $jlh_bln=count($xbulan);
          for($c=0; $c<$jlh_bln; $c+=1){
            if($edit_generate_table->bulan == $c){
              echo"<option value='$c' selected> $xbulan[$c] </option>";
            }else{
              echo"<option value='$c'> $xbulan[$c] </option>";
            }
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="balance">Tahun</label>
        <select name="tahun" id="tahun" class="form-control">
          <?php
          $now=date('Y');
          for ($a=$now;$a>=2016;$a--)
          {
            if($edit_generate_table->tahun == $a){
              echo "<option value='$a' selected>$a</option>";
            }else{
              echo "<option value='$a'>$a</option>";
            }
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="note">Status</label>
        <select name="status" class="form-control">
          <option value="">Pilih Status</option>
          <?php if($edit_generate_table->status == "Aktif"){ ?>
            <option value="Aktif" selected>Aktif</option>
            <option value="nAktif">Tidak Aktif</option>
          <?php }else{ ?>
            <option value="Aktif">Aktif</option>
            <option value="nAktif" selected>Tidak Aktif</option>
          <?php } ?>
          
        </select>
      </div> 
      <div class="form-group">
        <label for="note">Update By</label>
        <input type="text" class="form-control" name="input_by" id="input_by" value="<?php echo $this->session->userdata('id_users'); ?>" readonly>
      </div>   
          
      <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>generate_table/view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
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
    $("#asdas").keypress(function (e) {
      //if the letter is not digit then display error and don't type anything
      if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
      }
    });


    $('#add-generate_table').on('submit',function(){    
      var id_temp = $('#id_temp').val();
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('generate_table/add/insert') ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='update'){        
            $('#bulan').val("");
            $("#tahun").val("");
            $('#status').val("");      
          }
          swal("Saved!", "Saved Sucessfully", "success");
          if(id_temp!=""){
            setTimeout(function(){ document.location.href = '<?php echo base_url()?>generate_table/edit/'+id_temp; }, 2000);
          }else{
            setTimeout(function(){ document.location.href = '<?php echo base_url()?>generate_table/add'; }, 2000);
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
      
      $(".block-ui").css('display','block'); 
   
      history.pushState(null, null,link);  
      $('.asyn-div').load(link+'/asyn',function() {
          $(".block-ui").css('display','none');     
      });     
            

      return false;
    });

});
</script>