
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>sales</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<?php 
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'FOS ALR', 6=>'Validasi GraPARI', 7=>'FOS GraPARI', 8=>'Admin CTP');
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom');
$jenis_event=array(1=>'Industrial Park',
                   2=>'Mall to Mall',
                   3=>'Office to Office',
                   4=>'Other',
                   5=>'Mandiri',
                   6=>'Telkomsel',
                   7=>'BNI',
                   8=>'BRI',
                   9=>'Direct Sales/Residential',
                   10=>'Event - Booth - Canvasing - Banking -etc',
                   11=>'Partnership',
                   12=>'Outlet Regular AD',
                   13=>'Outlet Device (MDN+MDR)'
                 );
$discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
$periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
$bc=array('BC 01'=>'BC 01', 'BC 06'=>'BC 06', 'BC 11'=>'BC 11', 'BC 16'=>'BC 16', 'BC 20'=>'BC 20');
$status=array(
  'sukses'=>'sukses',
  'blacklist'=>'blacklist',
  'bentrok'=>'bentrok');
 ?>

<form id="add-bast">
<div class="col-md-6 col-lg-6 col-sm-6 sales-div">
<!--Start Panel-->
  <div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Edit sales</div>
    <div class="panel-body add-client">
      <div class="form-group">
        <label for="MSISDN">NO BAST</label>
        <input type="text" class="form-control" name="sno_bast" id="sno_bast" value="<?php echo $edit_sales->no_bast?>" readonly>
      </div>
      <div class="form-group"> 
        <label for="sbranch">Branch</label>
        <select name="sbranch" class="form-control" id="sbranch">  
          <option value="">Pilih Branch</option>
          <?php foreach ($branch as $new) { 
            if($edit_sales->branch_id == $new->branch_id){ ?>
            <option value="<?php echo $new->branch_id ?>" selected><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="ssub_channel">Sub Channel</label> *Jika data tidak ada, silakan konfirmasi ke Administrator
        <select name="ssub_channel" class="form-control" id="ssub_channel">  
          <option value="">Pilih Sub Channel </option>
          <?php foreach ($sub_channel as $new) { 
            if($edit_sales->sub_sales_channel == $new->id_channel){ ?>
            <option value="<?php echo $new->id_channel ?>" class="<?php echo $new->branch_id?>" selected><?php echo "(".$channel[$new->sales_channel].") ".$new->sub_channel ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->id_channel ?>" class="<?php echo $new->branch_id?>"><?php echo "(".$channel[$new->sales_channel].") ".$new->sub_channel ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="Detail Sub">Detail Sub Channel (Lokasi)</label>  
        <input type="text" class="form-control" name="sdetail_sub" id="sdetail_sub" value="<?php echo $edit_sales->detail_sub?>" readonly>
      </div>
      <div class="form-group"> 
        <label for="sTL">TL</label>
        <select name="sTL" class="form-control" id="sTL">  
          <option value="">Pilih TL</option>
          <?php foreach ($tl as $new) { 
            if($edit_sales->TL == $new->username){ ?>
            <option value="<?php echo $new->id_users ?>" class="<?php echo $new->branch_id?>" selected><?php echo "(".$new->id_users.") ".$new->nama ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->id_users ?>" class="<?php echo $new->branch_id?>"><?php echo "(".$new->id_users.") ".$new->nama ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="ssales_person">Sales Person</label>
        <select name="ssales_person" class="form-control" id="ssales_person">  
          <option value="">Pilih Sales Person</option>
          <?php foreach ($sales_person as $new) { 
            if($edit_sales->sales_person == $new->nama_sales){ ?>
            <option value="<?php echo $new->nama_sales ?>" class="<?php echo $new->id_users?>" selected><?php echo "(".$new->id_sales.") ".$new->nama_sales ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->nama_sales ?>" class="<?php echo $new->id_users?>"><?php echo "(".$new->id_sales.") ".$new->nama_sales ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="spaket">Tanggal Masuk</label>
        <div class='input-group date' >
            <input type="text" class="form-control" placeholder="Tanggal Masuk" name="stanggal_masuk" id="stanggal_masuk" value="<?php echo $edit_sales->tanggal_masuk?>" readonly />   
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div> 
      <div class="form-group"> 
        <label for="spaket">Tanggal Validasi</label>
        <div class='input-group date' >
            <input type="text" class="form-control" placeholder="Tanggal Validasi" name="stanggal_validasi" id="stanggal_validasi" value="<?php echo $edit_sales->tanggal_validasi?>" readonly />   
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div> 
      <div class="form-group"> 
        <label for="spaket">Tanggal Aktivasi</label>
        <div class='input-group date' >
            <input type="text" class="form-control" placeholder="Tanggal Aktivasi" name="stanggal_aktivasi" id="stanggal_aktivasi" value="<?php echo date('Y-m-d h:i:s')?>" readonly />   
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div>
      <div class="form-group"> 
        <label for="sgrapari">Grapari</label>
        <select name="sgrapari" class="form-control" id="sgrapari">  
          <option value="">Pilih Grapari</option>
          <?php foreach ($grapari as $new) { 
            if($edit_sales->id_grapari == $new->id_grapari){ ?>
            <option value="<?php echo $new->id_grapari ?>" class="<?php echo $new->branch_id?>" selected><?php echo "(".$new->id_grapari.") ".$new->nama_grapari ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->id_grapari ?>" class="<?php echo $new->branch_id?>"><?php echo "(".$new->id_grapari.") ".$new->nama_grapari ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <button type="submit" class="mybtn btn-submit" id="save_button"><i class="fa fa-check"></i> Save</button>
      <a href="<?php echo base_url()?>Admin/bast_detail/<?php echo $edit_sales->no_bast?>" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </div>
    
  </div>
</div>
<div class="col-md-6 col-lg-6 col-sm-6 bast-div">
<!--Start Panel-->
  <div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Data Pelanggan</div>
    <div class="panel-body add-client">
    
      <input type="hidden" name="action" id="action" value="aktivasi"/>  
      <input type="hidden" name="psb_id" id="psb_id" value="<?php echo $edit_sales->psb_id ?>"/>    
      
      <div class="form-group">
        <label for="MSISDN">MSISDN</label>
        <input type="text" class="form-control" name="smsisdn" id="smsisdn" value="<?php echo $edit_sales->msisdn?>" readonly>
      </div>
      <div class="form-group"> 
        <label for="sbranch">Ubah MSISDN </label> 
        <select name="smsisdn1" class="form-control" id="smsisdn1">  
          <option value="">Ubah MSISDN</option>
          <?php foreach ($msisdn as $new) { ?>
            <option value="<?php echo $new->msisdn ?>" class="<?php echo $new->id_users?>"><?php echo $new->msisdn ?></option>
            <?php }
            ?>
          
        </select>      
      </div>
      <div class="form-group">
        <label for=" Nama Pelanggan">Nama Pelanggan</label>
        <input type="text" class="form-control" name="snama_pelanggan" id="snama_pelanggan" value="<?php echo $edit_sales->nama_pelanggan ?>">
      </div>
      <div class="form-group">
        <label for="Alamat">Alamat</label>
        <textarea class="form-control" name="salamat" id="salamat"><?php echo $edit_sales->alamat?></textarea>
      </div>
      <div class="form-group">
        <label for="Alamat 2">Alamat 2</label>
        <textarea class="form-control" name="salamat2" id="salamat2"><?php echo $edit_sales->alamat2 ?></textarea>
      </div>
      <div class="form-group">
        <label for="No HP">No HP</label>
        <input type="text" class="form-control" name="sno_hp" id="sno_hp" value="<?php echo $edit_sales->no_hp?>" maxlength="14">
      </div>
      <div class="form-group">
        <label for="Ibu Kandung">Ibu Kandung</label>
        <input type="text" class="form-control" name="sibu_kandung" id="sibu_kandung" value="<?php echo $edit_sales->ibu_kandung?>">
      </div>
      
      <div class="form-group"> 
        <label for="spaket">Paket</label>
        <select name="spaket" class="form-control" id="spaket">  
          <option value="">Pilih Paket</option>
          <?php foreach ($paket as $new) { 
            if($edit_sales->paket_id == $new->paket_id){ ?>
            <option value="<?php echo $new->paket_id ?>" selected><?php echo $new->nama_paket ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->paket_id ?>"><?php echo $new->nama_paket ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="discount">Discount (rb)</label>
        <select name="sdiscount" class="form-control" id="sdiscount">  
          <option value="">Pilih Discount</option>
          <?php for($i=1; $i<count($discount); $i++) { 
            if($edit_sales->discount == $i){ ?>
            <option value="<?php echo $i; ?>" selected><?php echo $discount[$i]; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $i; ?>"><?php echo $discount[$i]; ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="periode">Periode (Bulan)</label>
        <select name="speriode" class="form-control" id="speriode">  
          <option value="">Pilih Periode</option>
          <?php for($i=1; $i<count($periode); $i++) { 
            if($edit_sales->periode == $i){ ?>
            <option value="<?php echo $i; ?>" selected><?php echo $periode[$i]; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $i; ?>"><?php echo $periode[$i]; ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="bill_cycle">Bill Cycle</label>
        <select name="sbill_cycle" class="form-control" id="sbill_cycle">  
          <option value="">Pilih Bill Cycle</option>
          <?php foreach($bc as $bc){ 
          if($edit_sales->bill_cycle == $bc){ ?>
            <option value="<?php echo $bc; ?>" selected><?php echo $bc; ?></option>
          <?php }else{ ?>
            <option value="<?php echo $bc; ?>"><?php echo $bc; ?></option>
          <?php } 
        } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="fa_id">FA ID</label>
        <input type="text" class="form-control" name="sfa_id" id="sfa_id" value="<?php echo $edit_sales->fa_id?>">
      </div>
      <div class="form-group">
        <label for="Account ID">Account ID</label>
        <input type="text" class="form-control" name="saccount_id" id="saccount_id" value="<?php echo $edit_sales->account_id?>">
      </div>
      <div class="form-group"> 
        <label for="jenis_event">Jenis Event</label>
        <select name="sjenis_event" class="form-control" id="sjenis_event">  
          <option value="">Pilih Jenis Event</option>
          <?php for($i=1; $i<count($jenis_event); $i++) { 
            if($edit_sales->jenis_event == $i){ ?>
            <option value="<?php echo $i; ?>" selected><?php echo "(".$i.") ".$jenis_event[$i]; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $i; ?>"><?php echo "(".$i.") ".$jenis_event[$i]; ?></option>
            <?php }
            ?>
          
          <?php } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="nama event">Nama Event</label>
        <input type="text" class="form-control" name="snama_event" id="snama_event" value="<?php echo $edit_sales->nama_event?>">
      </div>
      <div class="form-group"> 
        <label for="sTL">Validasi By</label>
        <select name="svalidasi_by" class="form-control" id="svalidasi_by">  
          <option value="">Pilih Validasi</option>
          <?php foreach ($validasi as $new) {
            if($edit_sales->validasi_by == $new->username){ ?>
            <option value="<?php echo $new->username ?>" class="<?php echo $new->branch_id?>" selected><?php echo "(".$new->id_users.") ".$new->nama ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->username ?>" class="<?php echo $new->branch_id?>"><?php echo "(".$new->id_users.") ".$new->nama ?></option>
            <?php }
          } ?>
        </select>      
      </div>
      <div class="form-group">
        <label for="nama event">Username</label>
        <input type="text" class="form-control" name="susername" id="susername" value="<?php echo $this->session->userdata('username')?>" readonly>
      </div>
      <div class="form-group"> 
        <label for="status">Status</label>
        <select name="sstatus" class="form-control" id="sstatus">  
          <option value="">Pilih Status</option>
          <?php foreach($status as $status) { 
            if($edit_sales->status == $status){ ?>
            <option value="<?php echo $status; ?>" selected><?php echo $status; ?></option>
            <?php }else{ ?>
            <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
            <?php }
          } ?>
        </select>      
      </div>
      <div class="form-group"> 
        <label for="sbranch">Keterangan / Reason</label> *Jika tidak tersedia, silahkan hubungi administrator
        <select name="id_reason" class="form-control" id="id_reason">  
          <option value="">Pilih Reason</option>
          <?php foreach ($reason as $new) { 
            if($edit_sales->deskripsi == $new->id_reason){ ?>
            <option value="<?php echo $new->id_reason ?>" class="<?php echo $new->status?>" selected><?php echo strtoupper($new->nama_reason) ?></option>
            <?php }else{ ?>
            <option value="<?php echo $new->id_reason ?>" class="<?php echo $new->status?>"><?php echo strtoupper($new->nama_reason) ?></option>
            <?php }
          }
            ?>
          
        </select>      
      </div>
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->    
    
</div>

</form>

</div><!--End Inner container-->
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->
<script type="text/javascript">
$(document).ready(function(){

  if($(".sidebar").width()=="0"){
    $(".main-content").css("padding-left","0px");
  } 
  
  $("#smsisdn1").select2();
  $("#sbranch").select2();
  $("#sgrapari").select2();
  $("#ssub_channel").select2();
  $("#schannel").select2();
  $("#spaket").select2();
  $("#sdiscount").select2();
  $("#speriode").select2();
  $("#sbill_cycle").select2();
  $("#sjenis_event").select2();
  $("#sTL").select2();
  $("#ssales_person").select2();
  $("#sstatus").select2();
  $("#svalidasi_by").select2();
  $("#id_reason").select2();

  $("#date").datepicker();
  
  //sub channel berdasarkan branch
  $("#ssub_channel").chained("#sbranch");
  $("#svalidasi_by").chained("#sbranch");
  $("#sgrapari").chained("#sbranch");
  $("#sTL").chained("#sbranch");
  $("#ssales_person").chained("#sTL");
  $("#smsisdn1").chained("#sTL");
  $("#id_reason").chained("#sstatus");
  //for number only
  $("#smsisdn, #sno_hp, #sfa_id, #saccount_id").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
      //display error message
      return false;
    }
  });

  $('#add-bast').on('submit',function(){    
    var no_bast = $("#sno_bast").val();
    var psb_id = $("#psb_id").val();
    var msisdn = $("#smsisdn").val();
    var msisdn1 = $("#smsisdn1").val();
    var no_hp = $("#sno_hp").val();
    var fa_id = $("#sfa_id").val();
    var id_reason = $("#id_reason").val();
    var account_id = $("#saccount_id").val();
    var bill_cycle = $("#sbill_cycle").val();
    if(msisdn.substring(0,3)!=628){
      alert("MSISDN harus diawali 628");
      return false;
    }else if(no_hp.substring(0,3)!=628){
      alert("No HP harus diawali 628");
      return false;
    }else if(bill_cycle == ''){
      alert("Bill Cycle tidak boleh kosong");
      return false;
    }else if(fa_id == ''){
      alert("FA ID tidak boleh kosong");
      return false;
    }else if(account_id == ''){
      alert("Account ID tidak boleh kosong");
      return false;
    }else if(account_id == fa_id){
      alert("account_id tidak boleh sama dengan FA ID");
      return false;
    }else if(msisdn == msisdn1){
      alert("Pengubahan MSISDN tidak boleh sama");
      return false;
    }else if(id_reason == ''){
      alert("Keterangan tidak boleh kosong");
      return false;
    }else{
      $.ajax({
        method : "POST",
        url : "<?php echo site_url('Admin/bast_aktivasi').'/'.$no_bast.'/'.$psb_id.'/insert' ?>",
        data : $(this).serialize(),
        beforeSend : function(){
          $(".block-ui").css('display','block'); 
        },success : function(data){ 
        if(data=="true"){  
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none'); 
          if($("#action").val()!='validasi'){     
            $("#snama_pelanggan").val("");
            $('#salamat').val("");      
            $('#salamat2').val("");      
            $('#sno_hp').val("");  
            $('#sibu_kandung').val("");   
            $('#spaket').select2("val","");
            $('#sdiscount').val("");
            $('#speriode').val("");
            $('#sjenis_event').val("");
            $('#snama_event').val("");

            $('#sbranch').select2("val","");
            $('#ssub_channel').select2("val","");
            $('#sdetail_sub').val("");
            $('#sTL').select2("val","");
            $('#ssales_person').select2("val","");
          }
          swal("Saved!", "Saved Sucessfully", "success");
          setTimeout(function(){ document.location.href = '<?php echo base_url()?>Admin/bast_aktivasi/'+no_bast+'/'+psb_id; }, 2000);
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
  document.getElementById("save_button").disabled = true;
  $('#id_reason').change(function() {
    var id_reason = $("#id_reason").val();

    if(id_reason == ""){
      document.getElementById("save_button").disabled = true;
    }else{
      document.getElementById("save_button").disabled = false;
    }
  });
});
</script>