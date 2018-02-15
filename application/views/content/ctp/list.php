<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>CTP</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Customer Touch Point <div class="add-button">
    <a class="mybtn btn-default asyn-link" href="<?php echo site_url('feedbacks/add') ?>">Add CTP</a>
    </div></div>
    <div class="panel-body manage-client" >
        <table id="repeat-salesperson-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>Order_ID</th>
                <th>Order_Submit_Date</th>
                <th>Nama_Complete_Date</th>
                <th>Nama_Pelanggan</th>
                <th>User_ID</th>
                <th>Employee_Name</th>
                <th>Nama_Paket</th>
                <th>Upload_By</th>
                <th>Action</th>
            </thead>

            <tbody>
                <?php foreach($ctp as $new) { ?>    
                <tr>
                    <td><?php echo $new->order_id?></td>
                    <td><?php echo $new->order_submit_date ?></td>
                    <td><?php echo $new->order_completed_date ?></td>
                    <td><?php echo $new->msisdn_ctp ?></td>
                    <td><?php echo $new->user_id ?></td>
                    <td><?php echo $new->employee_name ?></td>
                    <td><?php echo $new->paket_id ?></td>
                    <td><?php echo $new->id_users ?></td>
                    <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                title="Click For Edit" href="<?php echo site_url('feedbacks/edit/'.$new->id_ctp) ?>">Edit</a> &nbsp; 
                <a class="mybtn btn-danger btn-xs kategori-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('ctp/add/remove/'.$new->id_ctp) ?>">Remove</a></td>
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
                swal("Deleted!", "Remove Sucessfully", "success"); 
                    $(".block-ui").css('display','none');
                }    
            });
        }); 
        return false;       
    }); 

});

</script>