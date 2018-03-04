<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
        redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Reportmodel','Adminmodel','Branchmodel'));
    }

    //View Branch Report// 
    public function branch($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/branch',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/branch',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$to_date);
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
                    } ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($lm);?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($sla)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                        <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                    </tr>
                <?php 
                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm;
                    $tsla = $tsla + $sla; 
                     
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
                 echo "<tr><td colspan='2'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    public function allbranch($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/allbranch',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/allbranch',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getTopBranch($tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tlm = 0;
                $ttm = 0;
                foreach ($reportData as $report) { 
                    $lm = $report->last_month;
                    $tm = $report->this_month;
                    $avg = $tm/$tgl;
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
                    } ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($lm);?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                    </tr>
                <?php 
                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                  }  
                 //Summery value
                    $tavg = $ttm/$tgl;
                    $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);
                    if($tmom > 0){
                        $tstyle = "style='background-color:#7CFC00'";
                    }else if($tmom < 0){
                        $tstyle = "style='background-color:#F08080'";
                    }else{
                        $tstyle = "style='background-color:#D3D3D3'";
                    }
                 echo "<tr><td colspan='2'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }
    }

    //View Paket Report// 
    public function paket($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/paket',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/paket',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$to_date);
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
                    } ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama_paket ?></td>
                        <td class="text-right"><?php echo number_format($lm);?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($sla)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                        <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                    </tr>
                <?php 
                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    
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
                 echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    //View TL Report// 
    public function tl($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/tl',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/tl',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportTL($branch_id,$tanggal,$status,$to_date);
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
                    } ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama ?></td>
                        <td class="text-right"><?php echo number_format($lm);?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($sla)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                        <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                    </tr>
                <?php 
                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    
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
                 echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    //View Sub Channel Report// 
    public function sub_channel($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/sub_channel',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sub_channel',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportSubChannel($branch_id,$tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
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
                    } ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $channel[$report->sales_channel] ?></td>
                        <td><?php echo $report->sub_channel ?></td>
                        <td class="text-right"><?php echo number_format($lm);?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($sla)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                        <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                    </tr>
                <?php 
                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    
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
                 echo "<tr><td colspan='4'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    //View Sales Person Report// 
    public function sales_person($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/sales_person',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sales_person',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status     =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportSalesPerson($branch_id,$tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tlm = 0;
                $ttm = 0;
                $tsla = 0;
                foreach ($reportData as $report) { 
                    $tm = $report->this_month;
                    $avg = $tm/$tgl;
                    $sla = $report->sla;
                    $avgsla = $sla/$tm;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama_sales ?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($sla)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                    </tr>
                <?php 
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    
                  }  
                 //Summery value
                  $tavgsla = $tsla/$ttm;
                  $tavg = $ttm/$tgl;
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
            }
        }

    }

    //View Sales Person Report// 
    public function service_level($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/service_level',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/service_level',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status     =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportServiceLevel($tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tjml = 0;
                $ttm = 0;
                $tavg = 0;
                foreach ($reportData as $report) { 
                    $jumlah = $report->jumlah;
                    $tm = $report->this_month;
                    $avg = $tm/$jumlah;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($jumlah);?></td>
                        <td class="text-right"><?php echo number_format($tm)." Hari";?></td>
                        <td class="text-right"><?php echo number_format($avg)." Hari";?></td>
                    </tr>
                <?php 
                    $tjml = $tjml + $jumlah; 
                    $ttm = $ttm + $tm; 
                    $tavg = $ttm/$tjml; 
                  }  
                 //Summery value
                 echo "<tr><td colspan='2'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tjml)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavg)." Hari</b></td>";
            }
        }

    }

    //View Status Daily Report// 
    public function status_daily($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        // $data['data_tanggal']=$this->Reportmodel->get_tanggal(); 
        if($action=='asyn'){
            $this->load->view('reports/status_daily',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/status_daily',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $bulan    =$this->input->post('bulan',true); 
            $tahun    =$this->input->post('tahun',true); 
            ?>  <table class="table table-bordered hoverTable">
                    <thead>
                        <th style="background-color: whitesmoke">STATUS</th>
                        <?php 
                        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                        for ($i=1; $i < $tanggal+1; $i++) { 
                            if($i<10){
                                $i = '0'.$i;
                            }else{
                                $i = $i;
                            }

                            ?>
                            <th style="background-color: whitesmoke"><?php echo $i?></th>
                        <?php }
                        ?>
                        <th style="background-color: whitesmoke">TOTAL</th>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td>SUKSES</td>
                            <?php $tsukses = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDailyAll('sukses', 'tanggal_aktif', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDaily($branch_id, 'sukses', 'tanggal_aktif', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_sukses->jumlah)){
                                        $jml_sukses = $q_sukses->jumlah;
                                    }else{
                                        $jml_sukses = 0;
                                    }
                                    echo $jml_sukses; 
                                 ?>
                                    
                            </td>
                            <?php $tsukses = $tsukses + $jml_sukses; 
                            } ?>
                            <td>
                                <?php echo number_format($tsukses); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>VALID</td>
                            <?php $tvalid = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDailyAll('valid', 'tanggal_validasi', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDaily($branch_id, 'valid', 'tanggal_validasi', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_valid->jumlah)){
                                    $jml_valid = $q_valid->jumlah;
                                }else{
                                    $jml_valid = 0;
                                }
                                echo $jml_valid; 
                                ?>
                                    
                            </td>
                            <?php $tvalid = $tvalid + $jml_valid; 
                            } ?>
                            <td>
                                <?php echo number_format($tvalid); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>CANCEL</td>
                            <?php $tcancel = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDailyAll('cancel', 'tanggal_validasi', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDaily($branch_id, 'cancel', 'tanggal_validasi', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_cancel->jumlah)){
                                    $jml_cancel = $q_cancel->jumlah;
                                }else{
                                    $jml_cancel = 0;
                                }
                                echo $jml_cancel; 
                                ?>
                                    
                            </td>
                            <?php $tcancel = $tcancel + $jml_cancel; 
                            } ?>
                            <td>
                                <?php echo number_format($tcancel); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>REJECT</td>
                            <?php $treject = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDailyAll('reject', 'tanggal_validasi', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDaily($branch_id, 'reject', 'tanggal_validasi', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_reject->jumlah)){
                                    $jml_reject = $q_reject->jumlah;
                                }else{
                                    $jml_reject = 0;
                                }
                                echo $jml_reject; 
                                ?>
                                    
                            </td>
                            <?php $treject = $treject + $jml_reject; 
                            } ?>
                            <td>
                                <?php echo number_format($treject); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>PENDING</td>
                            <?php $tpending = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDailyAll('pending', 'tanggal_validasi', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDaily($branch_id, 'pending', 'tanggal_validasi', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_pending->jumlah)){
                                    $jml_pending = $q_pending->jumlah;
                                }else{
                                    $jml_pending = 0;
                                }
                                echo $jml_pending; 
                                ?>
                                    
                            </td>
                            <?php $tpending = $tpending + $jml_pending; 
                            } ?>
                            <td>
                                <?php echo number_format($tpending); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>RETUR</td>
                            <?php $tretur = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDailyAll('retur', 'tanggal_validasi', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDaily($branch_id, 'retur', 'tanggal_validasi', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_retur->jumlah)){
                                    $jml_retur = $q_retur->jumlah;
                                }else{
                                    $jml_retur = 0;
                                }
                                echo $jml_retur; 
                                ?>
                                    
                            </td>
                            <?php $tretur = $tretur + $jml_retur; 
                            } ?>
                            <td>
                                <?php echo number_format($tretur); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>BLACKLIST</td>
                            <?php $tblacklist = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDailyAll('blacklist', 'tanggal_aktif', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDaily($branch_id, 'blacklist', 'tanggal_aktif', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_blacklist->jumlah)){
                                    $jml_blacklist = $q_blacklist->jumlah;
                                }else{
                                    $jml_blacklist = 0;
                                }
                                echo $jml_blacklist; 
                                ?>
                                    
                            </td>
                            <?php $tblacklist = $tblacklist + $jml_blacklist; 
                            } ?>
                            <td>
                                <?php echo number_format($tblacklist); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>BENTROK</td>
                            <?php $tbentrok = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDailyAll('bentrok', 'tanggal_aktif', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDaily($branch_id, 'bentrok', 'tanggal_aktif', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_bentrok->jumlah)){
                                    $jml_bentrok = $q_bentrok->jumlah;
                                }else{
                                    $jml_bentrok = 0;
                                }
                                echo $jml_bentrok; 
                                ?>
                                    
                            </td>
                            <?php $tbentrok = $tbentrok + $jml_bentrok; 
                            } ?>
                            <td>
                                <?php echo number_format($tbentrok); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>MASUK</td>
                            <?php $tmasuk = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                <?php 
                                
                                if($branch_id == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDailyAll('masuk', 'tanggal_masuk', $tahun, $bulan, $i);
                                                                        
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDaily($branch_id, 'masuk', 'tanggal_masuk', $tahun, $bulan, $i);
                                    
                                }
                                if(!empty($q_masuk->jumlah)){
                                    $jml_masuk = $q_masuk->jumlah;
                                }else{
                                    $jml_masuk = 0;
                                }
                                echo $jml_masuk; 
                                ?>
                                    
                            </td>
                            <?php $tmasuk = $tmasuk + $jml_masuk; 
                            } ?>
                            <td>
                                <?php echo number_format($tmasuk); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>GRAND TOTAL</td>
                            <?php $ttotal = 0; for ($i=1; $i < $tanggal+1; $i++) { 
                                
                                if($i<10){
                                    $i = '0'.$i;
                                }else{
                                    $i = $i;
                                }
                                ?>
                            <td>
                                
                            </td>
                            <?php $ttotal = $tsukses + $tvalid + $tcancel + $treject + $tpending + $tretur + $tblacklist + $tbentrok + $tmasuk; 
                            } ?>
                            <td>
                                <?php echo number_format($ttotal); ?>
                            </td>
                        </tr>
                    </tbody>
                </table><?php
        }

    }

}