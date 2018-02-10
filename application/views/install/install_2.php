
<div class="col-md-6 col-lg-6 col-sm-6 col-md-offset-3 col-lg-offset-3 col-sm-offset-3">
<!--Start Panel-->
<div class="panel panel-info">
    <!-- Default panel contents -->
    <div class="panel-heading text-center">Install Tricky Money Management</div>
    <div class="panel-body install-content-body">
    <form id="final-install" method="post" action="<?php echo site_url('Install/processInstall') ?>">
   <div class="form-group"> 
    <label for="account">Company Name</label>
    <input type="text" class="form-control" value="" name="company-name" id="company-name"/>   
  </div> 
        
        
    <div class="form-group"> 
    <label for="timezone">Time Zone (Important)</label>
    <select name="timezone" class="form-control" id="timezone"> 
    <?php foreach($timezone as $key => $value){ ?> 
    <option value="<?php echo $value['ZONE'] ?>"><?php echo "(".$value['GMT'].") ".$value['ZONE'] ?></option>
    <?php } ?>
    </select>      
    </div>                 
 
     <div class="form-group"> 
    <label for="cur-symbol">Currency Symbol ( $ )</label>
    <input type="text" class="form-control" value="$" name="cur-symbol" id="cur-symbol"/>   
    </div>
                       
   <div class="form-group"> 
    <label for="username">Username</label>
    <input type="username" class="form-control" value="" name="username" id="username"/>   
  </div>  
  <div class="form-group"> 
    <label for="email">Email (Important)</label>
    <input type="email" class="form-control" value="" name="email" id="email"/>   
  </div> 
  <div class="form-group"> 
    <label for="password">Password (Important)</label>
    <input type="password" placeholder="Min 5 Character" minlength="5" maxlength="15" class="form-control" value="" name="password" id="password"/>   
  </div>  
                                                                                                                 
  <button type="submit"  class="btn btn-default">
  <span class="glyphicon glyphicon-ok"></span>&nbsp;Finish</button>
  <img class="loader" src="<?php echo base_url() ?>theme/images/install.gif">
   </form>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->       
</div>
<script type="text/javascript">
$("#timezone").select2();


</script>


