<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="user-scalable=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="shortcut icon" href="<?php echo base_url() ?>theme/images/favicon.ico">
<title>HVC Area 2</title>

<!-- Bootstrap -->
<link href='<?php echo base_url() ?>assets/css/roboto_slab.css' rel='stylesheet' type='text/css'>
<link href='<?php echo base_url() ?>assets/css/lora.css' rel='stylesheet' type='text/css'>
<link href='<?php echo base_url() ?>assets/css/raleway.css' rel='stylesheet' type='text/css'>

<link href="<?php echo base_url() ?>/theme/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/metisMenu.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/ionicons.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/c3.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/select2.css" rel="stylesheet">
<link href="<?php echo base_url() ?>/theme/css/bootstrap-datepicker.css" rel="stylesheet"> 
<link href="<?php echo base_url() ?>/theme/css/fullcalendar.css" rel="stylesheet">  
<link href="<?php echo base_url() ?>/theme/css/jquery.dataTables.min.css" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/theme/css/responsive.dataTables.css">
<link href="<?php echo base_url() ?>/theme/css/slicknav.css" rel="stylesheet">  
<link href="<?php echo base_url() ?>/theme/css/sweetalert.css" rel="stylesheet"> 


<link href="<?php echo base_url() ?>/theme/css/my-style.css" rel="stylesheet">
   
<!--jquery-->   
<script src="<?php echo base_url() ?>/theme/js/jquery.js"></script>
<script src='<?php echo base_url() ?>/theme/js/modernizr.js'></script>
<script src="<?php echo base_url() ?>theme/js/jquery.chained.min.js"></script>


</head>
<body>
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
<div class="row">
<header>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 headerbar">
<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 left-headerbar">
<ul class="leftheader-menu">
<li><a href="">HVCARES MANAGEMENT</a></li>
<li class="hide-class"><a class="hide-nav" href=""><i class="fa fa-bars"></i></a></li>
</ul>
</div>
<!-- End left header-->

<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 right-headerbar">
<div class="rightheader-menu">

<div class="header-nav-profile">
  <a id="profile" href=""><img src="<?php echo base_url() ?>/theme/images/avatar.jpg" alt="" />
    <span class="profile-info"><?php date_default_timezone_set(get_current_setting('timezone'));  echo $this->session->userdata('username'); ?>
      <small><?php $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'FOS ALR', 6=>'Validasi GraPARI', 7=>'FOS GraPARI', 8=>'Admin CTP'); echo $level[$this->session->userdata('level')]; ?></small>
    </span>
    <span class="caret"></span>
    </a>
    <ul class="dropdown-profile">
        <li><a href="" data-toggle="modal" data-target="#profileModal"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Profile</a></li>
        <li><a href="" data-toggle="modal" data-target="#passwordModal"><i class="fa fa-exchange"></i>&nbsp;&nbsp;&nbsp;Change Password</a></li>
        <li><a href="<?php echo site_url('User/logout') ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;&nbsp;Logout</a></li>
    </ul>
</div>
<!--End Header-nav-provile -->
</div>
</div>
<!-- End Right header-->

<!-- End Header-->
<!--Start Sidebar-->
</div>
</header>


<!-- Profile Modal -->
<div id="profileModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Profile</h4>
</div>
<div class="modal-body">
<?php 
$channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
?>
 <!--Start Panel-->
<div class="panel panel-default">
    <!-- Default panel contents -->
  <div class="panel-heading">Edit Profile</div>
  <div class="panel-body add-client">
  <form id="edit-profile" method="post" action="<?php echo site_url('Admin/updateProfile') ?>">
  <p>Profile User</p>
  <div class="form-group">
    <label for="profle-username">Username</label>
    <input type="text" value="<?php echo $this->session->userdata('username') ?>"
    class="form-control" name="username" id="profle-username" readonly>
  </div>
  <div class="form-group">
    <label for="profle-nama">Nama</label>
    <input type="text" value="<?php echo $this->session->userdata('nama') ?>"
    class="form-control" name="nama" id="nama">
  </div>
  <div class="form-group">
    <label for="profle-no_hp">No HP</label>
    <input type="text" value="<?php echo $this->session->userdata('no_hp') ?>"
     class="form-control" name="no_hp" id="no_hp">
  </div>
  <div class="form-group">
    <label for="profle-no_hp">Keterangan</label>
    <input type="text" value="<?php echo $this->session->userdata('keterangan') ?>"
     class="form-control" name="keterangan" id="keterangan" readonly>
  </div>
  <hr>
  <p>Data Mandatory</p>
  <div class="form-group">
    <label for="profle-branch">Branch</label>
    <input type="text" value="<?php echo $this->session->userdata('nama_branch') ?>"
     class="form-control" name="nama_branch" id="nama_branch" readonly>
    <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $this->session->userdata('branch_id')?>" readonly>
  </div>
  <div class="form-group">
    <label for="profle-channel">Channel</label>
    <input type="text" value="<?php echo $channel[$this->session->userdata('channel')]; ?>"
     class="form-control" name="channel_x" id="channel_x" readonly>
    <input type="hidden" name="channel" id="channel" value="<?php echo $this->session->userdata('channel')?>" readonly>
  </div>
  <div class="form-group">
    <label for="profle-level">Level</label>
    <input type="text" value="<?php echo $level[$this->session->userdata('level')]; ?>"
     class="form-control" name="level_x" id="level_x" readonly>
    <input type="hidden" name="level" id="level" value="<?php echo $this->session->userdata('level')?>" readonly>
  </div>  
  <div class="form-group">
    <label for="profle-level">Last Login</label>
    <input type="text" value="<?php echo $this->session->userdata('last_login'); ?>"
     class="form-control" name="last_login_x" id="last_login_x" readonly>
    <input type="hidden" name="last_login" id="last_login" value="<?php echo $this->session->userdata('last_login')?>" readonly>
  </div>  
        
  <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Update</button>
