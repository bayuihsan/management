
<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Custom Fields</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->


<div class="col-md-8 col-lg-8 col-sm-8 custom_fields-div">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Custom Fields</div>
    <div class="panel-body add-client">  
    <form id="add-custom_fields">
      <input type="hidden" name="action" id="action" value="eksekusi"/>  
      <input type="hidden" name="id_users" id="id_users" value="<?php echo $this->session->userdata('id_users');?>"/>  
      <div class="form-group">
        <label for="acc_name">Query</label>
        <textarea class="form-control" name="input_query" id="input_query"></textarea>
      </div>
            
      <button type="submit" class="mybtn btn-submit"><i class="fa fa-refresh"></i> Eksekusi</button>
    </form>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->    
    
</div>
<div class="col-md-12 col-lg-12 col-sm-12">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Log Eksekusi </div>
    <div class="panel-body">
        <table id="repeat-custom_fields-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>No</th><th>Deskripsi</th><th>User</th>
                <th>Tanggal</th>
            </thead>

            <tbody>
                <?php $no=1; foreach($custom_fields as $new) { ?>    
                <tr>
                  <td><?php echo $no++;?></td>
                  <td><?php echo str_replace("`", "", $new->desc_query); ?></td>
                  <td><?php echo $new->nama ?></td>
                  <td class="date"><?php echo $new->tanggal ?></td>
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
</div><!--End Row-->
</div><!--End Main-content DIV-->
</section><!--End Main-content Section-->
<script type="text/javascript">
$(document).ready(function(){

if($(".sidebar").width()=="0"){
  $(".main-content").css("padding-left","0px");
} 

$("#repeat-custom_fields-table").DataTable();
$(".dataTables_length select").addClass("show_entries");

//for number only
$("#id_users").keypress(function (e) {
  //if the letter is not digit then display error and don't type anything
  if (e.which != 8 && e.which != 0 &&  (e.which < 48 || e.which > 57)) {
    //display error message
    return false;
  }
});


$('#add-custom_fields').on('submit',function(){    
  $.ajax({
    method : "POST",
    url : "<?php echo site_url('Admin/customfields_add/eksekusi') ?>",
    data : $(this).serialize(),
    beforeSend : function(){
      $(".block-ui").css('display','block'); 
    },success : function(data){ 
    if(data=="true"){  
      sucessAlert("Query Sucessfully"); 
      $(".block-ui").css('display','none');       
      $('#input_query').val("");     
    }else{
      failedAlert2(data);
      $(".block-ui").css('display','none');
    }   
    }
  });    
  return false;

});

});
</script>