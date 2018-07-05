<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Sales</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<?php 
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
$status=array('sukses'=>'sukses',
  'valid'=>'valid',
  'cancel'=>'cancel',
  'reject'=>'reject',
  'pending'=>'pending',
  'retur'=>'retur',
  'blacklist'=>'blacklist',
  'bentrok'=>'bentrok',
  'masuk'=>'masuk');
$tgl = array("tanggal_aktif"=>"tanggal_aktif", "tanggal_validasi"=>"tanggal_validasi", "tanggal_masuk"=>"tanggal_masuk");

function discount($id){
    if($id=="1"){
        echo 0;
    }elseif($id=="2"){
        echo 25;
    }elseif($id=="3"){
        echo 50;
    }elseif($id=="4"){
        echo 100;
    }elseif($id=="5"){
        echo 'FreeMF';
    }
}

function periode($id){
    if($id=="1"){
        echo 0;
    }elseif($id=="2"){
        echo 1;
    }elseif($id=="3"){
        echo 3;
    }elseif($id=="4"){
        echo 6;
    }elseif($id=="5"){
        echo 12;
    }
}

function jenis_event($id){
    if($id=="1"){
        echo strtoupper("Industrial Park");
    }elseif($id=="2"){
        echo strtoupper("Mall to Mall");
    }elseif($id=="3"){
        echo strtoupper("Office to Office");
    }elseif($id=="4"){
        echo strtoupper("Other");
    }elseif ($id=="5") {
        echo strtoupper("Mandiri");
    }elseif ($id=="6") {
        echo strtoupper("Telkomsel");
    }
}
 ?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><?php echo strtoupper($nama_table)?> <div class="add-button">
    <a href="<?php echo base_url()?>Admin/generatetable_view" class="mybtn btn-warning kembali"><i class="fa fa-backward"></i> Back</a>
    </div></div>
    <div class="panel-body">
        <div class="col-md-12 col-lg-12 col-sm-12 report-params">
            <form id="sales_cari" action="<?php echo site_url('Admin/generatetable_load_table/'.$id_temp) ?>">

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <select class="form-control" name="vbranch_id" id="vbranch_id">
                            <?php foreach($branch as $v){ 
                                if($bbranch_id == $v->branch_id){ ?>
                                <option value="<?php echo $v->branch_id ?>" selected><?php echo $v->nama_branch ?></option>
                                <?php }else{ ?>
                                <option value="<?php echo $v->branch_id ?>"><?php echo $v->nama_branch ?></option>
                                <?php }
                                } ?>
                        </select>  
                    </div> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <select class="form-control" name="vtanggal" id="vtanggal">
                        <?php foreach($tgl as $tgl){ 
                            if($btgl == $tgl){ ?>
                            <option value="<?php echo $tgl?>" selected><?php echo $tgl?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $tgl?>"><?php echo $tgl?></option>
                         <?php } 
                        }?>
                    </select> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <select class="form-control" name="vstatus" id="vstatus">
                        <?php foreach($status as $status){ 
                            if($bstatus == $status){ ?>
                            <option value="<?php echo $status?>" selected><?php echo $status?></option>
                            <?php }else{ ?>
                            <option value="<?php echo $status?>"><?php echo $status?></option>
                            <?php }
                            } ?>
                        
                    </select> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <div class='input-group date' id='date'>
                            <input type="text" class="form-control" placeholder="Date From" name="vfrom-date" id="vfrom-date" value="<?php echo isset($bfrom_date) ? $bfrom_date : '' ?>" />   
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div> 
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                    <div class="form-group"> 
                        <div class='input-group'>
                            <input type="text" class="form-control" placeholder="Date To" name="vto-date" id="vto-date" value="<?php echo isset($bto_date) ? $bto_date : '' ?>" /> 
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>  
                        </div> 
                    </div>
                </div>

                <div class="col-md-2 col-lg-2 col-sm-2"> 
                <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
                <?php if(!empty($bfrom_date) && !empty($bto_date)) { ?>
                <a href="<?php echo site_url('Admin/generatetable_export/'.$id_temp.'/asyn').'/'.$bbranch_id.'/'.$btgl.'/'.$bstatus.'/'.$bfrom_date.'/'.$bto_date ?>" title="Export to Excel" class="mybtn btn-warning"><i class="fa fa-download"></i></a>
                <?php } ?>
                </div>
                                
            </form>
        </div>
        <br>
        <div class="report-heading">
            <p>Date From - - - - To - - - -</p>
        </div>
        <?php $no=1; if(empty($data_list)){ ?>
        <script type="text/javascript">
            $(document).ready(function() {
                swal("Alert","Sorry, No Data Found !", "info");
            }); 
        </script>
        
        <?php }else{ ?>

        <table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th>
                <th>MSISDN</th>
                <th>NAMA PELANGGAN</th>
                <th>BRANCH</th>
                <th>PAKET</th>
                <th>TL</th>
                <th>SALES PERSON</th>
                <th>TANGGAL MASUK</th>
                <th>TANGGAL VALIDASI</th>
                <th>TANGGAL AKTIF</th>
                <th>SLA</th>
                <th>FA ID</th>
                <th>ACCOUNT ID</th>
                <th>ALAMAT</th>
                <th>ALAMAT 2</th>
                <th>DISCOUNT (RB)</th>
                <th>PERIODE (BULAN)</th>
                <th>BILL CYCLE</th>
                <th>CHANNEL</th>
                <th>SUB SALES CHANNEL</th>
                <th>JENIS EVENT</th>
                <th>NAMA EVENT</th>
                <th>VALIDATOR</th>
                <th>AKTIVATOR</th>
                <th>STATUS</th>
                <th>TANGGAL UPDATE (System)</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1;
                    foreach($data_list as $new) { ?>    
                        <tr>
                            <td class="date"><?php echo $no++; ?></td>
                            <td><?php echo $new->psb_id.' - '.strtoupper($new->msisdn) ?></td>
                            <td><?php echo strtoupper($new->nama_pelanggan) ?></td>
                            <td><?php echo strtoupper($new->nama_branch) ?></td>
                            <td><?php echo strtoupper($new->nama_paket) ?></td>
                            <td><?php echo strtoupper($new->TL) ?></td>
                            <td><?php echo strtoupper($new->sales_person) ?></td>
                            <td><?php echo isset($new->tanggal_masuk) ? date('Y-m-d', strtotime($new->tanggal_masuk)) : ''; ?></td>
                            <td><?php echo isset($new->tanggal_validasi) ? date('Y-m-d', strtotime($new->tanggal_validasi)) : ''; ?></td>
                            <td><?php echo isset($new->tanggal_aktif) ? date('Y-m-d', strtotime($new->tanggal_aktif)) : ''; ?></td>
                            <td><?php if(!empty($new->tanggal_aktif)){
                                    $tgl_aktif = new DateTime($new->tanggal_aktif);
                                }else{
                                    $tgl_aktif = new DateTime();
                                }
                                $tgl_masuk = new DateTime($new->tanggal_masuk);
                                $diff = $tgl_aktif->diff($tgl_masuk); echo $diff->d." Hari"; ?></td>
                            <td><?php echo strtoupper($new->fa_id) ?></td>
                            <td><?php echo strtoupper($new->account_id) ?></td>
                            <td><?php echo strtoupper($new->alamat) ?></td>
                            <td><?php echo strtoupper($new->alamat2) ?></td>
                            <td><?php echo strtoupper(discount($new->discount)) ?></td>
                            <td><?php echo strtoupper(periode($new->periode)) ?></td>
                            <td><?php echo strtoupper($new->bill_cycle) ?></td>
                            <td><?php echo strtoupper($channel[$new->sales_channel]) ?></td>
                            <td><?php echo strtoupper($new->sub_channel) ?></td>
                            <td><?php echo strtoupper(jenis_event($new->jenis_event)) ?></td>
                            <td><?php echo strtoupper($new->nama_event) ?></td>
                            <td><?php echo strtoupper($new->validator) ?></td>
                            <td><?php echo strtoupper($new->aktivator) ?></td>
                            <td><?php echo strtoupper($new->status) ?></td>
                            <td><?php echo strtoupper($new->tanggal_update) ?></td>
                            <?php if($this->session->userdata('level')==4){ ?>
                            <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Edit" href="<?php echo site_url('sales/edit/'.$new->psb_id) ?>">Edit</a> &nbsp; 
                            <a class="mybtn btn-danger btn-xs sales-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('sales/add/remove/'.$new->psb_id) ?>">Delete</a> </td>
                            <?php }else{ ?>
                            <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Edit" href="<?php echo site_url('sales/edit/'.$new->psb_id) ?>">Edit</a> </td>
                            <?php } ?>
                            
                        </tr>
                <?php } ?>
                
            </tbody>       

        </table>
        <?php }  ?>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>