</form>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel--> 
</div>

</div>
</div>
</div>
<!--End Model-->


<!-- Modal -->
<div id="passwordModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
        
       <!--Start Panel-->
        <div class="panel panel-default">
          <!-- Default panel contents -->
          <div class="panel-heading">Change Password</div>
          <div class="panel-body add-client">
            <form id="change-password" method="post" action="<?php echo site_url('Admin/changePassword') ?>">
            <div class="form-group">
              <label for="new-password">New Password</label>
              <input type="password" 
              class="form-control" name="new-password" id="new-password">
            </div>
            <div class="form-group">
              <label for="confrim-change">Confrim Password</label>
              <input type="password" 
              class="form-control" name="confrim-password" id="confrim-password">
            </div> 
            <button type="submit"  class="mybtn btn-submit"><i class="fa fa-check"></i> Update</button>
            </form>
          </div>
          <!--End Panel Body-->
        </div>
      <!--End Panel--> 
      </div>

    </div>
  </div>
</div>
<!--End Model-->

<!-- Calculator Model -->
<!-- Modal -->
<div id="calculatorModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Calculator</h4>
</div>
<div class="modal-body">
  
  <div class="calculator">
  <input type="text" readonly>
  <div class="row">
    <div class="key">1</div>
    <div class="key">2</div>
    <div class="key">3</div>
    <div class="key last">0</div>
  </div>
  <div class="row">
    <div class="key">4</div>
    <div class="key">5</div>
    <div class="key">6</div>
    <div class="key last action instant">cl</div>
  </div>
  <div class="row">
    <div class="key">7</div>
    <div class="key">8</div>
    <div class="key">9</div>
    <div class="key last action instant">=</div>
  </div>
  <div class="row">
    <div class="key action">+</div>
    <div class="key action">-</div>
    <div class="key action">x</div>
    <div class="key last action">/</div>
  </div>

</div>

</div>

</div>
</div>
</div>
<!--End Calculator Model-->

<!-- Calendar Model -->
<!-- Modal -->
<div id="calendarModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Calendar</h4>
</div>
<div class="modal-body">
<!-- Create Calendar-->
<div id="current-calendar"></div>

</div>

</div>
</div>
</div>
<!--End Calendar Model-->


<section>
<div class="content-area">
<aside>
<div class="sidebar">

<ul id="menu" class="menu-helper asyn-menu">
<li class="has-sub"><a class="active-menu" href="#"><i  class="fa fa-tachometer"></i>
<span class="title">Dashboard</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/home') ?>"><i class="fa fa-angle-double-right"></i>Home</a></li>
    <li><a href="<?php echo site_url('Admin/dashboard') ?>"><i class="fa fa-tachometer"></i> Dashboard HVC</a></li>
</ul>
</li>

<li class="has-sub">
<a href="#"><i class="fa fa-search"></i>
<span class="title">Pencarian</span></a>
<ul class="collapse">
    <li><a class="asyn-bast" href="<?php echo site_url('Admin/sales_cek_msisdn') ?>""><i class="fa fa-search"></i> MSISDN</a></li>
    <li><a class="asyn-bast" href="<?php echo site_url('Admin/cek_bast') ?>""><i class="fa fa-search"></i> NO BAST</a></li>
