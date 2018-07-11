<!--Statt Main Content-->
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;    
}
</style>
<section>
<div class="main-content">
<div class="row">
<div class="col-md-12 col-lg-12 col-sm-12 content-title"><h4>Dashboard</h4></div>

</div>
<!--End Card box-->
<div class="col-md-12 col-lg-12 col-sm-12">
    <form id="pencarian" action="<?php echo site_url('Admin/dashboard') ?>">
        <div class="col-md-3 col-lg-3 col-sm-3"> 
            <div class="form-group"> 
                <div class='input-group date' id='date'>
                    <input type="text" class="form-control" placeholder="Month to date" name="from-date" id="from-date" value="<?php echo $max_tanggal?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div> 
        </div>
        <div class="col-md-1 col-lg-1 col-sm-1"> 
            <button type="submit"  class="mybtn btn-submit"><i class="fa fa-play"></i></button>
        </div>
    </form>
</div>
<div class="preloader"><img src="<?php echo base_url() ?>theme/images/ring.gif"></div>
<?php 
$sales = $bulan_ini['sales'];
$churn = $bulan_ini['churn'];
$sales_lm = $bulan_ini['sales_lm'];
$churn_lm = $bulan_ini['churn_lm'];

$netadd = $sales-$churn; 
$netadd_lm= $sales_lm-$churn_lm; 

if($sales_lm > 0){
    $mom_sales = (($sales-$sales_lm)/$sales_lm)*100; 
}else{
    $mom_sales = 0;
}

if($churn_lm > 0){
    $mom_churn = (($churn-$churn_lm)/$churn_lm)*100; 
}else{
    $mom_churn = 0;
}

if($netadd_lm > 0){
    $mom_netadd = (($netadd-$netadd_lm)/$netadd_lm)*100; 
}else{
    $mom_netadd = 0;
}

if($mom_sales == 0){
    $icon_sales = "down";
    $warna_sales = "red";
}else{
    $mom_sales = decimalPlace($mom_sales);
    $icon_sales = "up";
    $warna_sales = "green";
}

if($mom_churn == 0){
    $icon_churn = "down";
    $warna_churn = "green";
}else{
    $mom_churn = decimalPlace($mom_churn);
    $icon_churn = "up";
    $warna_churn = "red";
}

if($mom_netadd == 0){
    $icon_netadd = "down";
    $warna_netadd = "red";
}else{
    $mom_netadd = decimalPlace($mom_netadd);
    $icon_netadd = "up";
    $warna_netadd = "green";
}

// echo "Sales ".$sales." - ".$sales_lm.", Churn - ".$churn." - ".$churn_lm.", Netadd - ".$netadd." - ".$netadd_lm;
// echo "MoM Sales ".$mom_sales.", MoM Churn ".$mom_churn.", MoM Netadd ".$mom_netadd;
?>


<div class="row">
<div class="col-md-2 col-sm-6 col-xs-12">
    <div class="box box-success box-solid" id="box_row1_simpanan">
        <div class="box-header with-border">
          <center><h3 class="box-title">Sales</h3></center>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <center><span class="info-box-number"><?php echo number_format($sales)?></span>
            <span style="color: <?php echo $warna_sales?>">MoM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-<?php echo $icon_sales ?>"></i> <?php echo $mom_sales?>%</span> </center>
        </div>
        <div class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!-- /.col -->
<div class="col-md-2 col-sm-6 col-xs-12">
    <div class="box box-warning box-solid" id="box_row1_pinjaman">
        <div class="box-header with-border">
          <center><h3 class="box-title">Churn</h3></center>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
             <center><span class="info-box-number"id="row1_pinjaman_nilai"><?php echo number_format($churn)?></span>
            <span style="color: <?php echo $warna_churn?>">MoM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-<?php echo $icon_churn ?>"></i> <?php echo $mom_churn?>%</span> </center>
        </div>
        <div class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!-- /.col -->
<div class="col-md-2 col-sm-6 col-xs-12">
    <div class="box box-info box-solid" id="box_row1_npl">
        <div class="box-header with-border">
          <center><h3 class="box-title">NetAdd</h3></center>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <center><span class="info-box-number" id="row1_npl_nilai"><?php echo number_format($netadd)?></span>
            <span style="color: <?php echo $warna_netadd?>">MoM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-<?php echo $icon_netadd ?>"></i> <?php echo $mom_netadd?>%</span> </center>
        </div>
        <div class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!--Start Daily Report Line Chart-->