</div><!--End Inner container-->
</div> <!-- End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->


<script type="text/javascript">
$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });   
        $(".manage-client").niceScroll({
        cursorwidth: "8px",cursorcolor:"#7f8c8d"
    });
    $("#vfrom-date, #vto-date").datepicker(); 
    $("#vbranch_id").select2();
    $("#vtanggal").select2();
    $("#vstatus").select2();

    $("#sales-table").DataTable();
    $(".dataTables_length select").addClass("show_entries");

    $(document).on('click','.edit-btn',function(){

        var link=$(this).attr("href"); 
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

    $(document).on('click','.sales-remove-btn',function(){  
        var main=$(this);
        swal({title: "Are you sure Want To Delete?",
        text: "You will not be able to recover this Data!",
        type: "warning",   showCancelButton: true,confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes, delete it!",closeOnConfirm: false,
        showLoaderOnConfirm: true }, function(){ 
            ///////////////     
            var link=$(main).attr("href");    
            $.ajax({
                url : link,
                beforeSend : function(){
                    $(".block-ui").css('display','block'); 
                },success : function(data){
                    $(main).closest("tr").remove();    
                    //sucessAlert("Remove Sucessfully"); 
                    $(".system-alert-box").empty();
                    document.location.href = '<?php echo base_url()?>/sales';
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

    $('#sales_cari').on('submit',function(){
        var link=$(this).attr("action");
        var branch_id   = $("#vbranch_id").val();
        var tanggal     = $("#vtanggal").val();
        var status      = $("#vstatus").val();
        var fromdate    = $("#vfrom-date").val();
        var todate      = $("#vto-date").val();
        
        if(fromdate!="" && todate!=""){
            $(".block-ui").css('display','block'); 
            
            history.pushState(null, null,link);  
            $('.asyn-div').load(link+'/cari'+'/'+branch_id+'/'+tanggal+'/'+status+'/'+fromdate+'/'+todate,function() {
                $(".block-ui").css('display','none');   
                $(".report-heading p").html("Date From "+fromdate+" To "+todate);  
            }); 
                
        }else if(fromdate > todate){
            swal("Alert","Periksa kembali tanggal pencarian", "info");  
        }else{
            swal("Alert","Please Select Date Range.", "info");      
        }

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