</ul>
</li>
<?php if($this->session->userdata('level')==4 || $this->session->userdata('level')==5) { ?>
<li class="has-sub">
<a href="#"><i class="fa fa-credit-card"></i>
<span class="title">BAST</span></a>
<ul class="collapse">
    <li><a class="asyn-bast" href="<?php date_default_timezone_set(get_current_setting('timezone'));  echo site_url('Admin/bast_create') ?>"><i class="fa fa-plus-square"></i> Add Bast</a></li>
    <li><a class="asyn-bast" href="<?php echo site_url('Admin/bast_view') ?>"><i class="fa fa-book"></i> Manage Bast</a></li>
</ul>
</li>
<li class="has-sub">
<a href="#"><i class="fa fa-language"></i>
<span class="title">Sales</span></a>
<ul class="collapse">
    <li><a class="asyn-sales" href="<?php echo site_url('Admin/sales_view') ?>"><i class="fa fa-book"></i> Manage Sales</a></li>
    <?php /*<li><a class="asyn-sales" href="<?php echo site_url('cm_ekstrak/view_aktif') ?>"><i class="fa fa-book"></i> CM New Activation</a></li>
    <li><a class="asyn-sales" href="<?php echo site_url('cm_ekstrak/view_churn') ?>"><i class="fa fa-book"></i> CM New Deactivation</a></li>*/?>
    <?php $qgenerate = $this->db->query("select * from new_psb_temp order by nama_table desc")->result();
    foreach($qgenerate as $row){ ?>
    <li><a class="asyn-sales" href="<?php echo site_url('Admin/generatetable_load_table/'.$row->id_temp) ?>"><i class="fa fa-book"></i> <?php echo $row->nama_table?></a></li>
    <?php }
    ?>
    <?php if($this->session->userdata('level')==4 || $this->session->userdata('level')!=5){ ?>
    <li><a href="<?php echo site_url('Admin/ctp_add') ?>"><i class="fa fa-plus-square"></i> Add Data CTP</a></li>
    <li><a href="<?php echo site_url('Admin/ctp_view') ?>"><i class="fa fa-calendar-plus-o"></i> Data Sales CTP</a></li>
    <?php } ?>
    
</ul>
</li>
<?php } ?> 
<?php if($this->session->userdata('level')==4){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-language"></i>
<span class="title">Churn</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/churn_add') ?>"><i class="fa fa-plus-square"></i> Add Churn</a></li>
    <li><a href="<?php echo site_url('Admin/churn_view') ?>"><i class="fa fa-book"></i> Data Churn</a></li>
</ul>
</li>
<?php } ?>
<?php if($this->session->userdata('level')==4){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-book"></i>
<span class="title">Target Sales</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/target_add') ?>"><i class="fa fa-plus-square"></i> Add Target Sales</a></li>
    <li><a href="<?php echo site_url('Admin/target_view') ?>"><i class="fa fa-book"></i> Data Target Sales</a></li>
</ul>
</li>
<?php } ?>
<?php if($this->session->userdata('level')==4){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-book"></i>
<span class="title">Target Churn</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/target_churn_add') ?>"><i class="fa fa-plus-square"></i> Add Target Churn</a></li>
    <li><a href="<?php echo site_url('Admin/target_churn_view') ?>"><i class="fa fa-book"></i> Data Target Churn</a></li>
</ul>
</li>
<?php } ?>
<?php if($this->session->userdata('level')==3 || $this->session->userdata('level')==4 || $this->session->userdata('level')==5) { ?>
<li class="has-sub">
<a href="#"><i class="fa fa-university"></i>
<span class="title">MSISDN</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/msisdn_add') ?>"><i class="fa fa-plus-square"></i> Add MSISDN</a></li>
    <li><a href="<?php echo site_url('Admin/msisdn_view') ?>"><i class="fa fa-book"></i> Data MSISDN</a></li>
</ul>
</li>
<?php } ?>
<?php if($this->session->userdata('level')==3){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-suitcase"></i>
<span class="title">Sales Person</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/salesperson_add') ?>"><i class="fa fa-calendar-plus-o"></i> Add Sales Person</a></li>
    <li><a href="<?php echo site_url('Admin/salesperson_view') ?>"><i class="fa fa-calendar-plus-o"></i> Manage Sales Person</a></li>
</ul>
</li>
<?php } ?>

<li class="has-sub">
<a href="#"><i class="fa fa-suitcase"></i>
<span class="title">widgets</span></a>
<ul class="collapse">
    <li><a href="#" data-toggle="modal" data-target="#calculatorModal"><i class="fa fa-calculator"></i> Calculator</a></li>
    <li><a href="#" data-toggle="modal" data-target="#calendarModal"><i class="fa fa-calendar"></i> Calendar</a></li>
