<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
	<?php $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'FOS ALR', 6=>'Validasi GraPARI', 7=>'FOS GraPARI', 8=>'Admin CTP'); ?>
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Selamat Datang <?php echo ucwords(strtolower($this->session->userdata('username')));?></h4></div>


<?php if($this->session->userdata('level')!=4){ ?>
<!--Start Daily Report Line Chart-->
<div class="col-md-12 col-sm-12 col-lg-12">
    <div id="load_popup_modal_show_msisdn" class="modal fade" tabindex="-1"></div>
<!--Start Panel-->
<div class="panel panel-default">
    
    <!-- Default panel contents -->
    <div class="panel-heading"><?php echo $title." (".$jumlah_sales.")"; ?></div>  
    <div class="panel-body" style="overflow: auto; font-size: 12px; font-family: tahoma;">
    	
    	<table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th>
                <th>MSISDN</th>
                <th>NAMA PELANGGAN</th>
                <th>BRANCH</th>
                <th>TL</th>
                <th>SLA</th>
                <th>TANGGAL MASUK</th>
                <th>TANGGAL VALIDASI</th>
                <th>TANGGAL AKTIF</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </thead>

            <tbody>
                <?php $no=1;
                    foreach($sales as $new) { ?>    
                        <tr>
                            <td class="date"><?php echo $no++; ?></td>
                            <td><?php echo $new->psb_id.' - '.strtoupper($new->msisdn) ?></td>
                            <td><?php echo strtoupper($new->nama_pelanggan) ?></td>
                            <td><?php echo strtoupper($new->nama_branch) ?></td>
                            <td><?php echo strtoupper($new->nama_tl) ?></td>
                            <td><?php echo strtoupper($new->selisih). " Hari"; ?></td>
                            <td><?php echo isset($new->tanggal_masuk) ? date('Y-m-d', strtotime($new->tanggal_masuk)) : ''; ?></td>
                            <td><?php echo isset($new->tanggal_validasi) ? date('Y-m-d', strtotime($new->tanggal_validasi)) : ''; ?></td>
                            <td><?php echo isset($new->tanggal_aktif) ? date('Y-m-d', strtotime($new->tanggal_aktif)) : ''; ?></td>
                            <td><?php echo strtoupper($new->status) ?></td>
                            <td><?php if($this->session->userdata('level')==4 || $this->session->userdata('level')>5){  
                                if($new->status=='valid' && ($this->session->userdata('level')==7 || $this->session->userdata('level')==4)){ ?>
                              <a class="mybtn btn-success btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Aktivasi" href="<?php echo site_url('Admin/bast_aktivasi/'.$new->no_bast.'/'.$new->psb_id) ?>">Aktivasi</a>
                              <?php }else{
                                    if($new->status!="sukses" && $this->session->userdata('level')==6){ ?>
                              <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Validasi" href="<?php echo site_url('Admin/bast_validasi/'.$new->no_bast.'/'.$new->psb_id) ?>">Validasi</a> 
                        <?php   } } }
                             ?></td>
                            
                        </tr>
                <?php } ?>
                
            </tbody>       

        </table>
    	
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End Daily Report Col-->

<script type="text/javascript">
	$(document).ready(function() {
		$("#sales-table").DataTable();
	    $(".dataTables_length select").addClass("show_entries");
	});
</script>
<?php }?>
</section>