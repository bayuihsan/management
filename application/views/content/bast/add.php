<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
<div class="inner-contatier">    
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Berita Acara Serah Terima</h4></div>

<!--Alert-->
<div class="system-alert-box">
<div class="alert alert-success ajax-notify"></div>
</div>
<!--End Alert-->

<?php date_default_timezone_set(get_current_setting('timezone')); ?>
<div class="col-md-5 col-lg-5 col-sm-5">
<!--Start Panel-->
  <div class="panel panel-default">
  <!-- Default panel contents -->
    <div class="panel-heading">Add BAST</div>
    <div class="panel-body add-client">
      <form id="add-bast-account">
        <input type="hidden" name="action" id="action" value="insert"/>  
        <input type="hidden" name="id_header" id="id_header" value=""/>    
        <div class="form-group"> 
          <label for="account">NO BAST</label>
          <input type="text" maxlength="30" class="form-control" name="bno_bast" id="bno_bast" value="T<?php echo date('Ymdhis')?>" readonly/>   
        </div> 
        <div class="form-group"> 
          <label for="bbranch">Branch</label>
          <select name="bbranch" class="form-control" id="bbranch">  
            <option value="">Pilih Branch</option>
            <?php foreach ($branch as $new) {?>
            <option value="<?php echo $new->branch_id ?>"><?php echo "(".$new->branch_id.") ".$new->nama_branch ?></option>
            <?php } ?>
          </select>      
        </div>
        <div class="form-group"> 
          <label for="account">Tanggal Masuk</label>
          <input type="text" maxlength="30" class="form-control" name="btanggal_masuk" id="btanggal_masuk" value="<?php echo date('Y-m-d h:i:s')?>" readonly/>   
        </div> 
        <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Save</button>
      </form>
    </div>
    <!--End Panel Body-->
  </div>
<!--End Panel-->       
</div>
    
  
    
<div class="col-md-7 col-lg-7 col-sm-7">
<!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Data BAST</div>
    <div class="panel-body">
       
        <div class="scroll-div"> 
          <table id="accounts-table" class="table table-striped table-bordered">
            <th class="sc-col-4">NO BAST</th><th class="sc-col-2">BRANCH</th><th class="sc-col-3">TANGGAL</th><th class="sc-col-3">Action</th>
            <?php  if(empty($bast_list)){
              echo "<tr><td class='sc-col-4 t_no_bast'></td><td class='sc-col-2 t_branch'></td><td class='sc-col-3 t_type'></td>";
              echo "<td class='sc-col-3'></td></tr>";
            }else{ ?>    
           <?php foreach($bast_list as $list){ ?>
           
            <tr>
              <td class="a_no_bast sc-col-4"><?php echo $list->no_bast ?></td>
              <td class="a_nama_branch sc-col-2"><?php echo $list->branch_id ?></td> 
              <td class="a_tanggal_masuk sc-col-3"><?php echo $list->tanggal_masuk ?></td> 
              <td class="sc-col-3"><a class="mybtn btn-info btn-xs bast-edit-btn"  href="<?php echo $list->id_header ?>">Edit</a>
              <a class="mybtn btn-danger btn-xs bast-remove-btn"  href="<?php echo site_url('bast/create/remove/'.$list->id_header) ?>">Remove</a></td>     
            </tr>
            
            <?php } 
            }?>
    
          </table>
        </div>
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
$(document).ready(function() {
  var myRow;    
  $("#bbranch").select2();

  $(".scroll-div").niceScroll({
    cursorwidth: "8px",cursorcolor:"#7f8c8d",cursorborderradius:"0px"
  });

  $('#add-bast-account').on('submit',function(){    
    $.ajax({
      method : "POST",
      url : "<?php echo site_url('bast/create/insert') ?>",
      data : $(this).serialize(),
      beforeSend : function(){
        $(".block-ui").css('display','block'); 
      },success : function(data){ 
        var json = JSON.parse(data);   
        if(json['result']=="true" ){  
          if(json['action']=="insert"){   
            appendRow(json['last_id']);
          }else if(json['action']=="update"){     
            updateRow();
          }
          sucessAlert("Saved Sucessfully"); 
          $(".block-ui").css('display','none');     
          $('#add-bast-account')[0].reset(); 
          $("#bbranch").select2("val","");     
        }else{
          failedAlert(json['message']);
          $(".block-ui").css('display','none');
        }   
      }
    });    
    return false;
  });

  $(document).on('click','.bast-remove-btn',function(){  
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
      
  $(document).on('click','.bast-edit-btn',function(){ 
    var main=$(this);
    $("#action").val("update");
    $("#id_header").val($(main).attr("href"));
    $("#bno_bast").val($(main).closest("tr").find(".a_no_bast").html());  
    $("#btanggal_masuk").val($(main).closest("tr").find(".a_tanggal_masuk").html());  
    $("#bbranch").select2("val",$(main).closest("tr").find(".a_nama_branch").html()); 
    //get table index
    var tr = $(main).closest('tr');
    myRow = tr.index();

    return false;    
  }); 


  function appendRow(id){
    var base='<?php echo base_url() ?>'+'bast/create/remove/'+id;    
    var edit_link="<a class='mybtn btn-info btn-xs bast-edit-btn' href='"+id+"'>Edit</a>";
    var remove_link="<a class='mybtn btn-danger btn-xs bast-remove-btn' href='"+base+"'>Remove</a>";
    /*
    $("#accounts-table tr:first").after("<tr><td class='a_name sc-col-4'>"+$("#account").val()+"</td>"+
    "<td class='a_type sc-col-3'>"+$("#account-type").val()+"</td><td class='sc-col-3'>"+edit_link+" "+remove_link+"</td></tr>"); 
    */
    $("<tr><td class='a_no_bast sc-col-4'>"+$("#bno_bast").val()+"</td>"+
    "<td class='a_nama_branch sc-col-3'>"+$("#bbranch").val()+"</td><td class='a_tanggal_masuk sc-col-3'>"+$("#btanggal_masuk").val()+"</td><td class='sc-col-3'>"+edit_link+" "+remove_link+"</td></tr>").insertBefore("#accounts-table tr:first");
  }

  function updateRow(){
    var an=$("#account").val();
    var at=$("#account-type").val();

    var x = document.getElementById("accounts-table").rows[myRow].cells;
    x[0].innerHTML = an;
    x[1].innerHTML = at;
    $("#action").val("insert");
  }
    
    
});

</script>