<div class="col-md-12 col-sm-12 col-lg-12">
<!--Start Panel-->
<div class="panel panel-default custom-box">
    
    <!-- Default panel contents -->
    <div class="panel-heading">Daily Report (<span id="nilaidaily"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body">
        <!--<canvas id="inc_vs_exp2"></canvas>-->
        <div id="inc_vs_exp2"></div>
        <br>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End Daily Report Col-->

<!--Start Branch-->
<div class="col-md-12 col-sm-12 col-lg-12">
<!--Start Panel-->
<div class="panel panel-default medium-box"> 
    <!-- Default panel contents -->
    
    <div class="panel-heading">MoM Growth (<span id="nilaibranch"><?php echo $max_tanggal?></span>) <a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch">View Detail</a></div>
    <div class="panel-body financial-bal" style="font-size: 9px; height: 420px; font-family: tahoma; font-style: italic;">
        <b><font style="font-size: 12px; font-family: tahoma;">MoM Growth AREA 2</font></b><br><br>
        <!--Branch Table-->
        <table class="table table-bordered" >
         <tr>
            <th style="background-color: #2574A9; color: white" rowspan="2" class="text-center" >BRANCH</th>
                <th style="background-color: #2574A9; color: white" colspan="5" class="text-center"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: #2574A9; color: white" colspan="5" class="text-center"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: #2574A9; color: white" colspan="5" class="text-center">%GROWTH</th>
         </tr>
         <tr>
            <!-- last month -->
            <th style="background-color: #2574A9; color: white">TSA</th>
            <th style="background-color: #2574A9; color: white">M. AD</th>
            <th style="background-color: #2574A9; color: white">M. DEVICE</th>
            <th style="background-color: #2574A9; color: white">OTHERS</th>
            <th style="background-color: #2574A9; color: white">TOTAL</th>
            <!-- this month -->
            <th style="background-color: #2574A9; color: white">TSA</th>
            <th style="background-color: #2574A9; color: white">M. AD</th>
            <th style="background-color: #2574A9; color: white">M. DEVICE</th>
            <th style="background-color: #2574A9; color: white">OTHERS</th>
            <th style="background-color: #2574A9; color: white">TOTAL</th>
            <!-- Growth -->
            <th style="background-color: #2574A9; color: white">TSA</th>
            <th style="background-color: #2574A9; color: white">M. AD</th>
            <th style="background-color: #2574A9; color: white">M. DEVICE</th>
            <th style="background-color: #2574A9; color: white">OTHERS</th>
            <th style="background-color: #2574A9; color: white">TOTAL</th>
         </tr>
         <?php $no=1; foreach($branch_info as $binfo) { ?>   
            <tr>
                <td><b> <?php echo strtoupper($binfo->nama_branch) ?></b></td>
                <!-- last month -->
                <td class="text-right"><?php $tsa_lm     = $binfo->tsa_last_month; echo number_format($tsa_lm); ?></td>
                <td class="text-right"><?php $mad_lm     = $binfo->miad_last_month; echo number_format($mad_lm); ?></td>
                <td class="text-right"><?php $mdev_lm    = $binfo->mdevice_last_month; echo number_format($mdev_lm); ?></td>
                <td class="text-right"><?php $oth_lm     = $binfo->others_last_month; echo number_format($oth_lm); ?></td>
                <td class="text-right"><?php $total_lm   = $tsa_lm+$mad_lm+$mdev_lm+$oth_lm; echo number_format($total_lm); ?></td>
                <!-- this month -->
                <td class="text-right"><?php $tsa_tm     = $binfo->tsa_this_month; echo number_format($tsa_tm); ?></td>
                <td class="text-right"><?php $mad_tm     = $binfo->miad_this_month; echo number_format($mad_tm); ?></td>
                <td class="text-right"><?php $mdev_tm    = $binfo->mdevice_this_month; echo number_format($mdev_tm); ?></td>
                <td class="text-right"><?php $oth_tm     = $binfo->others_this_month; echo number_format($oth_tm); ?></td>
                <td class="text-right"><?php $total_tm   = $tsa_tm+$mad_tm+$mdev_tm+$oth_tm; echo number_format($total_tm); ?></td>
                <!-- Growth -->
                <?php if($tsa_lm == 0){ 
                        $tsa_mom = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $tsa_mom = (($tsa_tm-$tsa_lm)/$tsa_lm)*100; $tsa_mom = decimalPlace($tsa_mom);
                        if($tsa_mom > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($tsa_mom < 0){
                             $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><?php  echo $tsa_mom.' %'; ?></td>

                <?php if($mad_lm == 0){ 
                        $mad_mom = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $mad_mom = (($mad_tm-$mad_lm)/$mad_lm)*100; $mad_mom = decimalPlace($mad_mom);
                        if($mad_mom > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($mad_mom < 0){
                             $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?><?php  echo $mad_mom.' %'; ?></td>

                <?php if($mdev_lm == 0){ 
                        $mdev_mom = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $mdev_mom = (($mdev_tm-$mdev_lm)/$mdev_lm)*100; $mdev_mom = decimalPlace($mdev_mom);
                        if($mdev_mom > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($mdev_mom < 0){
                             $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><?php  echo $mdev_mom.' %'; ?></td>

                <?php if($oth_lm == 0){ 
                        $oth_mom = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $oth_mom = (($oth_tm-$oth_lm)/$oth_lm)*100; $oth_mom = decimalPlace($oth_mom);
                        if($oth_mom > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($oth_mom < 0){
                             $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><?php  echo $oth_mom.' %'; ?></td>

                <?php if($total_lm == 0){ 
                        $total_mom = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $total_mom = (($total_tm-$total_lm)/$total_lm)*100; $total_mom = decimalPlace($total_mom);
                        if($total_mom > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($total_mom < 0){
                             $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><?php  echo $total_mom.' %'; ?></td>
            </tr>
          <?php } ?>  

        </table>

    </div>
    <!--End Panel Body-->
    
</div>
<!--End Panel-->
</div>

<!--Start Branch-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box" style="height: 510px">
    <!-- Default panel contents -->
    
    <div class="panel-heading">Top Branch (<span id="nilaibranch"><?php echo $max_tanggal?></span>) <a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch">View Detail</a></div>
    <div class="panel-body financial-bal" style="font-size: 9px; height: 470px; font-family: tahoma;" >
        <!--Branch Table-->
        <table class="table table-bordered" >
            <th style="background-color: black; color: white">RANK</th>
            <th style="background-color: black; color: white">BRANCH</th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lyear)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: black; color: white" class="text-right">%MOM</th>
            <th style="background-color: black; color: white" class="text-right">%YOY</th>
            <th style="background-color: black; color: white" class="text-right">Flag</th>
         <?php $no=1; foreach($top_branch as $top){ ?>   
            <tr>
                <td class="text-center"><b><?php echo $no++; ?></b></td>
                <td><?php echo strtoupper($top->nama_branch) ?></td>
                <td class="text-right"><b><?php $ly = $top->last_year; echo number_format($ly); ?></b></td>
                <td class="text-right"><b><?php $lm = $top->last_month; echo number_format($lm); ?></b></td>
                <td class="text-right"><b><?php $tm = $top->amount; echo number_format($tm); ?></b></td>
                <?php if($lm == 0){ 
                        $mom = '0'; 
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
                    } ?>
                <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                <?php if($ly == 0){ 
                        $yoy = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                        if($yoy > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($yoy < 0){
                            $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><b><?php  echo $yoy.' %'; ?></b></td>
                <?php if($no == 2){
                         $src = "<img src='".base_url('image/medal.png')."' width='25' height='25'>";
                    }else{
                        $src ="";
                    } ?>
                <td><?php echo $src?></td>
                <?php } ?>
            </tr>  
        </table>
    </div>
    <!--End Panel Body-->

</div>

<!--End Panel-->
</div>
<!--End Branch Col-->
<script type="text/javascript">
    $(document).ready(function(){
        var from_date = $('#from-date').val();
        var $modal = $('#load_popup_modal_show_branch');
        $('#click_to_load_modal_popup_branch').on('click', function(){
            $modal.load('<?php echo base_url()?>Admin/load_modal/',{'tanggal': from_date,'id2':'2'},
            function(){
                $modal.modal('show');
            });

        });
    });

</script>
<div id="load_popup_modal_show_branch" class="modal fade" tabindex="-1"></div>
<!--Start Paket-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box" style="height: 510px">
    <!-- Default panel contents -->
    <div class="panel-heading">Top 10 Paket (<span id="nilaipaket"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 10px; height: 460px; font-family: tahoma;">
        <table class="table table-bordered">
            <th style="background-color: black; color: white">RANK</th>
            <th style="background-color: black; color: white">PAKET</th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lyear)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: black; color: white" class="text-right">%MOM</th>
            <th style="background-color: black; color: white" class="text-right">%YOY</th>
        <?php $no=1; foreach($top_paket as $paket){ ?>   
            <tr>
                <td class="text-center"><b><?php echo $no++; ?></b></td>
                <td><b><?php echo strtoupper($paket->nama_paket) ?></b></td>
                <td class="text-right"><b><?php $ly_paket = $paket->last_year; echo number_format($ly_paket); ?></b></td>
                <td class="text-right"><b><?php $lm_paket = $paket->last_month; echo number_format($lm_paket); ?></b></td>
                <td class="text-right"><b><?php $tm_paket = $paket->amount; echo number_format($tm_paket); ?></b></td>
                <?php if($lm_paket == 0){ 
                        $mom_paket = '0'; 
                        $style_paket = "style='background-color:#D3D3D3'";
                    }else{ 
                        $mom_paket = (($tm_paket-$lm_paket)/$lm_paket)*100; $mom_paket = decimalPlace($mom_paket);
                        if($mom_paket > 0){
                            $style_paket = "style='background-color:#7CFC00'";
                        }else if($mom_paket < 0){
                            $style_paket = "style='background-color:#F08080'";
                        }else{
                            $style_paket = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_paket?>><b><?php  echo $mom_paket.' %'; ?></b></td>
                <?php if($ly_paket == 0){ 
                        $yoy_paket = '0'; 
                        $style_paket = "style='background-color:#D3D3D3'";
                    }else{ 
                        $yoy_paket = (($tm_paket-$ly_paket)/$ly_paket)*100; $yoy_paket = decimalPlace($yoy_paket);
                        if($yoy_paket > 0){
                            $style_paket = "style='background-color:#7CFC00'";
                        }else if($yoy_paket < 0){
                            $style_paket = "style='background-color:#F08080'";
                        }else{
                            $style_paket = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_paket?>><b><?php  echo $yoy_paket.' %'; ?></b></td>
            </tr>

        <?php } ?>  
        </table>
    </div>

    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Paket Col-->


<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">TOP TSA (<span id="nilaipaket"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 10px; font-family: tahoma;">
        <table class="table table-bordered" >
            <th style="background-color: black; color: white">NO</th>
            <th style="background-color: black; color: white">BRANCH</th>
            <th style="background-color: black; color: white" class="text-left">Sales <= 30</th>
            <th style="background-color: black; color: white" class="text-left">Sales > 30</th>
        <?php $no=1; 
            $tgl = $max_tanggal;
            foreach($branch as $row){ 
                $branch_id_tsa = $row->branch_id;
                if($branch_id_tsa != 17){
                    $tsa_k30 = $this->db->query("SELECT branch_id, sales_person, COUNT(psb_id) AS jumlah
                                FROM new_psb 
                                WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND branch_id='$branch_id_tsa'
                                GROUP BY branch_id, sales_person
                                HAVING jumlah <= 30")->num_rows();
                    $tsa_l30 = $this->db->query("SELECT branch_id, sales_person, COUNT(psb_id) AS jumlah
                                FROM new_psb 
                                WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND branch_id='$branch_id_tsa'
                                GROUP BY branch_id, sales_person
                                HAVING jumlah > 30")->num_rows();
                ?> 
                <tr>
                    <td class="text-center"><b><?php echo $no++; ?></b></td>
                    <td class="text-left"><b><?php echo strtoupper($row->nama_branch) ?></b></td>
                    <td class="text-right"><b><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch_k30_<?php echo $branch_id_tsa ?>"><?php echo $tsa_k30?></a></b></td>
                    <td class="text-right"><b><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch_l30_<?php echo $branch_id_tsa ?>"><?php echo $tsa_l30?></a></b></td>
                </tr>
                <script type="text/javascript">
                    $(document).ready(function(){
                        var branch_id_tsa = "<?php echo $branch_id_tsa?>";
                        var $modal_tsa = $('#load_popup_modal_show_branch_detail_tsa');
                        $('#click_to_load_modal_popup_branch_k30_<?php echo $branch_id_tsa ?>').on('click', function(){
                            $modal_tsa.load('<?php echo base_url()?>Admin/load_modal_detail_tsa/',{'branch_id': branch_id_tsa,'tipe':'k30','tanggal':'<?php echo $max_tanggal?>'},
                            function(){
                                $modal_tsa.modal('show');
                            });

                        });
                        $('#click_to_load_modal_popup_branch_l30_<?php echo $branch_id_tsa ?>').on('click', function(){
                            $modal_tsa.load('<?php echo base_url()?>Admin/load_modal_detail_tsa/',{'branch_id': branch_id_tsa,'tipe':'l30','tanggal':'<?php echo $max_tanggal?>'},
                            function(){
                                $modal_tsa.modal('show');
                            });

                        });
                    });
                </script>
            <?php }
            } ?>
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<div id="load_popup_modal_show_branch_detail_tsa" class="modal fade" tabindex="-1"></div>

<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">TOP TEAM LEADER (<span id="nilaipaket"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 10px; font-family: tahoma;">
        <table class="table table-bordered">
            <th style="background-color: black; color: white">NO</th>
            <th style="background-color: black; color: white">BRANCH</th>
            <th style="background-color: black; color: white" class="text-left">Sales <= 200</th>
            <th style="background-color: black; color: white" class="text-left">Sales > 200 </th>
            <?php $no=1; 
            foreach($branch as $row_tl){ 
                $branch_id_tl = $row_tl->branch_id;
                if($branch_id_tl != 17){
                    $tl_k200 = $this->db->query("SELECT a.branch_id, a.tl, COUNT(psb_id) AS jumlah
                                FROM new_psb a 
                                JOIN app_users b ON a.tl = b.username 
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id_tl' AND b.level='3'
                                GROUP BY a.branch_id, a.tl
                                HAVING jumlah <= 200")->num_rows();
                    $tl_l200 = $this->db->query("SELECT a.branch_id, a.tl, COUNT(psb_id) AS jumlah
                                FROM new_psb a 
                                JOIN app_users b ON a.tl = b.username 
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id_tl' AND b.level='3'
                                GROUP BY a.branch_id, a.tl
                                HAVING jumlah > 200")->num_rows();                
                ?> 
                <tr>
                    <td class="text-center"><b><?php echo $no++; ?></b></td>
                    <td class="text-left"><b><?php echo strtoupper($row_tl->nama_branch) ?></b></td>
                    <td class="text-right"><b><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch_k200_<?php echo $branch_id_tl ?>"><?php echo $tl_k200?></a></b></td>
                    <td class="text-right"><b><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch_l200_<?php echo $branch_id_tl ?>"><?php echo $tl_l200?></a></b></td>
                </tr>
                <script type="text/javascript">
                    $(document).ready(function(){
                        var branch_id_tl = "<?php echo $branch_id_tl?>";
                        var $modal_tsa = $('#load_popup_modal_show_branch_detail_tl');
                        $('#click_to_load_modal_popup_branch_k200_<?php echo $branch_id_tl ?>').on('click', function(){
                            $modal_tsa.load('<?php echo base_url()?>Admin/load_modal_detail_tl/',{'branch_id': branch_id_tl,'tipe':'k200','tanggal':'<?php echo $max_tanggal?>'},
                            function(){
                                $modal_tsa.modal('show');
                            });

                        });
                        $('#click_to_load_modal_popup_branch_l200_<?php echo $branch_id_tl ?>').on('click', function(){
                            $modal_tsa.load('<?php echo base_url()?>Admin/load_modal_detail_tl/',{'branch_id': branch_id_tl,'tipe':'l200','tanggal':'<?php echo $max_tanggal?>'},
                            function(){
                                $modal_tsa.modal('show');
                            });

                        });
                    });
                </script>
            <?php }
            } ?>
       </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<div id="load_popup_modal_show_branch_detail_tl" class="modal fade" tabindex="-1"></div>
<!--Start Channel-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Top Channel (<span id="nilaibranch"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 10px; font-family: tahoma;">
        <!--Branch Table-->
        <?php $data_cn = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom'); ?>
        <table class="table table-bordered" >
            <th style="background-color: black; color: white">RANK</th>
            <th style="background-color: black; color: white">SUB CHANNEL</th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lyear)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: black; color: white" class="text-right">%MOM</th>
            <th style="background-color: black; color: white" class="text-right">%YOY</th>
        <?php $no=1; foreach($top_channel as $channel){ ?>   
            <tr>
                <td class="text-center"><b><?php echo $no++; ?></b></td>
                <td><b><?php echo strtoupper($data_cn[$channel->sales_channel]) ?></b></td>
                <td class="text-right"><b><?php $ly_channel = $channel->last_year; echo number_format($ly_channel); ?></b></td>
                <td class="text-right"><b><?php $lm_channel = $channel->last_month; echo number_format($lm_channel); ?></b></td>
                <td class="text-right"><b><?php $tm_channel = $channel->amount; echo number_format($tm_channel); ?></b></td>
                <?php if($lm_channel == 0){ 
                        $mom_channel = '0'; 
                        $style_channel = "style='background-color:#D3D3D3'";
                    }else{ 
                        $mom_channel = (($tm_channel-$lm_channel)/$lm_channel)*100; $mom_channel = decimalPlace($mom_channel);
                        if($mom_channel > 0){
                            $style_channel = "style='background-color:#7CFC00'";
                        }else if($mom_channel < 0){
                            $style_channel = "style='background-color:#F08080'";
                        }else{
                            $style_channel = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_channel?>><b><?php  echo $mom_channel.' %'; ?></b></td>
                <?php if($ly_channel == 0){ 
                        $yoy_channel = '0'; 
                        $style_channel = "style='background-color:#D3D3D3'";
                    }else{ 
                        $yoy_channel = (($tm_channel-$ly_channel)/$ly_channel)*100; $yoy_channel = decimalPlace($yoy_channel);
                        if($yoy_channel > 0){
                            $style_channel = "style='background-color:#7CFC00'";
                        }else if($yoy_channel < 0){
                            $style_channel = "style='background-color:#F08080'";
                        }else{
                            $style_channel = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_channel?>><b><?php  echo $yoy_channel.' %'; ?></b></td>
            </tr>

        <?php } ?>

        </table>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End channel Col-->

<!--Start TL-->
<div class="col-md-6 col-sm-6 col-lg-6">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Top 10 Team Leader (<span id="nilaipaket"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 10px; font-family: tahoma;">
        <table class="table table-bordered">
            <th style="background-color: black; color: white">RANK</th>
            <th style="background-color: black; color: white">NAMA</th>
            <th style="background-color: black; color: white">BRANCH</th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lyear)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: black; color: white" class="text-right">%MOM</th>
            <th style="background-color: black; color: white" class="text-right">%YOY</th>
        <?php $no=1; foreach($top_tl as $tl){ ?>   
            <tr>
                <td><b><?php echo $no++; ?></b></td>
                <td><b><?php echo strtoupper($tl->nama) ?></b></td>
                <td><b><?php echo strtoupper($tl->nama_branch) ?></b></td>
                <td class="text-right"><b><?php $ly_tl = $tl->last_year; echo number_format($ly_tl); ?></b></td>
                <td class="text-right"><b><?php $lm_tl = $tl->last_month; echo number_format($lm_tl); ?></b></td>
                <td class="text-right"><b><?php $tm_tl = $tl->amount; echo number_format($tm_tl); ?></b></td>
                <?php if($lm_tl == 0){ 
                        $mom_tl = '0'; 
                        $style_tl = "style='background-color:#D3D3D3'";
                    }else{ 
                        $mom_tl = (($tm_tl-$lm_tl)/$lm_tl)*100; $mom_tl = decimalPlace($mom_tl);
                        if($mom_tl > 0){
                            $style_tl = "style='background-color:#7CFC00'";
                        }else if($mom_tl < 0){
                            $style_tl = "style='background-color:#F08080'";
                        }else{
                            $style_tl = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_tl?>><b><?php  echo $mom_tl.' %'; ?></b></td>
                <?php if($ly_tl == 0){ 
                        $yoy_tl = '0'; 
                        $style_tl = "style='background-color:#D3D3D3'";
                    }else{ 
                        $yoy_tl = (($tm_tl-$ly_tl)/$ly_tl)*100; $yoy_tl = decimalPlace($yoy_tl);
                        if($yoy_tl > 0){
                            $style_tl = "style='background-color:#7CFC00'";
                        }else if($yoy_tl < 0){
                            $style_tl = "style='background-color:#F08080'";
                        }else{
                            $style_tl = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style_tl?>><b><?php  echo $yoy_tl.' %'; ?></b></td>
            </tr>

        <?php } ?>
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Paket Col-->

<!--Start Persentase Status Sales Chart-->
<div class="col-md-6 col-sm-6 col-lg-6">
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Persentase Status Sales (<span id="nilaipaket"><?php echo $max_tanggal?></span>) <span id="info_status" style="border-style: double;  float: right;">Jumlah</span></div>
    <div class="panel-body">
        
        <div id="persen_status"></div>

    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End Persentase Status Sales Chart-->

<!--Start Panel-->
<div class="col-md-6 col-sm-6 col-lg-6">
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Top Sub Sales Channel (<span id="nilaibranch"><?php echo $max_tanggal?></span>)</div>
    <div class="panel-body financial-bal" style="font-size: 11px; font-family: tahoma;">
        <!--Branch Table-->
        <table class="table table-bordered" >
            <th style="background-color: black; color: white">RANK</th>
            <th style="background-color: black; color: white">Sub Sales Channel</th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lyear)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($lmonth)))?></th>
            <th style="background-color: black; color: white" class="text-right"><?php echo strtoupper(date('M-Y',strtotime($max_tanggal)));?></th>
            <th style="background-color: black; color: white" class="text-right">%MOM</th>
            <th style="background-color: black; color: white" class="text-right">%YOY</th>
         <?php $no=1; foreach($top_subchannel as $tsc){ ?>   
            <tr>
                <td class="text-center"><b><?php echo $no++; ?></b></td>
                <td><b> <?php echo strtoupper($tsc->sub_channel) ?></b></td>
                <td class="text-right"><b><?php $ly = $top->last_year; echo number_format($ly); ?></b></td>
                <td class="text-right"><b><?php $lm = $top->last_month; echo number_format($lm); ?></b></td>
                <td class="text-right"><b><?php $tm = $top->amount; echo number_format($tm); ?></b></td>
                <?php if($lm == 0){ 
                        $mom = '0'; 
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
                    } ?>
                <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                <?php if($ly == 0){ 
                        $yoy = '0'; 
                        $style = "style='background-color:#D3D3D3'";
                    }else{ 
                        $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                        if($yoy > 0){
                            $style = "style='background-color:#7CFC00'";
                        }else if($yoy < 0){
                            $style = "style='background-color:#F08080'";
                        }else{
                            $style = "style='background-color:#D3D3D3'";
                        }
                    } ?>
                <td class="text-right" <?php echo $style?>><b><?php  echo $yoy.' %'; ?></b></td>
            </tr>

          <?php } ?>  

        </table>
    </div>
    <!--End Panel Body-->

</div>
<!--End Panel-->
</div>
<!--End channel Col-->

<!--Start login Status-->
<div class="col-md-12 col-sm-12 col-lg-12">
<!--Start Panel-->
<div class="panel panel-default medium-box">
    <!-- Default panel contents -->
    <div class="panel-heading">Last Login</div>
    <div class="panel-body financial-bal" style="font-size: 11px;" font-family: tahoma;>
        <table class="table table-bordered ">
            <th>Username</th>
            <th>Branch</th>
            <th class="text-right">Last Login</th>
            <?php foreach($last_login as $last) {?>
            <tr>
                <td><?php echo strtoupper($last->username) ?></td>
                <td><?php echo strtoupper($last->nama_branch) ?></td>
                <td class="text-right"><?php echo $last->last_login ?></td>
            </tr>

            <?php } ?>
   
        </table>
    </div>
    <!--End Panel Body-->
</div>
<!--End Panel-->
</div>
<!--End login Status Col-->

</div>
<!--End Row-->
</div>
</section>
<!--End Main-content-->
<script type="text/javascript">
$(document).ready(function() {

    $(".financial-bal").niceScroll({
        cursorwidth: "8px",cursorcolor:"#7f8c8d"
    });


    var chart;
    chart = c3.generate({
        bindto: '#inc_vs_exp2',
        data: {
            x: 'x',
    //        xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
            columns: [
                ['x'

                <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
                 echo ",";    
                 echo "'".$line_chart[0][$i]['date']."'"; 
                } ?>

                ],

                ['Aktif', 
                <?php for($i=1;$i<=count($line_chart[0]);$i++){ 
                echo  $line_chart[0][$i]['amount'].",";
                } ?>
                ],

                // ['Pending', 
                // <?php // for($i=1;$i<=count($line_chart[1]);$i++){ 
                // echo  $line_chart[1][$i]['amount'].",";
                // } ?>
                // ],

                
            ]
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


    $("#date").datepicker();
    $("#date2").datepicker();

    $('#pencarian').on('submit',function(){
        var link=$(this).attr("action");
        var tanggal = $("#from-date").val();
        $(".block-ui").css('display','block'); 
        if(tanggal!=""){
            history.pushState(null, null,link);  
            $('.asyn-div').load(link+'/view/'+tanggal,function() {
                $(".block-ui").css('display','none');  
                $("#from-date").val(tanggal);   
                $("#nilaidaily").html(tanggal);   
                $("#nilaibranch").html(tanggal);   
                $("#nilaipaket").html(tanggal);   
            });
        }else{
            swal("Alert","Please Select Date Range.", "info");      
        }

        return false;
    });


    chart = c3.generate({
        bindto: '#persen_status',
        data: {
            columns: [
                ['Sukses', <?php echo $pie_data['sukses'] ?>],
                ['Reject', <?php echo $pie_data['reject'] ?>],
                ['Retur', <?php echo $pie_data['retur'] ?>],
                ['Pending', <?php echo $pie_data['pending'] ?>],
                ['Cancel', <?php echo $pie_data['cancel'] ?>],
                ['Bentrok', <?php echo $pie_data['bentrok'] ?>],
                ['Masuk', <?php echo $pie_data['masuk'] ?>],
                ['Blacklist', <?php echo $pie_data['blacklist'] ?>],
                ['Valid', <?php echo $pie_data['valid'] ?>],
            ],
            type: 'donut',
            onclick: function(d, i) {
                alert(d['name']+' : '+d['value']);
                // console.log("onclick", d, i);
            },
            onmouseover: function(d, i) {
                document.getElementById("info_status").innerHTML = d['name']+' : '+d['value'].toLocaleString(undefined,{ minimumFractionDigits: 0 }
);
                // console.log("onmouseover", d, i);
            },
            // onmouseout: function(d, i) {
            //     console.log("onmouseout", d, i);
            // }
        },
        // color: {
        //     pattern: ['#23c6c8', '#f39c12']
        // },
        donut: {
            title: "% Status Sales"
        }
    });
   
});

</script>

<style type="text/css">
table.dataTable th {background-color:#e2ffda !important}

.left {
    text-align: left;
}

.right {
    text-align: right;
}

.center {
    text-align: center;
}

@media screen and (max-width: 600px) {
  .table_dash_mobile {
    visibility: visible;
    display: block;
  }
  .progress-text {
    padding-left: 5px !important;
  }

  .table_dash_web {
    visibility: hidden;
    display: none;
  }
}
@media screen and (min-width: 601px) {
  .table_dash_mobile {
    visibility: hidden;
    display: none;
  }

  .progress-text {
    padding-left: 50px  !important;
  }

  .table_dash_web {
    visibility: visible;
    display: block;
  }
}
.info-box{display:block;min-height:90px;background:#fff;width:100%;box-shadow:0 1px 1px rgba(0,0,0,0.1);border-radius:2px;margin-bottom:15px}.info-box small{font-size:14px}.info-box .progress{background:rgba(0,0,0,0.2);margin:5px -10px 5px -10px;height:2px}.info-box .progress,.info-box .progress .progress-bar{border-radius:0}.info-box .progress .progress-bar{background:#fff}.info-box-icon{border-top-left-radius:2px;border-top-right-radius:0;border-bottom-right-radius:0;border-bottom-left-radius:2px;display:block;float:left;height:90px;width:45px;text-align:center;font-size:30px;line-height:90px;background:rgba(0,0,0,0.2)}.info-box-icon>img{max-width:100%}.info-box-content{padding:5px 10px;margin-left:45px}.info-box-number{display:block;font-weight:bold;font-size:18px}.progress-description,.info-box-text{display:block;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.info-box-text{text-transform:uppercase}.info-box-more{display:block}

.bg-red,.bg-yellow,.bg-aqua,.bg-blue,.bg-light-blue,.bg-green,.bg-navy,.bg-teal,.bg-olive,.bg-lime,.bg-orange,.bg-fuchsia,.bg-purple,.bg-maroon,.bg-black,.bg-red-active,.bg-yellow-active,.bg-aqua-active,.bg-blue-active,.bg-light-blue-active,.bg-green-active,.bg-navy-active,.bg-teal-active,.bg-olive-active,.bg-lime-active,.bg-orange-active,.bg-fuchsia-active,.bg-purple-active,.bg-maroon-active,.bg-black-active,.callout.callout-danger,.callout.callout-warning,.callout.callout-info,.callout.callout-success,.alert-success,.alert-danger,.alert-error,.alert-warning,.alert-info,.modal-primary .modal-body,.modal-primary .modal-header,.modal-primary .modal-footer,.modal-warning .modal-body,.modal-warning .modal-header,.modal-warning .modal-footer,.modal-info .modal-body,.modal-info .modal-header,.modal-info .modal-footer,.modal-success .modal-body,.modal-success .modal-header,.modal-success .modal-footer,.modal-danger .modal-body,.modal-danger .modal-header,.modal-danger .modal-footer{color:#fff !important}

.bg-red,.callout.callout-danger,.alert-danger,.alert-error,.modal-danger .modal-body{background-color:#dd4b39 !important}.bg-yellow,.callout.callout-warning,.alert-warning,.modal-warning .modal-body{background-color:#f39c12 !important}.bg-aqua,.callout.callout-info,.alert-info,.modal-info .modal-body{background-color:#00c0ef !important}.bg-blue{background-color:#0073b7 !important}.bg-light-blue,.modal-primary .modal-body{background-color:#3c8dbc !important}.bg-green,.callout.callout-success,.alert-success,.modal-success .modal-body{background-color:#00a65a !important}.bg-navy{background-color:#001f3f !important}.bg-teal{background-color:#39cccc !important}.bg-olive{background-color:#3d9970 !important}.bg-lime{background-color:#01ff70 !important}.bg-orange{background-color:#ff851b !important}.bg-fuchsia{background-color:#f012be !important}.bg-purple{background-color:#605ca8 !important}.bg-maroon{background-color:#d81b60 !important}.bg-gray-active{color:#000;background-color:#b5bbc8 !important}.bg-black-active{background-color:#000 !important}.bg-red-active,.modal-danger .modal-header,.modal-danger .modal-footer{background-color:#d33724 !important}.bg-yellow-active,.modal-warning .modal-header,.modal-warning .modal-footer{background-color:#db8b0b !important}.bg-aqua-active,.modal-info .modal-header,.modal-info .modal-footer{background-color:#00a7d0 !important}.bg-blue-active{background-color:#005384 !important}.bg-light-blue-active,.modal-primary .modal-header,.modal-primary .modal-footer{background-color:#357ca5 !important}.bg-green-active,.modal-success .modal-header,.modal-success .modal-footer{background-color:#008d4c !important}.bg-navy-active{background-color:#001a35 !important}.bg-teal-active{background-color:#30bbbb !important}.bg-olive-active{background-color:#368763 !important}.bg-lime-active{background-color:#00e765 !important}.bg-orange-active{background-color:#ff7701 !important}.bg-fuchsia-active{background-color:#db0ead !important}.bg-purple-active{background-color:#555299 !important}.bg-maroon-active{background-color:#ca195a !important}
.progress-bar-light-blue,.progress-bar-primary{background-color:#3c8dbc}.progress-striped .progress-bar-light-blue,.progress-striped .progress-bar-primary{background-image:-webkit-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:-o-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent)}.progress-bar-green,.progress-bar-success{background-color:#00a65a}.progress-striped .progress-bar-green,.progress-striped .progress-bar-success{background-image:-webkit-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:-o-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent)}.progress-bar-aqua,.progress-bar-info{background-color:#00c0ef}.progress-striped .progress-bar-aqua,.progress-striped .progress-bar-info{background-image:-webkit-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:-o-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent)}.progress-bar-yellow,.progress-bar-warning{background-color:#f39c12}.progress-striped .progress-bar-yellow,.progress-striped .progress-bar-warning{background-image:-webkit-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:-o-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent)}.progress-bar-red,.progress-bar-danger{background-color:#dd4b39}.progress-striped .progress-bar-red,.progress-striped .progress-bar-danger{background-image:-webkit-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:-o-linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);background-image:linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent)}.small-box{border-radius:2px;position:relative;display:block;margin-bottom:20px;box-shadow:0 1px 1px rgba(0,0,0,0.1)}

.text-red{color:#dd4b39 !important}.text-yellow{color:#f39c12 !important}.text-aqua{color:#00c0ef !important}.text-blue{color:#0073b7 !important}.text-black{color:#111 !important}.text-light-blue{color:#3c8dbc !important}.text-green{color:#00a65a !important}.text-gray{color:#d2d6de !important}.text-navy{color:#001f3f !important}.text-teal{color:#39cccc !important}.text-olive{color:#3d9970 !important}.text-lime{color:#01ff70 !important}.text-orange{color:#ff851b !important}.text-fuchsia{color:#f012be !important}.text-purple{color:#605ca8 !important}.text-maroon{color:#d81b60 !important}

.progress-number{float:right}

.box{position:relative;border-radius:3px;background:#ffffff;border-top:3px solid #d2d6de;margin-bottom:20px;width:100%;box-shadow:0 1px 1px rgba(0,0,0,0.1)}.box.box-primary{border-top-color:#3c8dbc}.box.box-info{border-top-color:#00c0ef}.box.box-danger{border-top-color:#dd4b39}.box.box-warning{border-top-color:#f39c12}.box.box-success{border-top-color:#00a65a}.box.box-default{border-top-color:#d2d6de}.box.collapsed-box .box-body,.box.collapsed-box .box-footer{display:none}.box .nav-stacked>li{border-bottom:1px solid #f4f4f4;margin:0}.box .nav-stacked>li:last-of-type{border-bottom:none}.box.height-control .box-body{max-height:300px;overflow:auto}.box .border-right{border-right:1px solid #f4f4f4}.box .border-left{border-left:1px solid #f4f4f4}.box.box-solid{border-top:0}.box.box-solid>.box-header .btn.btn-default{background:transparent}.box.box-solid>.box-header .btn:hover,.box.box-solid>.box-header a:hover{background:rgba(0,0,0,0.1)}.box.box-solid.box-default{border:1px solid #d2d6de}.box.box-solid.box-default>.box-header{color:#444;background:#d2d6de;background-color:#d2d6de}.box.box-solid.box-default>.box-header a,.box.box-solid.box-default>.box-header .btn{color:#444}.box.box-solid.box-primary{border:1px solid #3c8dbc}.box.box-solid.box-primary>.box-header{color:#fff;background:#3c8dbc;background-color:#3c8dbc}.box.box-solid.box-primary>.box-header a,.box.box-solid.box-primary>.box-header .btn{color:#fff}.box.box-solid.box-info{border:1px solid #00c0ef}.box.box-solid.box-info>.box-header{color:#fff;background:#00c0ef;background-color:#00c0ef}.box.box-solid.box-info>.box-header a,.box.box-solid.box-info>.box-header .btn{color:#fff}.box.box-solid.box-danger{border:1px solid #dd4b39}.box.box-solid.box-danger>.box-header{color:#fff;background:#dd4b39;background-color:#dd4b39}.box.box-solid.box-danger>.box-header a,.box.box-solid.box-danger>.box-header .btn{color:#fff}.box.box-solid.box-warning{border:1px solid #f39c12}.box.box-solid.box-warning>.box-header{color:#fff;background:#f39c12;background-color:#f39c12}.box.box-solid.box-warning>.box-header a,.box.box-solid.box-warning>.box-header .btn{color:#fff}.box.box-solid.box-success{border:1px solid #00a65a}.box.box-solid.box-success>.box-header{color:#fff;background:#00a65a;background-color:#00a65a}.box.box-solid.box-success>.box-header a,.box.box-solid.box-success>.box-header .btn{color:#fff}.box.box-solid>.box-header>.box-tools .btn{border:0;box-shadow:none}.box.box-solid[class*='bg']>.box-header{color:#fff}.box .box-group>.box{margin-bottom:5px}.box .knob-label{text-align:center;color:#333;font-weight:100;font-size:12px;margin-bottom:0.3em}.box>.overlay,.overlay-wrapper>.overlay,.box>.loading-img,.overlay-wrapper>.loading-img{position:absolute;top:0;left:0;width:100%;height:100%}.box .overlay,.overlay-wrapper .overlay{z-index:50;background:rgba(255,255,255,0.7);border-radius:3px}.box .overlay>.fa,.overlay-wrapper .overlay>.fa{position:absolute;top:50%;left:50%;margin-left:-15px;margin-top:-15px;color:#000;font-size:30px}.box .overlay.dark,.overlay-wrapper .overlay.dark{background:rgba(0,0,0,0.5)}.box-header:before,.box-body:before,.box-footer:before,.box-header:after,.box-body:after,.box-footer:after{content:" ";display:table}.box-header:after,.box-body:after,.box-footer:after{clear:both}.box-header{color:#444;display:block;padding:10px;position:relative}.box-header.with-border{border-bottom:1px solid #f4f4f4}.collapsed-box .box-header.with-border{border-bottom:none}.box-header>.fa,.box-header>.glyphicon,.box-header>.ion,.box-header .box-title{display:inline-block;font-size:18px;margin:0;line-height:1}.box-header>.fa,.box-header>.glyphicon,.box-header>.ion{margin-right:5px}.box-header>.box-tools{position:absolute;right:10px;top:5px}.box-header>.box-tools [data-toggle="tooltip"]{position:relative}.box-header>.box-tools.pull-right .dropdown-menu{right:0;left:auto}.box-header>.box-tools .dropdown-menu>li>a{color:#444!important}.btn-box-tool{padding:5px;font-size:12px;background:transparent;color:#97a0b3}.open .btn-box-tool,.btn-box-tool:hover{color:#606c84}.btn-box-tool.btn:active{box-shadow:none}.box-body{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:3px;border-bottom-left-radius:3px;padding:10px}.no-header .box-body{border-top-right-radius:3px;border-top-left-radius:3px}.box-body>.table{margin-bottom:0}.box-body .fc{margin-top:5px}.box-body .full-width-chart{margin:-19px}.box-body.no-padding .full-width-chart{margin:-9px}.box-body .box-pane{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:0;border-bottom-left-radius:3px}.box-body .box-pane-right{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:3px;border-bottom-left-radius:0}.box-footer{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:3px;border-bottom-left-radius:3px;border-top:1px solid #f4f4f4;padding:10px;background-color:#fff}.chart-legend{margin:10px 0}@media (max-width:991px){.chart-legend>li{float:left;margin-right:10px}}.box-comments{background:#f7f7f7}.box-comments .box-comment{padding:8px 0;border-bottom:1px solid #eee}.box-comments .box-comment:before,.box-comments .box-comment:after{content:" ";display:table}.box-comments .box-comment:after{clear:both}.box-comments .box-comment:last-of-type{border-bottom:0}.box-comments .box-comment:first-of-type{padding-top:0}.box-comments .box-comment img{float:left}.box-comments .comment-text{margin-left:40px;color:#555}.box-comments .username{color:#444;display:block;font-weight:600}.box-comments .text-muted{font-weight:400;font-size:12px}

.info-box>.overlay,.overlay-wrapper>.overlay,.info-box>.loading-img,.overlay-wrapper>.loading-img{position:absolute;top:0;left:0;width:100%;height:100%}.info-box .overlay,.overlay-wrapper .overlay{z-index:50;background:rgba(255,255,255,0.7);border-radius:3px}.info-box .overlay>.fa,.overlay-wrapper .overlay>.fa{position:absolute;top:50%;left:50%;margin-left:-15px;margin-top:-15px;color:#000;font-size:30px}.info-box .overlay.dark,.overlay-wrapper .overlay.dark{background:rgba(0,0,0,0.5)}

.widget-main>.overlay,.overlay-wrapper>.overlay,.widget-main>.loading-img,.overlay-wrapper>.loading-img{position:absolute;top:0;left:0;width:100%;height:100%}.widget-main .overlay,.overlay-wrapper .overlay{z-index:50;background:rgba(255,255,255,0.7);border-radius:3px}.widget-main .overlay>.fa,.overlay-wrapper .overlay>.fa{position:absolute;top:50%;left:50%;margin-left:-15px;margin-top:-15px;color:#000;font-size:30px}.widget-main .overlay.dark,.overlay-wrapper .overlay.dark{background:rgba(0,0,0,0.5)}
</style>