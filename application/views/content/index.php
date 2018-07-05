<!--Statt Main Content-->
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
<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-speedometer cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Monthly Sales<br>
                    <span class="card-value"><?php echo $cart_summery['current_month_psb']; ?> </span></p>
            </div>
        </div>
    </div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-speedometer cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Today Sales<br>
                    <span class="card-value"><?php echo $cart_summery['current_day_psb']; ?></span></p>
            </div>
        </div>
    </div>
</div>


<!-- <div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-social-bitcoin cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Current Revenue<br>
                    <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_day_revenue']; ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="clo-md-3 col-lg-3 col-sm-6">
    <div class="card-box">
        <div class="box-callout-green">
            <div class="leftside-cart">
                <i class="ion-social-bitcoin cart-icon"></i>
            </div>
            <div class="rightside-cart">
                <p class="card-head">Month Revenue<br>
                    <span class="card-value"><?php echo get_current_setting('currency_code')." ".$cart_summery['current_month_revenue']; ?></span></p>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
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
<div class="panel panel-default medium-box" style="height: 435px"> 
    <!-- Default panel contents -->
    
    <div class="panel-heading">Top Branch (<span id="nilaibranch"><?php echo $max_tanggal?></span>) <a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_branch">View Detail</a></div>
    <div class="panel-body financial-bal" style="font-size: 10px; height: 420px; ">
        <!--Branch Table-->
        <table class="table table-bordered" >
         <tr>
            <th style="background-color: #2574A9; color: white" rowspan="2">BRANCH</th>
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
                <td class="text-right"><b><?php $tsa_lm     = $binfo->tsa_last_month; echo number_format($tsa_lm); ?></b></td>
                <td class="text-right"><b><?php $mad_lm     = $binfo->miad_last_month; echo number_format($mad_lm); ?></b></td>
                <td class="text-right"><b><?php $mdev_lm    = $binfo->mdevice_last_month; echo number_format($mdev_lm); ?></b></td>
                <td class="text-right"><b><?php $oth_lm     = $binfo->others_last_month; echo number_format($oth_lm); ?></b></td>
                <td class="text-right"><b><?php $total_lm   = $tsa_lm+$mad_lm+$mdev_lm+$oth_lm; echo number_format($total_lm); ?></b></td>
                <!-- this month -->
                <td class="text-right"><b><?php $tsa_tm     = $binfo->tsa_this_month; echo number_format($tsa_tm); ?></b></td>
                <td class="text-right"><b><?php $mad_tm     = $binfo->miad_this_month; echo number_format($mad_tm); ?></b></td>
                <td class="text-right"><b><?php $mdev_tm    = $binfo->mdevice_this_month; echo number_format($mdev_tm); ?></b></td>
                <td class="text-right"><b><?php $oth_tm     = $binfo->others_this_month; echo number_format($oth_tm); ?></b></td>
                <td class="text-right"><b><?php $total_tm   = $tsa_tm+$mad_tm+$mdev_tm+$oth_tm; echo number_format($total_tm); ?></b></td>
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
                <td class="text-right" <?php echo $style?>><b><?php  echo $tsa_mom.' %'; ?></b></td>

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
                <td class="text-right" <?php echo $style?>><b><?php  echo $mad_mom.' %'; ?></b></td>

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
                <td class="text-right" <?php echo $style?>><b><?php  echo $mdev_mom.' %'; ?></b></td>

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
                <td class="text-right" <?php echo $style?>><b><?php  echo $oth_mom.' %'; ?></b></td>

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
                <td class="text-right" <?php echo $style?>><b><?php  echo $total_mom.' %'; ?></b></td>
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
    <div class="panel-body financial-bal" style="font-size: 9px; height: 470px">
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
                <td><b> <?php echo strtoupper($top->nama_branch) ?></b></td>
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
        Total Sales Area 2 <?php echo $cart_summery['current_month_psb'];?>
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
    <div class="panel-body financial-bal" style="font-size: 10px; height: 460px">
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
    <div class="panel-body financial-bal" style="font-size: 10px;">
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
    <div class="panel-body financial-bal" style="font-size: 11px; ">
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
    <div class="panel-body financial-bal" style="font-size: 11px;">
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
    <div class="panel-body financial-bal" style="font-size: 11px; ">
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
    <div class="panel-body financial-bal" style="font-size: 11px; ">
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
    <div class="panel-body financial-bal" style="font-size: 11px;">
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