<html>
<head>
	<title>Report Viewer</title>
</head>
<body>

<h1 style="text-align: center;">Paket Report</h1>
<h5 style="text-align: center;">Date From <?php echo $fromdate?> To <?php echo $todate?> </h5>

<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>

<table border="1" align="center">
<tr>
	<th>No</th>
    <th>Branch</th>
    <th>Paket</th>
    <th class="text-right" id="last_month">Last Month</th>
    <th class="text-right" id="this_month">This Month</th>
    <th class="text-right">Avg/hari</th>
    <th class="text-right">Rata2 SLA</th>
    <th class="text-right">% MOM</th>
</tr>
<?php
if(empty($reportData)){
    echo "false";
}else{
    $no=1 ;
    $tlm = 0;
    $ttm = 0;
    $tsla = 0;
    foreach ($reportData as $report) { 
        $lm = $report->last_month;
        $tm = $report->this_month;
        $avg = $tm/$tgl;
        $sla = $report->sla;
        $avgsla = $sla/$tm;
        if($lm == 0){ 
            $mom = 'Infinity'; 
            $style = "style='background-color:#D3D3D3'";
        }else{ 
            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
            if($mom > 0){
                $style = "style='background-color:#7CFC00'";
            }else if($mom < 0){
                $style = "style='background-color:#F08080'";
            }else{
                $style = "style='background-color:#D3D3D3'";
            }
        } 

        if($avgsla < 16){ ?>

        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $report->nama_branch ?></td>
            <td><?php echo $report->nama_paket ?></td>
            <td class="text-right"><?php echo number_format($lm);?></td>
            <td class="text-right"><?php echo number_format($tm);?></td>
            <td class="text-right"><?php echo round($avg);?></td>
            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
        </tr>
    <?php 
        $tlm = $tlm + $lm;
        $ttm = $ttm + $tm; 
        $tsla = $tsla + $sla; 
        
        }
    }  
     //Summery value
        $tavg = $ttm/$tgl;
        $tavgsla = $tsla/$ttm; 
        $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);
        if($tmom > 0){
            $tstyle = "style='background-color:#7CFC00'";
        }else if($tmom < 0){
            $tstyle = "style='background-color:#F08080'";
        }else{
            $tstyle = "style='background-color:#D3D3D3'";
        }
     echo "<tr><td colspan='3'><b>Total</b></td>";
     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
}
?>
</table>

</body>
</html>
