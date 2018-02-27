<!--Statt Main Content-->
<section>
<div class="main-content">
<div class="row">
	<?php $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP'); ?>
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Selamat Datang <?php echo $this->session->userdata('nama').' ('.$level[$this->session->userdata('level')].') di Aplikasi HVCare System Management V2'?></h4></div>



<?php if($this->session->userdata('level')!=4){ ?>
<!--Start Daily Report Line Chart-->
<div class="col-md-12 col-sm-12 col-lg-12">
    <div id="load_popup_modal_show_msisdn" class="modal fade" tabindex="-1"></div>
<!--Start Panel-->
<div class="panel panel-default">
    
    <!-- Default panel contents -->
    <div class="panel-heading"><?php echo $title?></div>
    <div class="panel-body">
    	
    	<table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
            <thead>    
                <th>NO</th>
                <th>MSISDN</th>
                <th>NAMA PELANGGAN</th>
                <th>BRANCH</th>
                <th>TL</th>
                <th>TANGGAL MASUK</th>
                <th>SERVICE</th>
                <th>STATUS</th>
                <th class="single-action">ACTION</th>
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
                            <td><?php echo isset($new->tanggal_masuk) ? date('Y-m-d', strtotime($new->tanggal_masuk)) : ''; ?></td>
                            <td><?php echo strtoupper($new->selisih). " Hari"; ?></td>
                            <td><?php echo strtoupper($new->status) ?></td>
                            <?php if($this->session->userdata('level')==4){ ?>
                            <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Edit" href="<?php echo site_url('sales/edit/'.$new->psb_id) ?>">Edit</a> &nbsp; 
                            <a class="mybtn btn-danger btn-xs sales-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('sales/add/remove/'.$new->psb_id) ?>">Delete</a> </td>
                            <?php }else{ ?>
                            <td><a style="cursor: pointer;" class="mybtn btn-info btn-xs" id="click_to_load_modal_popup_msisdn_<?php echo $new->msisdn?>"> Detail</a></td>
                            <?php } ?>
                            
                        </tr>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var $modal = $('#load_popup_modal_show_msisdn');
                                $('#click_to_load_modal_popup_msisdn_<?php echo $new->msisdn?>').on('click', function(){
                                    $modal.load('<?php echo base_url()?>sales/load_modal/',{'msisdn': "<?php echo $new->msisdn ?>",'id2':'2'},
                                    function(){
                                        $modal.modal('show');
                                    });

                                });
                            });

                        </script>
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