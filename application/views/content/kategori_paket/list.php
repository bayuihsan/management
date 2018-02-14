<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Kategori Paket</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Manage Kategori Paket <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('kategori_paket/add') ?>">Add Paket</a>
    </div></div>
    <div class="panel-body">
        <table id="repeat-branch-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Harga</th>
                <th>Last Update</th>
                <th class="single-action">Action</th>
            </thead>

            <tbody>
                <?php foreach($kategori_paket as $new) { ?>    
                <tr>
                <td><?php echo strtoupper ($new->id_kategori) ?></td>
                <td><?php echo strtoupper ($new->nama_kategori) ?></td>
                <td><?php echo get_current_setting('currency_code')." ".number_format($new->harga_kategori) ?></td>
                <td><?php echo strtoupper ($new->last_update) ?></td>
                <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                title="Click For Edit" href="<?php echo site_url('kategori_paket/edit/'.$new->id_kategori) ?>">Edit</a> &nbsp; 
                <a class="mybtn btn-danger btn-xs kategori-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('kategori_paket/add/remove/'.$new->id_kategori) ?>">Remove</a></td>
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

    $("#repeat-branch-table").DataTable();
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

    $(document).on('click','.kategori-remove-btn',function(){  
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
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 
});
</script>