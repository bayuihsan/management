<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="<?php echo base_url() ?>/theme/images/favicon.ico">
    <!--<link rel="icon" type="image/png" href="">-->
    <title>Login</title>
    <!-- Bootstrap -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url() ?>/theme/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/theme/css/login.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/theme/css/font-awesome.css" rel="stylesheet">
    <!--<link href="css/jquery-ui.css" rel="stylesheet">-->

  </head>
  <!-- <body class="login-body" style="background-image:url('<?php //echo base_url()?>image/halo.png'); background-size:100% 100%; background-repeat:no-repeat; background-attachment: fixed;"> -->
  <body class="login-body">
    <div class="block-ui">
      <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
      </div>
    </div>  

    <div id="wrapper">
      <div class="login-div">
        <div class="system-name col-md-4 col-lg-4 col-sm-6 col-md-offset-4 col-lg-offset-4 col-sm-offset-3">
          <div class="login-logo"><img src="<?php echo base_url() ?>uploads/<?php  echo get_current_setting('logo_path'); ?>" alt=""/></div>
          <b><h3 style="color: ;"><?php  echo get_current_setting('company_name'); ?></h3></b>
        </div>

        <!--Alert-->
        <div class="system-alert-box col-md-4 col-lg-4 col-sm-6 col-md-offset-4 col-lg-offset-4 col-sm-offset-3">
          <div class="alert alert-success ajax-notify"></div>
        </div>
        <!--End Alert-->

        <div class="col-md-4 col-lg-4 col-sm-6 login-panel col-md-offset-4 col-lg-offset-4 col-sm-offset-3">
          <h3><i class="ace-icon fa fa-coffee green"></i> Please Enter Your Information </h3>
          <form class="login-form" method="post" action="<?php echo site_url('User/varifyUser') ?>">


            <input type="text" class="my-control user-icon" name="username" placeholder="Username"/>
            <input type="password" class="my-control pass-icon" name="password" placeholder="Password"/>
            <button type="submit" class="my-btn">Login</button>
          </form>

          <p></p>
        </div>
      </div>
    </div><!-- End Wrapper -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <script src="<?php echo base_url() ?>/theme/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url() ?>/theme/js/bootstrap.js"></script>
    <!--<script src="js/jquery-ui.js"></script>-->

    <script type="text/JavaScript">
      $(document).ready(function(){
        $('.login-form').on('submit',function(){
          var link=$(this).attr("action");
          $.ajax({
            method : "POST",
            url : link,
            data : $(this).serialize(),
            beforeSend : function(){
              $(".my-btn").html('');
              //$(".my-btn").addClass('loading');
              $(".block-ui").css("display","block");
            },success : function(data){ 
              if(data=='true'){
                $(".block-ui").css("display","none"); 
                if (!$(".ajax-notify").length){
                  $(".system-alert-box").append("<div class='alert alert-success ajax-notify'></div>");
                }   
                $('.ajax-notify').css("display","block"); 
                $('.ajax-notify').addClass("alert-success"); 
                $('.ajax-notify').removeClass("alert-danger");     
                $('.ajax-notify').html('Login Sucessfully');  
                //$(".my-btn").removeClass('loading');
                $(".my-btn").html('Login');
                $(".block-ui").css("display","none");  
                window.location.href ="<?php echo site_url('Admin/home') ?>";

              }else{
                if (!$(".ajax-notify").length){
                  $(".system-alert-box").append("<div class='alert alert-success ajax-notify'></div>");
                }   
                $('.ajax-notify').css("display","block"); 
                $('.ajax-notify').removeClass("alert-success"); 
                $('.ajax-notify').addClass("alert-danger");     
                $('.ajax-notify').html('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <i class="fa fa-times-circle"></i> Username or Password Is Incorrect !');  
                $(".my-btn").removeClass('loading');
                $(".my-btn").html('Login');
                $(".block-ui").css("display","none");  
              }
            }
          });
          return false;
        });

      });

    </script>

  </body>
</html>