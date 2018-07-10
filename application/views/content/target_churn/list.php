<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Target Churn</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Target Churn <div class="add-button">
        <?php if($this->session->userdata('level')==4){ ?>
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('Admin/target_churn_add') ?>">Add</a>
        <?php } ?>
    <a class="mybtn btn-default" href="<?php echo site_url('Admin/target_churn_export/asyn') ?>" download="<?php echo site_url('Admin/target_churn_export/asyn') ?>">Export</a>
    </div></div>
    <?php $namaBulan = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des"); ?>
    <div class="panel-body" style="overflow: auto;">
        <table id="target_churn-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>ID</th>
                <th>REGION</th>
                <th>BULAN</th>
                <th>NILAI TARGET</th>
                <th>UPDATED BY</th>
                <th>UPDATED</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($target_churn as $new) { ?>    
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo strtoupper($new->nama_region) ?></td>
                    <td><?php echo $namaBulan[$new->bulan_target_churn]."-".$new->tahun_target_churn; ?></td>
                    <td><?php echo strtoupper($new->nilai_target_churn) ?></td>
                    <td><?php echo strtoupper($new->nama) ?></td>
                    <td><?php echo $new->tanggal_update;?></td>
                    <td>
                    <?php if($this->session->userdata('level')==4){ ?>
                    <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                    title="Click For Edit" href="<?php echo site_url('Admin/target_churn_edits/'.$new->id_target_churn) ?>">Edit</a> &nbsp; 
                    <a class="mybtn btn-danger btn-xs paket-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('Admin/target_churn_add/remove/'.$new->id_target_churn) ?>">Delete</a>
                    <?php } ?>
                    </td>
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

    $("#target_churn-table").DataTable();
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

    $(document).on('click','.paket-remove-btn',function(){  
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
                    document.location.href = '<?php echo base_url()?>Admin/target_churn_view';
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

    $(document).on('click','.export-btn',function(){

        var link=$(this).attr("href"); 
        // alert(link);
        $.ajax({
            method : "POST",
            url : link,
            beforeSend : function(){
                $(".block-ui").css('display','block'); 
            },success : function(data){ 
                window.open(link+'/asyn','_blank');
                $(".block-ui").css('display','none');               
            }
        });

        return false;
    });

});

</script>