
<div id="load_popup_modal_contant" class="" role="dialog">

	<div class="modal-dialog modal-lg">
	<?php
	//$id1 = $_POST["id1"];
	//$id2 = $_POST["id2"];
	?>
	  
		<div class="modal-content">
			<div class="modal-header">  
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DETAIL <?php echo isset($msisdn) ? $msisdn : ""; ?></h4>
			</div>
			<div id="validation-error"></div>
			<div class="cl"></div>
			<div class="modal-body">
				<style style="text/css">
	                /* Define the hover highlight color for the table row */
	                .hoverTable tr:hover {
	                      background-color: #b8d1f3;
	                }
	            </style>
	            <?php
	            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
				
				function discount($id){
				    if($id=="1"){
				        echo 0;
				    }elseif($id=="2"){
				        echo 25;
				    }elseif($id=="3"){
				        echo 50;
				    }elseif($id=="4"){
				        echo 100;
				    }elseif($id=="5"){
				        echo 'FreeMF';
				    }
				}

				function periode($id){
				    if($id=="1"){
				        echo 0;
				    }elseif($id=="2"){
				        echo 1;
				    }elseif($id=="3"){
				        echo 3;
				    }elseif($id=="4"){
				        echo 6;
				    }elseif($id=="5"){
				        echo 12;
				    }
				}

				function jenis_event($id){
				    if($id=="1"){
				        echo strtoupper("Industrial Park");
				    }elseif($id=="2"){
				        echo strtoupper("Mall to Mall");
				    }elseif($id=="3"){
				        echo strtoupper("Office to Office");
				    }elseif($id=="4"){
				        echo strtoupper("Other");
				    }elseif ($id=="5") {
				        echo strtoupper("Mandiri");
				    }elseif ($id=="6") {
				        echo strtoupper("Telkomsel");
				    }
				}
	            ?>
	            <div id="Table-div">
	            	<?php if($this->session->userdata('level')==4 || $this->session->userdata('level')>5){  
                                if($detail_msisdn->status=='valid' && ($this->session->userdata('level')==7 || $this->session->userdata('level')==4)){ ?>
                              <a class="mybtn btn-success btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Aktivasi" href="<?php echo site_url('Admin/bast_aktivasi/'.$detail_msisdn->no_bast.'/'.$detail_msisdn->psb_id) ?>">Aktivasi</a>
                              <?php }else{
                              		if($detail_msisdn->status!="sukses" && $this->session->userdata('level')==6){ ?>
                              <a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                            title="Click For Validasi" href="<?php echo site_url('Admin/bast_validasi/'.$detail_msisdn->no_bast.'/'.$detail_msisdn->psb_id) ?>">Validasi</a> 
                        <?php 	} } }
                             ?>
	                <table class="table table-bordered hoverTable">
	                    <tr>
	                    	<td>NO BAST</td>
	                    	<td>: <?php echo isset($detail_msisdn->no_bast) ? strtoupper($detail_msisdn->no_bast) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>PSB ID</td>
	                    	<td>: <?php echo isset($detail_msisdn->psb_id) ? strtoupper($detail_msisdn->psb_id) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>MSISDN</td>
	                    	<td>: <?php echo isset($detail_msisdn->msisdn) ? strtoupper($detail_msisdn->msisdn) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>NAMA PELANGGAN</td>
	                    	<td>: <?php echo isset($detail_msisdn->nama_pelanggan) ? strtoupper($detail_msisdn->nama_pelanggan) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>ALAMAT</td>
	                    	<td>: <?php echo isset($detail_msisdn->alamat) ? strtoupper($detail_msisdn->alamat) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>ALAMAT 2</td>
	                    	<td>: <?php echo isset($detail_msisdn->alamat2) ? strtoupper($detail_msisdn->alamat2) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>NO HP</td>
	                    	<td>: <?php echo isset($detail_msisdn->no_hp) ? strtoupper($detail_msisdn->no_hp) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>BRANCH</td>
	                    	<td>: <?php echo isset($detail_msisdn->nama_branch) ? strtoupper($detail_msisdn->nama_branch) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>TANGGAL MASUK</td>
	                    	<td>: <?php echo isset($detail_msisdn->tanggal_masuk) ? strtoupper(date('Y-m-d', strtotime($detail_msisdn->tanggal_masuk))) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>TANGGAL VALIDASI</td>
	                    	<td>: <?php echo isset($detail_msisdn->tanggal_validasi) ? strtoupper(date('Y-m-d', strtotime($detail_msisdn->tanggal_validasi))) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>TANGGAL AKTIF</td>
	                    	<td>: <?php echo isset($detail_msisdn->tanggal_aktif) ? strtoupper(date('Y-m-d', strtotime($detail_msisdn->tanggal_aktif))) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>SLA</td>
	                    	<td>: <?php 
	                    	if(!empty($detail_msisdn->tanggal_aktif)){
			                    $tgl_aktif = new DateTime($detail_msisdn->tanggal_aktif);
			                }else{
			                    $tgl_aktif = new DateTime($detail_msisdn->tanggal_validasi);
			                }
			                $tgl_masuk = new DateTime($detail_msisdn->tanggal_masuk);
			                $diff = $tgl_aktif->diff($tgl_masuk); 
	                    	if(isset($detail_msisdn->sla)) {
	                    		echo strtoupper($detail_msisdn->sla).' Hari';
                    		}else{
                    			echo $diff->d." Hari";
                    		} ?></td>
	                    </tr>
	                    <tr>
	                    	<td>PAKET</td>
	                    	<td>: <?php echo isset($detail_msisdn->nama_paket) ? strtoupper($detail_msisdn->nama_paket) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>DISCOUNT (RB)</td>
	                    	<td>: <?php echo isset($detail_msisdn->discount) ? discount($detail_msisdn->discount) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>PERIODE (BULAN)</td>
	                    	<td>: <?php echo isset($detail_msisdn->periode) ? periode($detail_msisdn->periode) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>BILL CYCLE</td>
	                    	<td>: <?php echo isset($detail_msisdn->bill_cycle) ? strtoupper($detail_msisdn->bill_cycle) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>FA ID</td>
	                    	<td>: <?php echo isset($detail_msisdn->fa_id) ? strtoupper($detail_msisdn->fa_id) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>ACCOUNT ID</td>
	                    	<td>: <?php echo isset($detail_msisdn->account_id) ? strtoupper($detail_msisdn->account_id) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>SALES CHANNEL</td>
	                    	<td>: <?php echo isset($detail_msisdn->sales_channel) ? strtoupper($channel[$detail_msisdn->sales_channel]) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>SUB SALES CHANNEL</td>
	                    	<td>: <?php echo isset($detail_msisdn->sub_channel) ? strtoupper($detail_msisdn->sub_channel) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>DETAIL SUB</td>
	                    	<td>: <?php echo isset($detail_msisdn->detail_sub) ? strtoupper($detail_msisdn->detail_sub) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>SALES PERSON</td>
	                    	<td>: <?php echo isset($detail_msisdn->sales_person) ? strtoupper($detail_msisdn->sales_person) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>TL</td>
	                    	<td>: <?php echo isset($detail_msisdn->nama_tl) ? strtoupper($detail_msisdn->nama_tl) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>JENIS EVENT</td>
	                    	<td>: <?php echo isset($detail_msisdn->jenis_event) ? strtoupper(jenis_event($detail_msisdn->jenis_event)) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>NAMA EVENT</td>
	                    	<td>: <?php echo isset($detail_msisdn->nama_event) ? strtoupper($detail_msisdn->nama_event) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>VALIDASI BY</td>
	                    	<td>: <?php echo isset($detail_msisdn->validator) ? strtoupper($detail_msisdn->validator) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>USERNAME</td>
	                    	<td>: <?php echo isset($detail_msisdn->aktivator) ? strtoupper($detail_msisdn->aktivator) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>STATUS</td>
	                    	<td>: <?php echo isset($detail_msisdn->status) ? strtoupper($detail_msisdn->status) : ""; ?></td>
	                    </tr>
	                    <tr>
	                    	<td>DESKRIPSI</td>
	                    	<td>: <?php echo isset($detail_msisdn->deskripsi) ? strtoupper($detail_msisdn->deskripsi) : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>TANGGAL INPUT (SYSTEM)</td>
	                    	<td>: <?php echo isset($detail_msisdn->tanggal_input) ? $detail_msisdn->tanggal_input : "";?></td>
	                    </tr>
	                    <tr>
	                    	<td>TANGGAL UPDATE (SYSTEM)</td>
	                    	<td>: <?php echo isset($detail_msisdn->tanggal_update) ? $detail_msisdn->tanggal_update : "";?></td>
	                    </tr>
	                </table>
	            </div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


