<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Users</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<?php 
$level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
 ?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Users <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('users/add') ?>">Add Users</a>
    </div></div>
    <div class="panel-body">
        <table id="users-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th><th>USERNAME</th><th>NAMA USER</th>
                <th>BRANCH</th><th>CHANNEL</th><th>LEVEL</th><th>KETERANGAN</th><th>LAST LOGIN</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($users as $new) { ?>    
                <tr>
                <td class="date"><?php echo $no++; ?></td>
                <td><?php echo $new->id_users.' - '.strtoupper($new->username) ?></td>
                <td><?php echo strtoupper($new->nama) ?></td>
                <td><?php echo strtoupper($new->nama_branch) ?></td>
                <td><?php echo strtoupper($channel[$new->channel]) ?></td>
                <td><?php echo strtoupper($level[$new->level]) ?></td>
                <td><?php echo strtoupper($new->keterangan) ?></td>
                <td><?php echo strtoupper($new->last_login) ?></td>
                <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                title="Click For Edit" href="<?php echo site_url('users/edit/'.$new->id_users) ?>">Edit</a> &nbsp; 
                <a class="mybtn btn-danger btn-xs users-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('users/add/remove/'.$new->id_users) ?>">Hapus</a></td>
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

    $("#users-table").DataTable();
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

    $(document).on('click','.users-remove-btn',function(){  
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
                    document.location.href = '<?php echo base_url()?>/users';
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

});

</script>