</ul>
</li>
<li class="has-sub">
<a href="#"><i class="fa fa-area-chart"></i>
<span class="title">Reporting</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/reports_allbranch') ?>"><i class="fa fa-angle-double-right"></i> All Branch Report</a></li>
    <li><a href="<?php echo site_url('Admin/reports_paket') ?>"><i class="fa fa-angle-double-right"></i> MoM Report by Paket</a></li>
    <li><a href="<?php echo site_url('Admin/reports_branch') ?>"><i class="fa fa-angle-double-right"></i> MoM Report by Branch</a></li>
    <li><a href="<?php echo site_url('Admin/reports_tl') ?>"><i class="fa fa-angle-double-right"></i> MoM Report by TL</a></li>
    <li><a href="<?php echo site_url('Admin/reports_sub_channel') ?>"><i class="fa fa-angle-double-right"></i> MoM Report by Sub Channel</a></li>
    <li><a href="<?php echo site_url('Admin/reports_status_daily') ?>" target="_BLANK"><i class="fa fa-angle-double-right"></i> Report Daily by Status</a></li>
    <li><a href="<?php echo site_url('Admin/reports_sales_person') ?>"><i class="fa fa-angle-double-right"></i> Report by Sales Person</a></li>
    <li><a href="<?php echo site_url('Admin/reports_service_level') ?>"><i class="fa fa-angle-double-right"></i> SLA View Report</a></li>
    <li><a href="<?php echo site_url('Admin/reports_fee_sales_tsa') ?>"><i class="fa fa-angle-double-right"></i> FEE SALES TSA</a></li>
    <li><a href="<?php echo site_url('Admin/reports_fee_sales_tl') ?>"><i class="fa fa-angle-double-right"></i> FEE SALES TL</a></li>
    <li><a href="<?php echo site_url('Admin/reports_scn_weekly') ?>"><i class="fa fa-angle-double-right"></i> SCN Weekly Report</a></li>
</ul>
</li>
<?php if($this->session->userdata('level')<6){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-suitcase"></i>
<span class="title">Master</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/region_view') ?>"><i class="fa fa-calendar-plus-o"></i> Region</a></li>
    <li><a href="<?php echo site_url('Admin/branch_view') ?>"><i class="fa fa-calendar-plus-o"></i> Branch</a></li>
    <li><a href="<?php echo site_url('Admin/grapari_view') ?>"><i class="fa fa-calendar-plus-o"></i> Grapari</a></li>
    <li><a href="<?php echo site_url('Admin/reason_view') ?>"><i class="fa fa-calendar-plus-o"></i> Reason</a></li>
    <li><a href="<?php echo site_url('Admin/paket_view') ?>"><i class="fa fa-calendar-plus-o"></i> Paket</a></li>
    <li><a href="<?php echo site_url('Admin/kategoripaket_view') ?>"><i class="fa fa-calendar-plus-o"></i> Kategori Paket</a></li>
    <li><a href="<?php echo site_url('Admin/salesperson_view') ?>"><i class="fa fa-calendar-plus-o"></i> Sales Person</a></li>
    <li><a href="<?php echo site_url('Admin/saleschannel_view') ?>"><i class="fa fa-calendar-plus-o"></i> Sales Channel</a></li>
    <li><a href="<?php echo site_url('Admin/feedback_view') ?>"><i class="fa fa-calendar-plus-o"></i> Feedbacks</a></li>
</ul>
</li>
<?php } ?>
<?php if($this->session->userdata('level')==4){ ?>
<li class="has-sub">
<a href="#"><i class="fa fa-book"></i>
<span class="title">Table Generate</span></a>
<ul class="collapse">
  
    <li><a href="<?php echo site_url('Admin/generatetable_add') ?>"><i class="fa fa-calendar-plus-o"></i> Add Generate</a></li>
  
    <li><a href="<?php echo site_url('Admin/generatetable_view') ?>"><i class="fa fa-book"></i> View Table</a></li>
</ul>
</li>

<li class="has-sub">
<a href="#"><i class="fa fa-cog"></i>
<span class="title">Administration</span></a>
<ul class="collapse">
    <li><a href="<?php echo site_url('Admin/users_view') ?>"><i class="fa fa-users"></i> User Management</a></li>
    <li><a href="<?php echo site_url('Admin/generalSettings') ?>"><i class="fa fa-cogs"></i> General Settings</a></li>
    <li><a href="<?php echo site_url('Admin/customfields_add') ?>"><i class="fa fa-language"></i>Custom Fields</a></li>
    <li><a href="<?php echo site_url('Admin/backupDatabase') ?>"><i class="fa fa-database"></i> Backup Database</a></li>
</ul>
</li>
<?php } ?>
</ul>
</div>
</aside>
<!--Javascript Code -->
<script type="text/Javascript">

</script>



<!--End Sidebar-->
<div class="asyn-div">