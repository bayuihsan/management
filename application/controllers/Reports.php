<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
        redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Reportmodel','Adminmodel'));
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
        if($action=='asyn'){
            $this->load->view('reports/paket',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/paket',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportPaket($tanggal,$status,$to_date);
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
                        <td><?php echo $report->nama_paket ?></td>
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
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    //View TL Report// 
    public function tl($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/tl',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/tl',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportTL($tanggal,$status,$to_date);
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
                        <td><?php echo $report->nama ?></td>
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
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

    //View Sales Person Report// 
    public function sales_person($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/sales_person',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sales_person',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status     =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportSalesPerson($tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tlm = 0;
                $ttm = 0;
                foreach ($reportData as $report) { 
                    $tm = $report->this_month;
                    $avg = $tm/$tgl;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama_sales ?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                    </tr>
                <?php 
                    $ttm = $ttm + $tm; 
                  }  
                 //Summery value
                  $tavg = $ttm/$tgl;
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
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
                $tlm = 0;
                $ttm = 0;
                foreach ($reportData as $report) { 
                    $tm = $report->this_month;
                    // $avg = $tm/$tgl;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($tm)." Hari";?></td>
                    </tr>
                <?php 
                    $ttm = $ttm + $tm; 
                  }  
                 //Summery value
                 echo "<tr><td colspan='2'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)." Hari</b></td>";
            }
        }

    }

    //View Sub Channel Report// 
    public function sub_channel($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/sub_channel',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sub_channel',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportSubChannel($tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
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
                        <td><?php echo $channel[$report->sales_channel] ?></td>
                        <td><?php echo $report->sub_channel ?></td>
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
                 echo "<tr><td colspan='4'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td></tr>"; 
            }
        }

    }

	//View Account Statement Report// 
    public function accountStatement($action='')
    {
        $data=array();
        $data['accounts']=$this->Adminmodel->getAllAccounts();
        if($action=='asyn'){
        $this->load->view('reports/accountStatement',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
		$this->load->view('reports/accountStatement',$data);
		$this->load->view('theme/include/footer');
        }else if($action=='view'){
        $account=$this->input->post('account',true); 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        $trans_type=$this->input->post('trans_type',true);
        
        $reportData=$this->Reportmodel->getAccountStatement($account,$from_date,$to_date,$trans_type);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;
        $bal=0;		
        foreach ($reportData as $report) {
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        $bal=$report->bal;
        ?>
        <tr>
        <td><?php echo $report->trans_date ?></td><td><?php echo $report->note ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->bal ?></td>
        </tr>
       

        <?php 
          }  
         //Summery value
         echo "<tr><td colspan='2'><b>Total</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$bal."</b></td></tr>"; 
        }
      }

    }

       //Date Wise Income Report
        public function datewiseIncomeReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/datewise_income_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/datewise_income_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getIncomeReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) { 
        
        $sum=$sum+$report->amount;
        ?>
        <tr>
        <td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->payer ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <td><?php echo $report->note ?></td></tr>
       

        <?php 
          } 
        echo "<tr><td colspan='5'><b>Total Income</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td>";
        echo "<td>Sub Total</td></tr>";   
        }
      }

    }


       //Day Wise Income Report
        public function daywiseIncomeReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/daywise_income_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/daywise_income_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getIncomeReport($from_date,$to_date,"group");
        if(empty($reportData)){
        echo "false";
        }else{

        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        <tr><td><?php echo $report->trans_date ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
          }  
        echo "<tr><td><b>Total Income</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td></tr>";    
        }
      }

    }

        //Date Wise Expense Report
        public function datewiseExpenseReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/datewise_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/datewise_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getExpenseReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td><td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td><td><?php echo $report->category ?></td>
        <td><?php echo $report->payee ?></td><td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <td><?php echo $report->note ?></td></tr>
       
        <?php 
          }
        echo "<tr><td colspan='5'><b>Total Expense</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td>";
        echo "<td>Sub Total</td></tr>";     
        }
      }

    }

       //Day Wise Expense Report
       public function daywiseExpenseReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/daywise_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/daywise_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getExpenseReport($from_date,$to_date,"group");
        if(empty($reportData)){
        echo "false";
        }else{
        $sum=0;    
        foreach ($reportData as $report) {     
        $sum=$sum+$report->amount;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
          }
         echo "<tr><td><b>Total Expense</b></td>";
        echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$sum."</b></td></tr>";        
        }
      }

    }

        //Transfer Report
        public function transferReport($action='')
    {

        if($action=='asyn'){
        $this->load->view('reports/transfer_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/transfer_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $reportData=$this->Reportmodel->getTransferReport($from_date,$to_date);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td><td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->ref ?></td><td><?php echo $report->payer ?></td>
        <td><?php echo $report->note ?></td><td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='5'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }

    }

	//Income Vs Expense Report
    public function incomeVsExpense($action=''){
      if($action=='asyn'){
        $this->load->view('reports/income_vs_expense_report');
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/income_vs_expense_report');
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true);  
        
        $income=$this->Reportmodel->getIncomeReport($from_date,$to_date);
        $expense=$this->Reportmodel->getExpenseReport($from_date,$to_date);

        $income_count=count($income);
        $expense_count=count($expense);

        ?>
        <div class="col-md-6 col-lg-6 col-sm-6 join-table-1">
        <table class="table table-bordered">
        <thead>
        <th>Income Date</th><th>Note</th><th class="text-right">Amount</th>
        <tbody>

        <?php 
        $income_total=0;
        foreach ($income as $report) { 
        $income_total=$income_total+$report->amount;
        ?>

        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->note ?></td>    
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>

        <?php 
        } 
        if($expense_count>$income_count){
        $dif=$expense_count-$income_count;
        for($i=0;$i<$dif; $i++){
        echo "<tr><td colspan='3'>&nbsp;</td></tr>";    
        }
        } 
        echo "<tr><td colspan='2'><b>Total Income</b></td><td class='text-right'><b>".get_current_setting('currency_code')." ".$income_total."</b></td></tr>";  
        
        ?>
        </tbody>
        </table>
        </div>

        <div class="col-md-6 col-lg-6 col-sm-6 join-table-2">
        <table class="table table-bordered">
        <thead>
        <th>Expense Date</th><th>Note</th><th class="text-right">Amount</th>
        <tbody>
        <?php    
        $expense_total=0;
        foreach ($expense as $report) { 
        $expense_total=$expense_total+$report->amount;
        ?>
		
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->note ?></td>     
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->amount ?></td>
        <?php 
        } 
   
        if($income_count>$expense_count){
        $dif=$income_count-$expense_count;
        for($i=0;$i<$dif; $i++){
        echo "<tr><td colspan='3'>&nbsp;</td></tr>";    
        }
        }
        echo "<tr><td colspan='2'><b>Total Expense</b></td><td class='text-right'><b>".get_current_setting('currency_code')." ".$expense_total."</b></td></tr>"; 
        ?>
        </tbody>
        </table>
        </div> 
        <?php
      }
    }

    //Report By Chart Of Accounts
    public function incomeCategoryReport($action=''){
        $data=array();  
        $data['accountList']=$this->Adminmodel->getAllChartOfAccounts();
        if($action=='asyn'){
        $this->load->view('reports/income_cat_report',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/income_cat_report',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $category=$this->input->post('category',true);  
        
        $reportData=$this->Reportmodel->getCategoryReport($from_date,$to_date,$category);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='5'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }


    //Report By Payer
    public function reportByPayer($action=''){
        $data=array();  
        $data['payerList']=$this->Adminmodel->getPayeryAndPayeeByType('Payer');
        if($action=='asyn'){
        $this->load->view('reports/report_by_payer',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/report_by_payer',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $payer=$this->input->post('payer',true);  
        
        $reportData=$this->Reportmodel->getPayerReport($from_date,$to_date,$payer);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td><?php echo $report->payer ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='6'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }

    //Report By Payee
    public function reportByPayee($action=''){
        $data=array();  
        $data['payeeList']=$this->Adminmodel->getPayeryAndPayeeByType('Payee');
        if($action=='asyn'){
        $this->load->view('reports/report_by_payee',$data);
        }else if($action==''){
        $this->load->view('theme/include/header');
        $this->load->view('reports/report_by_payee',$data);
        $this->load->view('theme/include/footer');
        }else if($action=='view'){ 
        $from_date=$this->input->post('from-date',true);
        $to_date=$this->input->post('to-date',true); 
        $payee=$this->input->post('payee',true);  
        
        $reportData=$this->Reportmodel->getPayeeReport($from_date,$to_date,$payee);
        if(empty($reportData)){
        echo "false";
        }else{
        $dr=0;
        $cr=0;    
        foreach ($reportData as $report) { 
        $dr=$dr+$report->dr;
        $cr=$cr+$report->cr;
        ?>
        
        <tr><td><?php echo $report->trans_date ?></td>
        <td><?php echo $report->accounts_name ?></td>
        <td><?php echo $report->category ?></td>
        <td><?php echo $report->type ?></td>
        <td><?php echo $report->note ?></td>
        <td><?php echo $report->payee ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->dr ?></td>
        <td class="text-right"><?php echo get_current_setting('currency_code')." ".$report->cr ?></td></tr>
       
        <?php 
          } 
         echo "<tr><td colspan='6'><b>Total</b>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$dr."</b></td>";
         echo "<td class='text-right'><b>".get_current_setting('currency_code')." ".$cr."</b></td></tr>";  
        }
      }
    }



}