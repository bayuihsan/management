<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>sales</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<?php 
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
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
    <div class="panel-heading">Manage Sales <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('sales/add') ?>">Add Sales</a>
    </div></div>
    <div class="panel-body">
        <table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th>
                <th>MSISDN</th>
                <th>NAMA PELANGGAN</th>
                <th>BRANCH</th>
                <th>TANGGAL MASUK</th>
                <th>TANGGAL VALIDASI</th>
                <th>TANGGAL AKTIF</th>
                <th>FA ID</th>
                <th>ACCOUNT ID</th>
                <th>ALAMAT</th>
                <th>ALAMAT 2</th>
                <th>PAKET</th>
                <th>DISCOUNT (RB)</th>
                <th>PERIODE (BULAN)</th>
                <th>BILL CYCLE</th>
                <th>CHANNEL</th>
                <th>SUB SALES CHANNEL</th>
                <th>SALES PERSON</th>
                <th>TL</th>
                <th>JENIS EVENT</th>
                <th>NAMA EVENT</th>
                <th>VALIDATOR</th>
                <th>AKTIVATOR</th>
                <th>STATUS</th>
                <th>TANGGAL UPDATE (System)</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($sales as $new) { ?>    
                <tr>
                    <td class="date"><?php echo $no++; ?></td>
                    <td><?php echo $new->psb_id.' - '.strtoupper($new->msisdn) ?></td>
                    <td><?php echo strtoupper($new->nama_pelanggan) ?></td>
                    <td><?php echo strtoupper($new->nama_branch) ?></td>
                    <td><?php echo strtoupper(date('Y-m-d', strtotime($new->tanggal_masuk))) ?></td>
                    <td><?php echo strtoupper(date('Y-m-d', strtotime($new->tanggal_validasi))) ?></td>
                    <td><?php echo strtoupper(date('Y-m-d', strtotime($new->tanggal_aktif))) ?></td>
                    <td><?php echo strtoupper($new->fa_id) ?></td>
                    <td><?php echo strtoupper($new->account_id) ?></td>
                    <td><?php echo strtoupper($new->alamat) ?></td>
                    <td><?php echo strtoupper($new->alamat2) ?></td>
                    <td><?php echo strtoupper($new->nama_paket) ?></td>
                    <td><?php echo strtoupper(discount($new->discount)) ?></td>
                    <td><?php echo strtoupper(periode($new->periode)) ?></td>
                    <td><?php echo strtoupper($new->bill_cycle) ?></td>
                    <td><?php echo strtoupper($channel[$new->sales_channel]) ?></td>
                    <td><?php echo strtoupper($new->sub_channel) ?></td>
                    <td><?php echo strtoupper($new->sales_person) ?></td>
                    <td><?php echo strtoupper($new->nama_tl) ?></td>
                    <td><?php echo strtoupper(jenis_event($new->jenis_event)) ?></td>
                    <td><?php echo strtoupper($new->nama_event) ?></td>
                    <td><?php echo strtoupper($new->validator) ?></td>
                    <td><?php echo strtoupper($new->aktivator) ?></td>
                    <td><?php echo strtoupper($new->status) ?></td>
                    <td><?php echo strtoupper($new->tanggal_update) ?></td>
                    <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                    title="Click For Edit" href="<?php echo site_url('sales/edit/'.$new->psb_id) ?>">Edit</a> &nbsp; 
                    <a class="mybtn btn-danger btn-xs sales-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('sales/add/remove/'.$new->psb_id) ?>">Delete</a> </td>
                </tr>
               <?php } ?>
            </tbody>       

        </table>
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

    $(document).on('click','.sales-reset-btn',function(){  
        var main=$(this);
        swal({title: "Are you sure Want To Reset Password?",
        text: "You will be able to change this Data Password!",
        type: "warning",   showCancelButton: true,confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Yes, Change it!",closeOnConfirm: false,
        showLoaderOnConfirm: true }, function(){ 
            ///////////////     
            var link=$(main).attr("href");    
            $.ajax({
                url : link,
                beforeSend : function(){
                    $(".block-ui").css('display','block'); 
                },success : function(data){
                    $(".system-alert-box").empty();
                    swal("Reset Sucessfully!", "New Password = "+data, "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

});

</script>

