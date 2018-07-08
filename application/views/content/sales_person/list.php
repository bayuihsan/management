<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Sales</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Sales Person <div class="add-button">
    <?php if($this->session->userdata('level')==4 || $this->session->userdata('level')==5 || $this->session->userdata('level')==3){ ?>
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('Admin/salesperson_add') ?>">Add</a>
    <?php } ?>
    <a class="mybtn btn-default" href="<?php echo site_url('Admin/salesperson_export/asyn') ?>" download="<?php echo site_url('Admin/salesperson_export/asyn') ?>">Export</a>
    </div></div>
    <div class="panel-body" style="overflow: auto;">
        <table id="repeat-salesperson-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th>
                <th>USER</th>
                <th>NAMA SALES</th>
                <th>BRANCH</th>
                <th>TL</th>
                <th>NO TELP</th>
                <th>NAMA BANK</th>
                <th>NO REKENING</th>
                <th>ATAS NAMA</th>
                <th>STATUS</th>
                <th>UPDATED</th>
                <th class="single-action">ACTION</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($salesperson as $new) { ?>    
                <tr>
                    <td><?php echo $no++?></td>
                    <td><?php echo $new->id_sales.' - '.strtoupper($new->user_sales) ?></td>
                    <td><?php echo strtoupper($new->nama_sales) ?></td>
                    <td><?php echo strtoupper($new->nama_branch) ?></td>
                    <td><?php echo strtoupper($new->nama) ?></td>
                    <td><?php echo strtoupper($new->no_telp) ?></td>
                    <td><?php echo strtoupper($new->nama_bank) ?></td>
                    <td><?php echo strtoupper($new->no_rekening) ?></td>
                    <td><?php echo strtoupper($new->atas_nama) ?></td>
                    <td><?php echo strtoupper($new->status) ?></td>
                    <td><?php echo strtoupper($new->tanggal_update) ?></td>
                    <td>
                        <?php if($this->session->userdata('level')==4 || $this->session->userdata('level')==5 || $this->session->userdata('level')==3){ ?>
                        <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                    title="Click For Edit" href="<?php echo site_url('Admin/salesperson_edits/'.$new->id_sales) ?>">Edit</a> &nbsp; 
                    <?php if($this->session->userdata('level')==4){ ?>
                    <a class="mybtn btn-danger btn-xs salesperson-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('Admin/salesperson_add/remove/'.$new->id_sales) ?>">Delete</a>
                    <?php }
                    } ?>
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

    $("#repeat-salesperson-table").DataTable();
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

    $(document).on('click','.salesperson-remove-btn',function(){  
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
                    document.location.href = '<?php echo base_url()?>Admin/salesperson_view';
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