<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>cm_ekstrak</h4></div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Sales New Actiivation 2018 <div class="add-button">
    <a class="mybtn btn-default export-btn" href="<?php echo site_url('cm_ekstrak/export') ?>">Export to Excel</a>
    </div></div>
    <div class="panel-body">
        <table id="repeat-cm_ekstrak-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th><th>MSISDN</th><th>NAME</th><th>ACTIVATION DATE</th>
                <th>HLR_REGIONAL</th><th>REGION NAME</th><th>GRAPARI NAME</th>
                <th>CITY</th><th>ZIP CODE</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($aktif as $new) { ?>    
                <tr>
                    <td class="date"><?php echo $no++; ?></td>
                    <td><?php echo strtoupper($new->msisdn) ?></td>
                    <td><?php echo strtoupper($new->name) ?></td>
                    <td><?php echo strtoupper($new->activation_date) ?></td>
                    <td><?php echo strtoupper($new->hlr_regional) ?></td>
                    <td><?php echo strtoupper($new->region_name) ?></td>
                    <td><?php echo strtoupper($new->grapari_name) ?></td>
                    <td><?php echo strtoupper($new->city) ?></td>
                    <td><?php echo strtoupper($new->zip_code) ?></td>
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

    $("#repeat-cm_ekstrak-table").DataTable();
    $(".dataTables_length select").addClass("show_entries");

    $(document).on('click','.edit-btn',function(){

        var link=$(this).attr("href"); 
        // alert(link);
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

    $(document).on('click','.cm_ekstrak-remove-btn',function(){  
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
                    document.location.href = '<?php echo base_url()?>/cm_ekstrak';
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

