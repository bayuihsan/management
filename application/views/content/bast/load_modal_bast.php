
<div id="load_popup_modal_contant" class="" role="dialog">

	<div class="modal-dialog modal-lg" style="width: 90%;">
	<?php
	//$id1 = $_POST["id1"];
	//$id2 = $_POST["id2"];
	?>
	  
		<div class="modal-content">
			<div class="modal-header">  
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DETAIL <?php echo isset($no_bast) ? $no_bast : ""; ?></h4>
			</div>
			<div id="validation-error"></div>
			<div class="cl"></div>
			<div class="modal-body" style="overflow: auto;">
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
	                <table id="sales-table" class="display responsive nowrap" cellspacing="0" width="100%">
			            <thead>    
			                <th>NO</th>
			                <th>MSISDN</th>
			                <th>NAMA PELANGGAN</th>
			                <th>BRANCH</th>
			                <th>PAKET</th>
			                <th>TL</th>
			                <th>SALES PERSON</th>
			                <th>TANGGAL MASUK</th>
			                <th>STATUS</th>
			                <th class="single-action">ACTION</th>
			            </thead>

			            <tbody>
			                <?php $no=1;
			                    foreach($detail_bast as $new) { ?>    
			                        <tr>
			                            <td class="date"><?php echo $no++; ?></td>
			                            <td><?php echo strtoupper($new->msisdn) ?></td>
			                            <td><?php echo strtoupper($new->nama_pelanggan) ?></td>
			                            <td><?php echo strtoupper($new->nama_branch) ?></td>
			                            <td><?php echo strtoupper($new->nama_paket) ?></td>
			                            <td><?php echo strtoupper($new->TL) ?></td>
			                            <td><?php echo strtoupper($new->sales_person) ?></td>
			                            <td><?php echo isset($new->tanggal_masuk) ? date('Y-m-d', strtotime($new->tanggal_masuk)) : ''; ?></td>
			                            <td><?php echo strtoupper($new->status) ?></td>
			                            <td><a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
			                            title="Click For Edit" data-dismiss="modal" href="<?php echo site_url('Admin/bast_edit/'.$new->no_bast.'/'.$new->psb_id) ?>">Edit</a> &nbsp; 
			                            <a class="mybtn btn-danger btn-xs sales-remove-btn" data-toggle="tooltip" title="Click For Delete" href="<?php echo site_url('Admin/bast_add/remove/'.$new->psb_id) ?>">Delete</a> </td>
			                            
			                        </tr>
			                <?php } ?>
			                
			            </tbody>       

			        </table>
	            </div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $("#sales-table").DataTable();

});
</script>


