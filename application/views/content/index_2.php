<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
	<?php $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP'); ?>
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Selamat Datang <?php echo $this->session->userdata('nama').' ('.$level[$this->session->userdata('level')].')'?></h4></div>


</div>