<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Install</title>
	
   <link href='https://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url() ?>theme/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url() ?>theme/css/select2.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>theme/css/install.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body class="install-body">
<div class="row install-apps">
<div class="col-md-4 col-sm-6 col-lg-4 col-md-offset-4 col-sm-offset-3 col-lg-offset-4"> 
<div class="panel panel-info">
<div class="panel-heading">
<h3 class="panel-title text-center">Install Tricky Money Management</h3>
</div>
<div class="panel-body install-content-body">
<!-- Instruction -->
<div class="instruction">
<p><span class="glyphicon glyphicon-pencil"></span>
 application/config/database.php File Must To Be Writable</p>
<p><span class="glyphicon glyphicon-pencil"></span>
 application/config/config.php File Must To Be Writable</p>
<p><span class="glyphicon glyphicon-pencil"></span>
 application/config/route.php File Must To Be Writable</p>
 <p><button type="button" class="btn btn-default btn-block instruction-btn">
 Next&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button></p>
</div>
<!--End Instruction -->
<form class="form-horizontal" id="install-form-1" method="post" action="<?php echo site_url('Install/checkStep1') ?>">
  <div class="form-group">
    <label for="hostname" class="control-label">Host</label>
      <input type="text" class="form-control" value="localhost" name="hostname" id="hostname" placeholder="Database Hostname">
  </div>
  
   <div class="form-group">
    <label for="database" class="control-label">Database</label>
      <input type="text" class="form-control" name="database" id="database" placeholder="Database Name">
  </div>
  
   <div class="form-group">
    <label for="user" class="control-label"> DB User</label>
      <input type="text" class="form-control" name="user" id="user" placeholder="Database Username">
  </div>
  
  <div class="form-group">
    <label for="password" control-label">DB Pass</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Database Password">
  </div>

  <div class="form-group">
      <button type="submit" class="btn btn-default">
      Next&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
	  <img class="loader" src="<?php echo base_url() ?>theme/images/install.gif">
  </div>
</form>
</div>
</div>
</div>
</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url() ?>theme/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url() ?>theme/js/bootstrap.js"></script>
	<script src="<?php echo base_url() ?>theme/js/select2.min.js"></script>
	<script src="<?php echo base_url() ?>theme/js/install.js"></script>

  </body>
</html>