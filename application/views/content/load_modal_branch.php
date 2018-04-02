
<div id="load_popup_modal_contant" class="" role="dialog">

	<div class="modal-dialog modal-lg" style="width: 90%; height: 100%;">
	<?php
	//$id1 = $_POST["id1"];
	//$id2 = $_POST["id2"];
	?>
	  
		<div class="modal-content">
			<div class="modal-header">  
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DETAIL BRANCH <?php echo isset($from_date) ? $from_date : '' ?> - <?php echo isset($to_date) ? $to_date : '' ?></h4>
			</div>
			<div id="validation-error"></div>
			<div class="cl"></div>
			<div class="modal-body">
				<div class="col-md-12 col-sm-12 col-lg-12">
					<div id="detail_branch"></div>
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

	    var chart;
	    chart = c3.generate({
	        bindto: '#detail_branch',
	        size: {
		        height: 350,
		    },
	        data: {
	            x: 'x',
	    //        xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
	            columns: [
	                ['x'

	                <?php for($i=1;$i<=count($line_chart_branch[0]);$i++){ 
	                 echo ",";    
	                 echo "'".$line_chart_branch[0][$i]['date']."'"; 
	                } ?>

	                ], 

	                ['Central 1 (Jakpusel)', 
	                <?php for($i=1;$i<=count($line_chart_branch[0]);$i++){ 
	                echo  $line_chart_branch[0][$i]['amount'].",";
	                } ?>
	                ],

	                ['Central 2 (Jakutim)', 
	                <?php for($i=1;$i<=count($line_chart_branch[8]);$i++){ 
	                echo  $line_chart_branch[8][$i]['amount'].",";
	                } ?>
	                ],

	                ['Bogor', 
	                <?php for($i=1;$i<=count($line_chart_branch[2]);$i++){ 
	                echo  $line_chart_branch[2][$i]['amount'].",";
	                } ?>
	                ],

	                ['Karawang', 
	                <?php for($i=1;$i<=count($line_chart_branch[3]);$i++){ 
	                echo  $line_chart_branch[3][$i]['amount'].",";
	                } ?>
	                ],

	                ['Jakarta Barat', 
	                <?php for($i=1;$i<=count($line_chart_branch[7]);$i++){ 
	                echo  $line_chart_branch[7][$i]['amount'].",";
	                } ?>
	                ],

	                ['Banten', 
	                <?php for($i=1;$i<=count($line_chart_branch[4]);$i++){ 
	                echo  $line_chart_branch[4][$i]['amount'].",";
	                } ?>
	                ],

	                ['Bandung', 
	                <?php for($i=1;$i<=count($line_chart_branch[6]);$i++){ 
	                echo  $line_chart_branch[6][$i]['amount'].",";
	                } ?>
	                ],

	                ['Soreang', 
	                <?php for($i=1;$i<=count($line_chart_branch[9]);$i++){ 
	                echo  $line_chart_branch[9][$i]['amount'].",";
	                } ?>
	                ],

	                ['Cirebon', 
	                <?php for($i=1;$i<=count($line_chart_branch[1]);$i++){ 
	                echo  $line_chart_branch[1][$i]['amount'].",";
	                } ?>
	                ],

	                ['Tasikmalaya', 
	                <?php for($i=1;$i<=count($line_chart_branch[5]);$i++){ 
	                echo  $line_chart_branch[5][$i]['amount'].",";
	                } ?>
	                ],

	            ]
	        },
	        zoom: {
		        enabled: true
		    },
	        axis: {
	            x: {
	                type: 'timeseries',
	                tick: {
	                    format: '%Y-%m-%d'
	                }
	            }
	        }
	    });
	});
</script>