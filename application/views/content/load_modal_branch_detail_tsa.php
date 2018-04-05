
<div id="load_popup_modal_contant" class="" role="dialog">

	<div class="modal-dialog modal-lg" style="width: 90%; height: 100%;">	  
		<div class="modal-content">
			<div class="modal-header">  
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo strtoupper($title)?></h4>
			</div>
			<div class="modal-body">
				<div class="col-md-12 col-sm-12 col-lg-12">
					<table id="repeat-branch-table" class="display responsive nowrap" cellspacing="0" width="100%">
			            <thead>    
			                <th>NO</th>
			                <th>BRANCH</th>
			                <th>NAMA TSA</th>
			                <th>JUMLAH</th>
			            </thead>

			            <tbody>
			                <?php $no=1; foreach($branch_30 as $new) { ?>    
			                <tr>
			                    <td class="date"><?php echo $no++; ?></td>
			                    <td><?php echo strtoupper($new->nama_branch) ?></td>
			                    <td><?php echo strtoupper($new->sales_person); ?></td>
			                    <td><?php $jumlah = $new->jumlah; echo number_format($jumlah) ?></td>
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
    $("#repeat-branch-table").DataTable();
});
</script>