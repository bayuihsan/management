<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
        redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Reportmodel','Adminmodel','Branchmodel'));
    }

    //View Fee Sales TSA Report// 
    public function fee_sales_tsa($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('reports/fee_sales',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/fee_sales',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getFeeSales($branch_id, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tpaketkurang = 0;
                $tpaketlebih = 0;
                $tfeepaketkurang = 0;
                $tfeepaketlebih = 0;
                $ttransport = 0;
                $ttfeepaket = 0;
                foreach ($reportData as $report) { 
                    $paketkurang = $report->jml_paketkurang;
                    if($paketkurang>0){
                        $feepaketkurang = $paketkurang*50000;
                    }else{
                        $feepaketkurang = $paketkurang*0;
                    }
                    
                    $paketlebih = $report->jml_paketlebih;
                    if($paketlebih>60){
                        $feepaketlebih = $paketlebih*65000;
                    }else if($paketlebih<=60 && $paketlebih > 30){
                        $feepaketlebih = $paketlebih*60000;
                    }else if($paketlebih<=30 && $paketlebih > 0){
                        $feepaketlebih = $paketlebih*55000;
                    }else{
                        $feepaketlebih = $paketlebih*0;
                    }
                    
                    $tpaket = $paketkurang + $paketlebih;
                    if($tpaket > 60){
                        $kategori_trans = "> 60 KH";
                        $transport = 1125000;
                    }else if($tpaket <= 60 && $tpaket > 30){
                        $kategori_trans = "31 KH - 60 KH";
                        $transport = 900000;
                    }else if($tpaket <= 30 && $tpaket > 19){
                        $kategori_trans = "20 KH - 30 KH";
                        $transport = 450000;
                    }else{
                        $kategori_trans = "< 20 KH";
                        $transport = 0;
                    }
                    $tfeepaket = $feepaketkurang + $feepaketlebih + $transport;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo strtoupper($report->nama_branch) ?></td>
                        <td><?php echo strtoupper($report->nama) ?></td>
                        <td><?php echo strtoupper($report->nama_sales) ?></td>
                        <?php // Kategori Paket ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper(number_format($tpaket)) ?></b></td>
                        <?php // Kategori Transport ?>
                        <td nowrap="" style="background-color: whitesmoke"><?php echo strtoupper($kategori_trans) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($transport)) ?></td>
                        <?php // FEE ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($transport)) ?></b></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($tfeepaket)) ?></b></td>

                        <td><?php echo strtoupper($report->nama_bank) ?></td>
                        <td><?php echo strtoupper($report->no_rekening) ?></td>
                        <td><?php echo strtoupper($report->atas_nama) ?></td>
                    </tr>
                <?php 
                    $tpaketkurang = $tpaketkurang + $paketkurang;
                    $tpaketlebih = $tpaketlebih +$paketlebih;
                    $ttpaket = $tpaketlebih + $tpaketkurang;

                    $tfeepaketlebih = $tfeepaketlebih + $feepaketlebih;
                    $tfeepaketkurang = $tfeepaketkurang + $feepaketkurang;
                    $ttransport = $ttransport + $transport;
                    $ttfeepaket = $tfeepaketlebih + $tfeepaketkurang + $ttransport;
                }  
                 // //Summery value
                   
                 echo "<tr><td colspan='4'><b>GRAND TOTAL</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>".number_format($ttpaket)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td colspan='2' class='text-right'></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttransport)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttfeepaket)."</b></td>";
                 echo "<td class='text-right' nowrap='' colspan='3' ></td>";
                 echo "</tr>"; 
            }
        }

    }

    //View Fee Sales TSA Report// 
    public function fee_sales_tl($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all();
        $channel_x = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
        if($action=='asyn'){
            $this->load->view('reports/fee_sales_tl',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/fee_sales_tl',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            
            $reportData=$this->Reportmodel->getFeeSalesTL($branch_id, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tpaketkurang = 0;
                $tpaketlebih = 0;
                $tfeepaketkurang = 0;
                $tfeepaketlebih = 0;
                $ttransport = 0;
                $ttfeepaket = 0;
                foreach ($reportData as $report) { 
                    $paketkurang = $report->jml_paketkurang;
                    if($paketkurang>0){
                        $feepaketkurang = $paketkurang*10000;
                    }else{
                        $feepaketkurang = $paketkurang*0;
                    }
                    
                    $paketlebih = $report->jml_paketlebih;
                    if($paketlebih > 600){
                        $feepaketlebih = $paketlebih*13000;
                    }else if($paketlebih <= 600 && $paketlebih > 400 ){
                        $feepaketlebih = $paketlebih*12000;
                    }else if($paketlebih <= 400 && $paketlebih > 200){
                        $feepaketlebih = $paketlebih*11000;
                    }else if($paketlebih <=200 && $paketlebih >0){
                        $feepaketlebih = $paketlebih*10000;
                    }else{
                        $feepaketlebih = $paketlebih*0;
                    }
                    
                    $tpaket = $paketkurang + $paketlebih;
                    if($tpaket > 400){
                        $kategori_trans = "> 400 KH";
                        $transport = 1500000;
                    }else if($tpaket <= 400 && $tpaket > 200){
                        $kategori_trans = "201 KH - 400 KH";
                        $transport = 1200000;
                    }else if($tpaket <= 200 && $tpaket > 0){
                        $kategori_trans = "<= 200 KH";
                        $transport = 600000;
                    }else{
                        $kategori_trans = "<= 200 KH";
                        $transport = 0;
                    }
                    $tfeepaket = $feepaketkurang + $feepaketlebih + $transport;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo strtoupper($report->nama_branch) ?></td>
                        <td><?php echo strtoupper($channel_x[$report->channel]) ?></td>
                        <td><?php echo strtoupper($report->nama) ?></td>
                        <?php // Kategori Paket ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper(number_format($tpaket)) ?></b></td>
                        <?php // Kategori Transport ?>
                        <td nowrap="" style="background-color: whitesmoke"><?php echo strtoupper($kategori_trans) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($transport)) ?></td>
                        <?php // FEE ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($transport)) ?></b></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($tfeepaket)) ?></b></td>
                    </tr>
                <?php 
                    $tpaketkurang = $tpaketkurang + $paketkurang;
                    $tpaketlebih = $tpaketlebih +$paketlebih;
                    $ttpaket = $tpaketlebih + $tpaketkurang;

                    $tfeepaketlebih = $tfeepaketlebih + $feepaketlebih;
                    $tfeepaketkurang = $tfeepaketkurang + $feepaketkurang;
                    $ttransport = $ttransport + $transport;
                    $ttfeepaket = $tfeepaketlebih + $tfeepaketkurang + $ttransport;
                }  
                 // //Summery value
                   
                 echo "<tr><td colspan='4'><b>GRAND TOTAL</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>".number_format($ttpaket)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td colspan='2' class='text-right'></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttransport)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttfeepaket)."</b></td>";
                 echo "</tr>"; 
            }
        }

    }

    //View Branch Report// 
    public function branch($action='',$tanggal='',$status='',$to_date='')
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
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
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

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm;
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='2'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
            
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $branch=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($branch as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $ly);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $lm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $tm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $avgsla." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm;
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($branch)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($branch)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($branch)+1, $tly);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($branch)+1, $tlm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($branch)+1, $ttm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($branch)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($branch)+1, $tavgsla." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($branch)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($branch)+1, $tyoy."%");

            $filename = "ReportBranch-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }

    }

    public function allbranch($action='', $opsi='', $to_date='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/allbranch',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/allbranch',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $opsi    =$this->input->post('opsi',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $reportData=$this->Reportmodel->getStatusBranch($opsi, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tsukses = 0; $tvalid = 0; $tcancel = 0; $treject = 0; $tpending = 0; $tretur = 0; $tbentrok = 0; $tblacklist = 0; $tmasuk = 0; $total = 0; $ttotal = 0;
                foreach ($reportData as $report) { 
                    $sukses = $report->sukses;
                    if($opsi=="opsi2"){
                        $sukses_masuk = $report->sukses_masuk;
                    }
                    $valid = $report->valid;
                    $cancel = $report->cancel;
                    $reject = $report->reject;
                    $pending = $report->pending;
                    $retur = $report->retur;
                    $bentrok = $report->bentrok;
                    $blacklist = $report->blacklist;
                    $masuk = $report->masuk;
                    if($opsi == "opsi2"){
                        $total = $sukses_masuk+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        $total_by_sukses = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        if($total_by_sukses == 0){
                            $persensukses = "0";
                        }else{
                            $persensukses = ($sukses/$total_by_sukses)*100;
                        }
                    }else{
                        $total = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        if($total == 0){
                            $persensukses = "0";
                        }else{
                            $persensukses = ($sukses/$total)*100;
                        }
                    }
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($sukses);?></td>
                        <td class="text-right"><?php echo number_format($valid);?></td>
                        <td class="text-right"><?php echo number_format($cancel);?></td>
                        <td class="text-right"><?php echo number_format($reject);?></td>
                        <td class="text-right"><?php echo number_format($pending);?></td>
                        <td class="text-right"><?php echo number_format($retur);?></td>
                        <td class="text-right"><?php echo number_format($bentrok);?></td>
                        <td class="text-right"><?php echo number_format($blacklist);?></td>
                        <td class="text-right"><?php echo number_format($masuk);?></td>
                        <td class="text-right"><b><?php echo number_format($total);?></b></td>
                        <td class="text-right"><b><?php echo number_format($persensukses)." %";?></b></td>
                    </tr>
                <?php 
                    $tsukses = $tsukses + $sukses;
                    $tvalid = $tvalid + $valid;
                    $tcancel = $tcancel + $cancel;
                    $treject = $treject + $reject;
                    $tpending = $tpending + $pending;
                    $tretur = $tretur + $retur;
                    $tbentrok = $tbentrok + $bentrok;
                    $tblacklist = $tblacklist + $blacklist;
                    $tmasuk = $tmasuk + $masuk;
                    $ttotal = $ttotal + $total;
                }  
                if($ttotal == 0){
                    $tpersensukses = "0";
                }else{
                    $tpersensukses = ($tsukses/$ttotal)*100;
                }
                 
                 echo "<tr><td colspan='2'><b>Grand Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tsukses)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tvalid)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tcancel)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($treject)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tpending)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tretur)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tbentrok)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tblacklist)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tmasuk)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttotal)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tpersensukses)." %</b></td>";
                 echo "</tr>"; 
            }
        }else if($action=='export'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');

            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "AKTIF","VALID", "CANCEL", "REJECT", "PENDING", "RETUR", "BENTROK", "BLACKLIST", "ON PROCESS", "DATA MASUK", "% SUKSES");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $allbranch=$this->Reportmodel->getStatusBranch($opsi, $to_date);

            $excel_row = 2;

            $no=1 ;
            $tsukses = 0; $tvalid = 0; $tcancel = 0; $treject = 0; $tpending = 0; $tretur = 0; $tbentrok = 0; $tblacklist = 0; $tmasuk = 0; $total = 0; $ttotal = 0;
            foreach($allbranch as $row)
            {
                $sukses = $row->sukses;
                if($opsi=="opsi2"){
                    $sukses_masuk = $row->sukses_masuk;
                }
                $valid = $row->valid;
                $cancel = $row->cancel;
                $reject = $row->reject;
                $pending = $row->pending;
                $retur = $row->retur;
                $bentrok = $row->bentrok;
                $blacklist = $row->blacklist;
                $masuk = $row->masuk;
                if($opsi == "opsi2"){
                    $total = $sukses_masuk+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    $total_by_sukses = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    if($total_by_sukses == 0){
                        $persensukses = "0";
                    }else{
                        $persensukses = ($sukses/$total_by_sukses)*100;
                    }
                }else{
                    $total = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    if($total == 0){
                        $persensukses = "0";
                    }else{
                        $persensukses = ($sukses/$total)*100;
                    }
                }

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, number_format($sukses));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($valid));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($cancel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($reject));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, number_format($pending));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, number_format($retur));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, number_format($blacklist));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, number_format($masuk));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, number_format($total));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, number_format($persensukses)."%");
                $excel_row++;

                $tsukses = $tsukses + $sukses;
                $tvalid = $tvalid + $valid;
                $tcancel = $tcancel + $cancel;
                $treject = $treject + $reject;
                $tpending = $tpending + $pending;
                $tretur = $tretur + $retur;
                $tbentrok = $tbentrok + $bentrok;
                $tblacklist = $tblacklist + $blacklist;
                $tmasuk = $tmasuk + $masuk;
                $ttotal = $ttotal + $total;
            }

            if($ttotal == 0){
                $tpersensukses = "0";
            }else{
                $tpersensukses = ($tsukses/$ttotal)*100;
            }
             
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($allbranch)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($allbranch)+1, "Grand Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($allbranch)+1, number_format($tsukses));
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($allbranch)+1, number_format($tvalid));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($allbranch)+1, number_format($tcancel));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($allbranch)+1, number_format($treject));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($allbranch)+1, number_format($tpending));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($allbranch)+1, number_format($tretur));
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($allbranch)+1, number_format($tblacklist));
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($allbranch)+1, number_format($tmasuk));
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, count($allbranch)+1, number_format($ttotal));
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, count($allbranch)+1, number_format($tpersensukses)."%");

            $filename = "ReportAllBranch-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }
    }

    //View Paket Report// 
    public function paket($action='',$branch_id='',$tanggal='',$status='',$to_date='')
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
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{  
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
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

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $report->nama_paket ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='3'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "PAKET", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $paket = $this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($paket as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $ly);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $lm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $tm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $avgsla." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;
            
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($paket)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($paket)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($paket)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($paket)+1, $tly);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($paket)+1, $tlm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($paket)+1, $ttm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($paket)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($paket)+1, $tavgsla." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($paket)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($paket)+1, $tyoy."%");

            $filename = "ReportPaket-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }

    }

    //View TL Report// 
    public function tl($action='',$branch_id='',$tanggal='',$status='',$to_date='')
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
<<<<<<< HEAD
            $to_date    =$this->input->post('vto-date',true); 
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{   
=======
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{  
>>>>>>> 6e7b85bedeffae0136b75c5e241832ddec3f252b
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportTL($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
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

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $report->nama ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='3'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
<<<<<<< HEAD
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>";
=======
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
>>>>>>> 6e7b85bedeffae0136b75c5e241832ddec3f252b
                }
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "NAMA TL", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $TL=$this->Reportmodel->getReportTL($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($TL as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($ly));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($lm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($tm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, number_format($avgsla)." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($TL)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($TL)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($TL)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($TL)+1, number_format($tly));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($TL)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($TL)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($TL)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($TL)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($TL)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($TL)+1, $tyoy."%");

            $filename = "ReportTL-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }

    }

    //View Sub Channel Report// 
    public function sub_channel($action='',$branch_id='',$tanggal='',$status='',$to_date='')
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
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{  
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportSubChannel($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
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

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $channel[$report->sales_channel] ?></td>
                            <td><?php echo $report->sub_channel ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='4'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
        }else if($action=='export'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');

            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "CHANNEL","SUB CHANNEL", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $sub_channel=$this->Reportmodel->getReportSubChannel($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($sub_channel as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $channel[$row->sales_channel] );
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($ly));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($lm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, number_format($tm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, number_format($avgsla)." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($sub_channel)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($sub_channel)+1, number_format($tly));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($sub_channel)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($sub_channel)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($sub_channel)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($sub_channel)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($sub_channel)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, count($sub_channel)+1, $tyoy."%");

            $filename = "ReportSubChannel-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }

    }

    //View Sales Person Report// 
    public function sales_person($action='',$branch_id='',$tanggal='',$status='',$to_date='')
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
                    
                    if($avgsla < 16){ ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama_sales ?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                    </tr>
                <?php 
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    }
                }  
                 //Summery value
                  $tavgsla = $tsla/$ttm;
                  $tavg = $ttm/$tgl;
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "NAMA SALES", "JUMLAH", "AVG/HARI", "RATA2 SLA");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sales_person=$this->Reportmodel->getReportSalesPerson($branch_id,$tanggal,$status,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($sales_person as $row)
            {
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;
                $avgsla = $sla/$tm; 

                if($avgsla < 16){

                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_sales));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($tm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, round($avg));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($avgsla)." Hari");
                    $excel_row++;

                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                }
                
            }

            $tavg = $ttm/$tgl;
            $tavgsla = $tsla/$ttm; 

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($sales_person)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($sales_person)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($sales_person)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($sales_person)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($sales_person)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($sales_person)+1, number_format($tavgsla)." Hari");

            $filename = "ReportSalesPerson-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
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
                        // $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                        for ($i=1; $i <= 31; $i++) { 
                            if($i<10){
                                $i = '0'.$i;
                            }else{
                                $i = $i;
                            }

                            ?>
                            <th style="background-color: whitesmoke" class="text-right"><?php echo $i?></th>
                        <?php }
                        ?>
                        <th style="background-color: whitesmoke" class="text-right">TOTAL</th>
                    </thead>
                    <tbody>
                    <?php if($branch_id == 17){
                        $qbranch = $this->Branchmodel->get_all();
                    }else{
                        $qbranch = $this->Branchmodel->get_all_by($branch_id);
                    } 
                    foreach($qbranch as $row){
                        $branch = $row->branch_id;
                    ?> 
                        <tr>
                            <td colspan="33" style="background-color: whitesmoke;"><?php echo $row->branch_id." - ".strtoupper($row->nama_branch)?></td>
                        </tr>
                        <tr>
                            <td>SUKSES</td>
                            <?php 
                                $tsukses = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDailyAll('sukses', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDaily($branch, 'sukses', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $sukses_satu = $q_sukses->satu; echo number_format($sukses_satu);?></td>
                            <td class="text-right"><?php $sukses_dua = $q_sukses->dua; echo number_format($sukses_dua);?></td>
                            <td class="text-right"><?php $sukses_tiga = $q_sukses->tiga; echo number_format($sukses_tiga);?></td>
                            <td class="text-right"><?php $sukses_empat = $q_sukses->empat; echo number_format($sukses_empat);?></td>
                            <td class="text-right"><?php $sukses_lima = $q_sukses->lima; echo number_format($sukses_lima);?></td>
                            <td class="text-right"><?php $sukses_enam = $q_sukses->enam; echo number_format($sukses_enam);?></td>
                            <td class="text-right"><?php $sukses_tujuh = $q_sukses->tujuh; echo number_format($sukses_tujuh);?></td>
                            <td class="text-right"><?php $sukses_delapan = $q_sukses->delapan; echo number_format($sukses_delapan);?></td>
                            <td class="text-right"><?php $sukses_sembilan = $q_sukses->sembilan; echo number_format($sukses_sembilan);?></td>
                            <td class="text-right"><?php $sukses_sepuluh = $q_sukses->sepuluh; echo number_format($sukses_sepuluh);?></td>
                            <td class="text-right"><?php $sukses_sebelas = $q_sukses->sebelas; echo number_format($sukses_sebelas);?></td>
                            <td class="text-right"><?php $sukses_duabelas = $q_sukses->duabelas; echo number_format($sukses_duabelas);?></td>
                            <td class="text-right"><?php $sukses_tigabelas = $q_sukses->tigabelas; echo number_format($sukses_tigabelas);?></td>
                            <td class="text-right"><?php $sukses_empatbelas = $q_sukses->empatbelas; echo number_format($sukses_empatbelas);?></td>
                            <td class="text-right"><?php $sukses_limabelas = $q_sukses->limabelas; echo number_format($sukses_limabelas);?></td>
                            <td class="text-right"><?php $sukses_enambelas = $q_sukses->enambelas; echo number_format($sukses_enambelas);?></td>
                            <td class="text-right"><?php $sukses_tujuhbelas = $q_sukses->tujuhbelas; echo number_format($sukses_tujuhbelas);?></td>
                            <td class="text-right"><?php $sukses_delapanbelas = $q_sukses->delapanbelas; echo number_format($sukses_delapanbelas);?></td>
                            <td class="text-right"><?php $sukses_sembilanbelas = $q_sukses->sembilanbelas; echo number_format($sukses_sembilanbelas);?></td>
                            <td class="text-right"><?php $sukses_duapuluh = $q_sukses->duapuluh; echo number_format($sukses_duapuluh);?></td>
                            <td class="text-right"><?php $sukses_duasatu = $q_sukses->duasatu; echo number_format($sukses_duasatu);?></td>
                            <td class="text-right"><?php $sukses_duadua = $q_sukses->duadua; echo number_format($sukses_duadua);?></td>
                            <td class="text-right"><?php $sukses_duatiga = $q_sukses->duatiga; echo number_format($sukses_duatiga);?></td>
                            <td class="text-right"><?php $sukses_duaempat = $q_sukses->duaempat; echo number_format($sukses_duaempat);?></td>
                            <td class="text-right"><?php $sukses_dualima = $q_sukses->dualima; echo number_format($sukses_dualima);?></td>
                            <td class="text-right"><?php $sukses_duaenam = $q_sukses->duaenam; echo number_format($sukses_duaenam);?></td>
                            <td class="text-right"><?php $sukses_duatujuh = $q_sukses->duatujuh; echo number_format($sukses_duatujuh);?></td>
                            <td class="text-right"><?php $sukses_duadelapan = $q_sukses->duadelapan; echo number_format($sukses_duadelapan);?></td>
                            <td class="text-right"><?php $sukses_duasembilan = $q_sukses->duasembilan; echo number_format($sukses_duasembilan);?></td>
                            <td class="text-right"><?php $sukses_tigapuluh = $q_sukses->tigapuluh; echo number_format($sukses_tigapuluh);?></td>
                            <td class="text-right"><?php $sukses_tigasatu = $q_sukses->tigasatu; echo number_format($sukses_tigasatu);?></td>
                            <?php $tsukses = $sukses_satu + $sukses_dua + $sukses_tiga + $sukses_empat + $sukses_lima + $sukses_enam + $sukses_tujuh + $sukses_delapan + $sukses_sembilan + $sukses_sepuluh + $sukses_sebelas + $sukses_duabelas + $sukses_tigabelas + $sukses_empatbelas + $sukses_limabelas + $sukses_enambelas + $sukses_tujuhbelas + $sukses_delapanbelas + $sukses_sembilanbelas + $sukses_duapuluh + $sukses_duasatu + $sukses_duadua + $sukses_duatiga + $sukses_duaempat + $sukses_dualima + $sukses_duaenam + $sukses_duatujuh + $sukses_duadelapan + $sukses_duasembilan + $sukses_tigapuluh + $sukses_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tsukses); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>VALID</td>
                            <?php 
                                $tvalid = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDailyAll('valid', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDaily($branch, 'valid', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $valid_satu = $q_valid->satu; echo number_format($valid_satu);?></td>
                            <td class="text-right"><?php $valid_dua = $q_valid->dua; echo number_format($valid_dua);?></td>
                            <td class="text-right"><?php $valid_tiga = $q_valid->tiga; echo number_format($valid_tiga);?></td>
                            <td class="text-right"><?php $valid_empat = $q_valid->empat; echo number_format($valid_empat);?></td>
                            <td class="text-right"><?php $valid_lima = $q_valid->lima; echo number_format($valid_lima);?></td>
                            <td class="text-right"><?php $valid_enam = $q_valid->enam; echo number_format($valid_enam);?></td>
                            <td class="text-right"><?php $valid_tujuh = $q_valid->tujuh; echo number_format($valid_tujuh);?></td>
                            <td class="text-right"><?php $valid_delapan = $q_valid->delapan; echo number_format($valid_delapan);?></td>
                            <td class="text-right"><?php $valid_sembilan = $q_valid->sembilan; echo number_format($valid_sembilan);?></td>
                            <td class="text-right"><?php $valid_sepuluh = $q_valid->sepuluh; echo number_format($valid_sepuluh);?></td>
                            <td class="text-right"><?php $valid_sebelas = $q_valid->sebelas; echo number_format($valid_sebelas);?></td>
                            <td class="text-right"><?php $valid_duabelas = $q_valid->duabelas; echo number_format($valid_duabelas);?></td>
                            <td class="text-right"><?php $valid_tigabelas = $q_valid->tigabelas; echo number_format($valid_tigabelas);?></td>
                            <td class="text-right"><?php $valid_empatbelas = $q_valid->empatbelas; echo number_format($valid_empatbelas);?></td>
                            <td class="text-right"><?php $valid_limabelas = $q_valid->limabelas; echo number_format($valid_limabelas);?></td>
                            <td class="text-right"><?php $valid_enambelas = $q_valid->enambelas; echo number_format($valid_enambelas);?></td>
                            <td class="text-right"><?php $valid_tujuhbelas = $q_valid->tujuhbelas; echo number_format($valid_tujuhbelas);?></td>
                            <td class="text-right"><?php $valid_delapanbelas = $q_valid->delapanbelas; echo number_format($valid_delapanbelas);?></td>
                            <td class="text-right"><?php $valid_sembilanbelas = $q_valid->sembilanbelas; echo number_format($valid_sembilanbelas);?></td>
                            <td class="text-right"><?php $valid_duapuluh = $q_valid->duapuluh; echo number_format($valid_duapuluh);?></td>
                            <td class="text-right"><?php $valid_duasatu = $q_valid->duasatu; echo number_format($valid_duasatu);?></td>
                            <td class="text-right"><?php $valid_duadua = $q_valid->duadua; echo number_format($valid_duadua);?></td>
                            <td class="text-right"><?php $valid_duatiga = $q_valid->duatiga; echo number_format($valid_duatiga);?></td>
                            <td class="text-right"><?php $valid_duaempat = $q_valid->duaempat; echo number_format($valid_duaempat);?></td>
                            <td class="text-right"><?php $valid_dualima = $q_valid->dualima; echo number_format($valid_dualima);?></td>
                            <td class="text-right"><?php $valid_duaenam = $q_valid->duaenam; echo number_format($valid_duaenam);?></td>
                            <td class="text-right"><?php $valid_duatujuh = $q_valid->duatujuh; echo number_format($valid_duatujuh);?></td>
                            <td class="text-right"><?php $valid_duadelapan = $q_valid->duadelapan; echo number_format($valid_duadelapan);?></td>
                            <td class="text-right"><?php $valid_duasembilan = $q_valid->duasembilan; echo number_format($valid_duasembilan);?></td>
                            <td class="text-right"><?php $valid_tigapuluh = $q_valid->tigapuluh; echo number_format($valid_tigapuluh);?></td>
                            <td class="text-right"><?php $valid_tigasatu = $q_valid->tigasatu; echo number_format($valid_tigasatu);?></td>
                            <?php $tvalid = $valid_satu + $valid_dua + $valid_tiga + $valid_empat + $valid_lima + $valid_enam + $valid_tujuh + $valid_delapan + $valid_sembilan + $valid_sepuluh + $valid_sebelas + $valid_duabelas + $valid_tigabelas + $valid_empatbelas + $valid_limabelas + $valid_enambelas + $valid_tujuhbelas + $valid_delapanbelas + $valid_sembilanbelas + $valid_duapuluh + $valid_duasatu + $valid_duadua + $valid_duatiga + $valid_duaempat + $valid_dualima + $valid_duaenam + $valid_duatujuh + $valid_duadelapan + $valid_duasembilan + $valid_tigapuluh + $valid_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tvalid); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>CANCEL</td>
                            <?php 
                                $tcancel = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDailyAll('cancel', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDaily($branch, 'cancel', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $cancel_satu = $q_cancel->satu; echo number_format($cancel_satu);?></td>
                            <td class="text-right"><?php $cancel_dua = $q_cancel->dua; echo number_format($cancel_dua);?></td>
                            <td class="text-right"><?php $cancel_tiga = $q_cancel->tiga; echo number_format($cancel_tiga);?></td>
                            <td class="text-right"><?php $cancel_empat = $q_cancel->empat; echo number_format($cancel_empat);?></td>
                            <td class="text-right"><?php $cancel_lima = $q_cancel->lima; echo number_format($cancel_lima);?></td>
                            <td class="text-right"><?php $cancel_enam = $q_cancel->enam; echo number_format($cancel_enam);?></td>
                            <td class="text-right"><?php $cancel_tujuh = $q_cancel->tujuh; echo number_format($cancel_tujuh);?></td>
                            <td class="text-right"><?php $cancel_delapan = $q_cancel->delapan; echo number_format($cancel_delapan);?></td>
                            <td class="text-right"><?php $cancel_sembilan = $q_cancel->sembilan; echo number_format($cancel_sembilan);?></td>
                            <td class="text-right"><?php $cancel_sepuluh = $q_cancel->sepuluh; echo number_format($cancel_sepuluh);?></td>
                            <td class="text-right"><?php $cancel_sebelas = $q_cancel->sebelas; echo number_format($cancel_sebelas);?></td>
                            <td class="text-right"><?php $cancel_duabelas = $q_cancel->duabelas; echo number_format($cancel_duabelas);?></td>
                            <td class="text-right"><?php $cancel_tigabelas = $q_cancel->tigabelas; echo number_format($cancel_tigabelas);?></td>
                            <td class="text-right"><?php $cancel_empatbelas = $q_cancel->empatbelas; echo number_format($cancel_empatbelas);?></td>
                            <td class="text-right"><?php $cancel_limabelas = $q_cancel->limabelas; echo number_format($cancel_limabelas);?></td>
                            <td class="text-right"><?php $cancel_enambelas = $q_cancel->enambelas; echo number_format($cancel_enambelas);?></td>
                            <td class="text-right"><?php $cancel_tujuhbelas = $q_cancel->tujuhbelas; echo number_format($cancel_tujuhbelas);?></td>
                            <td class="text-right"><?php $cancel_delapanbelas = $q_cancel->delapanbelas; echo number_format($cancel_delapanbelas);?></td>
                            <td class="text-right"><?php $cancel_sembilanbelas = $q_cancel->sembilanbelas; echo number_format($cancel_sembilanbelas);?></td>
                            <td class="text-right"><?php $cancel_duapuluh = $q_cancel->duapuluh; echo number_format($cancel_duapuluh);?></td>
                            <td class="text-right"><?php $cancel_duasatu = $q_cancel->duasatu; echo number_format($cancel_duasatu);?></td>
                            <td class="text-right"><?php $cancel_duadua = $q_cancel->duadua; echo number_format($cancel_duadua);?></td>
                            <td class="text-right"><?php $cancel_duatiga = $q_cancel->duatiga; echo number_format($cancel_duatiga);?></td>
                            <td class="text-right"><?php $cancel_duaempat = $q_cancel->duaempat; echo number_format($cancel_duaempat);?></td>
                            <td class="text-right"><?php $cancel_dualima = $q_cancel->dualima; echo number_format($cancel_dualima);?></td>
                            <td class="text-right"><?php $cancel_duaenam = $q_cancel->duaenam; echo number_format($cancel_duaenam);?></td>
                            <td class="text-right"><?php $cancel_duatujuh = $q_cancel->duatujuh; echo number_format($cancel_duatujuh);?></td>
                            <td class="text-right"><?php $cancel_duadelapan = $q_cancel->duadelapan; echo number_format($cancel_duadelapan);?></td>
                            <td class="text-right"><?php $cancel_duasembilan = $q_cancel->duasembilan; echo number_format($cancel_duasembilan);?></td>
                            <td class="text-right"><?php $cancel_tigapuluh = $q_cancel->tigapuluh; echo number_format($cancel_tigapuluh);?></td>
                            <td class="text-right"><?php $cancel_tigasatu = $q_cancel->tigasatu; echo number_format($cancel_tigasatu);?></td>
                            <?php $tcancel = $cancel_satu + $cancel_dua + $cancel_tiga + $cancel_empat + $cancel_lima + $cancel_enam + $cancel_tujuh + $cancel_delapan + $cancel_sembilan + $cancel_sepuluh + $cancel_sebelas + $cancel_duabelas + $cancel_tigabelas + $cancel_empatbelas + $cancel_limabelas + $cancel_enambelas + $cancel_tujuhbelas + $cancel_delapanbelas + $cancel_sembilanbelas + $cancel_duapuluh + $cancel_duasatu + $cancel_duadua + $cancel_duatiga + $cancel_duaempat + $cancel_dualima + $cancel_duaenam + $cancel_duatujuh + $cancel_duadelapan + $cancel_duasembilan + $cancel_tigapuluh + $cancel_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tcancel); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>REJECT</td>
                            <?php 
                                $treject = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDailyAll('reject', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDaily($branch, 'reject', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $reject_satu = $q_reject->satu; echo number_format($reject_satu);?></td>
                            <td class="text-right"><?php $reject_dua = $q_reject->dua; echo number_format($reject_dua);?></td>
                            <td class="text-right"><?php $reject_tiga = $q_reject->tiga; echo number_format($reject_tiga);?></td>
                            <td class="text-right"><?php $reject_empat = $q_reject->empat; echo number_format($reject_empat);?></td>
                            <td class="text-right"><?php $reject_lima = $q_reject->lima; echo number_format($reject_lima);?></td>
                            <td class="text-right"><?php $reject_enam = $q_reject->enam; echo number_format($reject_enam);?></td>
                            <td class="text-right"><?php $reject_tujuh = $q_reject->tujuh; echo number_format($reject_tujuh);?></td>
                            <td class="text-right"><?php $reject_delapan = $q_reject->delapan; echo number_format($reject_delapan);?></td>
                            <td class="text-right"><?php $reject_sembilan = $q_reject->sembilan; echo number_format($reject_sembilan);?></td>
                            <td class="text-right"><?php $reject_sepuluh = $q_reject->sepuluh; echo number_format($reject_sepuluh);?></td>
                            <td class="text-right"><?php $reject_sebelas = $q_reject->sebelas; echo number_format($reject_sebelas);?></td>
                            <td class="text-right"><?php $reject_duabelas = $q_reject->duabelas; echo number_format($reject_duabelas);?></td>
                            <td class="text-right"><?php $reject_tigabelas = $q_reject->tigabelas; echo number_format($reject_tigabelas);?></td>
                            <td class="text-right"><?php $reject_empatbelas = $q_reject->empatbelas; echo number_format($reject_empatbelas);?></td>
                            <td class="text-right"><?php $reject_limabelas = $q_reject->limabelas; echo number_format($reject_limabelas);?></td>
                            <td class="text-right"><?php $reject_enambelas = $q_reject->enambelas; echo number_format($reject_enambelas);?></td>
                            <td class="text-right"><?php $reject_tujuhbelas = $q_reject->tujuhbelas; echo number_format($reject_tujuhbelas);?></td>
                            <td class="text-right"><?php $reject_delapanbelas = $q_reject->delapanbelas; echo number_format($reject_delapanbelas);?></td>
                            <td class="text-right"><?php $reject_sembilanbelas = $q_reject->sembilanbelas; echo number_format($reject_sembilanbelas);?></td>
                            <td class="text-right"><?php $reject_duapuluh = $q_reject->duapuluh; echo number_format($reject_duapuluh);?></td>
                            <td class="text-right"><?php $reject_duasatu = $q_reject->duasatu; echo number_format($reject_duasatu);?></td>
                            <td class="text-right"><?php $reject_duadua = $q_reject->duadua; echo number_format($reject_duadua);?></td>
                            <td class="text-right"><?php $reject_duatiga = $q_reject->duatiga; echo number_format($reject_duatiga);?></td>
                            <td class="text-right"><?php $reject_duaempat = $q_reject->duaempat; echo number_format($reject_duaempat);?></td>
                            <td class="text-right"><?php $reject_dualima = $q_reject->dualima; echo number_format($reject_dualima);?></td>
                            <td class="text-right"><?php $reject_duaenam = $q_reject->duaenam; echo number_format($reject_duaenam);?></td>
                            <td class="text-right"><?php $reject_duatujuh = $q_reject->duatujuh; echo number_format($reject_duatujuh);?></td>
                            <td class="text-right"><?php $reject_duadelapan = $q_reject->duadelapan; echo number_format($reject_duadelapan);?></td>
                            <td class="text-right"><?php $reject_duasembilan = $q_reject->duasembilan; echo number_format($reject_duasembilan);?></td>
                            <td class="text-right"><?php $reject_tigapuluh = $q_reject->tigapuluh; echo number_format($reject_tigapuluh);?></td>
                            <td class="text-right"><?php $reject_tigasatu = $q_reject->tigasatu; echo number_format($reject_tigasatu);?></td>
                            <?php $treject = $reject_satu + $reject_dua + $reject_tiga + $reject_empat + $reject_lima + $reject_enam + $reject_tujuh + $reject_delapan + $reject_sembilan + $reject_sepuluh + $reject_sebelas + $reject_duabelas + $reject_tigabelas + $reject_empatbelas + $reject_limabelas + $reject_enambelas + $reject_tujuhbelas + $reject_delapanbelas + $reject_sembilanbelas + $reject_duapuluh + $reject_duasatu + $reject_duadua + $reject_duatiga + $reject_duaempat + $reject_dualima + $reject_duaenam + $reject_duatujuh + $reject_duadelapan + $reject_duasembilan + $reject_tigapuluh + $reject_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($treject); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>PENDING</td>
                            <?php 
                                $tpending = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDailyAll('pending', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDaily($branch, 'pending', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $pending_satu = $q_pending->satu; echo number_format($pending_satu);?></td>
                            <td class="text-right"><?php $pending_dua = $q_pending->dua; echo number_format($pending_dua);?></td>
                            <td class="text-right"><?php $pending_tiga = $q_pending->tiga; echo number_format($pending_tiga);?></td>
                            <td class="text-right"><?php $pending_empat = $q_pending->empat; echo number_format($pending_empat);?></td>
                            <td class="text-right"><?php $pending_lima = $q_pending->lima; echo number_format($pending_lima);?></td>
                            <td class="text-right"><?php $pending_enam = $q_pending->enam; echo number_format($pending_enam);?></td>
                            <td class="text-right"><?php $pending_tujuh = $q_pending->tujuh; echo number_format($pending_tujuh);?></td>
                            <td class="text-right"><?php $pending_delapan = $q_pending->delapan; echo number_format($pending_delapan);?></td>
                            <td class="text-right"><?php $pending_sembilan = $q_pending->sembilan; echo number_format($pending_sembilan);?></td>
                            <td class="text-right"><?php $pending_sepuluh = $q_pending->sepuluh; echo number_format($pending_sepuluh);?></td>
                            <td class="text-right"><?php $pending_sebelas = $q_pending->sebelas; echo number_format($pending_sebelas);?></td>
                            <td class="text-right"><?php $pending_duabelas = $q_pending->duabelas; echo number_format($pending_duabelas);?></td>
                            <td class="text-right"><?php $pending_tigabelas = $q_pending->tigabelas; echo number_format($pending_tigabelas);?></td>
                            <td class="text-right"><?php $pending_empatbelas = $q_pending->empatbelas; echo number_format($pending_empatbelas);?></td>
                            <td class="text-right"><?php $pending_limabelas = $q_pending->limabelas; echo number_format($pending_limabelas);?></td>
                            <td class="text-right"><?php $pending_enambelas = $q_pending->enambelas; echo number_format($pending_enambelas);?></td>
                            <td class="text-right"><?php $pending_tujuhbelas = $q_pending->tujuhbelas; echo number_format($pending_tujuhbelas);?></td>
                            <td class="text-right"><?php $pending_delapanbelas = $q_pending->delapanbelas; echo number_format($pending_delapanbelas);?></td>
                            <td class="text-right"><?php $pending_sembilanbelas = $q_pending->sembilanbelas; echo number_format($pending_sembilanbelas);?></td>
                            <td class="text-right"><?php $pending_duapuluh = $q_pending->duapuluh; echo number_format($pending_duapuluh);?></td>
                            <td class="text-right"><?php $pending_duasatu = $q_pending->duasatu; echo number_format($pending_duasatu);?></td>
                            <td class="text-right"><?php $pending_duadua = $q_pending->duadua; echo number_format($pending_duadua);?></td>
                            <td class="text-right"><?php $pending_duatiga = $q_pending->duatiga; echo number_format($pending_duatiga);?></td>
                            <td class="text-right"><?php $pending_duaempat = $q_pending->duaempat; echo number_format($pending_duaempat);?></td>
                            <td class="text-right"><?php $pending_dualima = $q_pending->dualima; echo number_format($pending_dualima);?></td>
                            <td class="text-right"><?php $pending_duaenam = $q_pending->duaenam; echo number_format($pending_duaenam);?></td>
                            <td class="text-right"><?php $pending_duatujuh = $q_pending->duatujuh; echo number_format($pending_duatujuh);?></td>
                            <td class="text-right"><?php $pending_duadelapan = $q_pending->duadelapan; echo number_format($pending_duadelapan);?></td>
                            <td class="text-right"><?php $pending_duasembilan = $q_pending->duasembilan; echo number_format($pending_duasembilan);?></td>
                            <td class="text-right"><?php $pending_tigapuluh = $q_pending->tigapuluh; echo number_format($pending_tigapuluh);?></td>
                            <td class="text-right"><?php $pending_tigasatu = $q_pending->tigasatu; echo number_format($pending_tigasatu);?></td>
                            <?php $tpending = $pending_satu + $pending_dua + $pending_tiga + $pending_empat + $pending_lima + $pending_enam + $pending_tujuh + $pending_delapan + $pending_sembilan + $pending_sepuluh + $pending_sebelas + $pending_duabelas + $pending_tigabelas + $pending_empatbelas + $pending_limabelas + $pending_enambelas + $pending_tujuhbelas + $pending_delapanbelas + $pending_sembilanbelas + $pending_duapuluh + $pending_duasatu + $pending_duadua + $pending_duatiga + $pending_duaempat + $pending_dualima + $pending_duaenam + $pending_duatujuh + $pending_duadelapan + $pending_duasembilan + $pending_tigapuluh + $pending_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tpending); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>RETUR</td>
                            <?php 
                                $tretur = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDailyAll('retur', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDaily($branch, 'retur', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $retur_satu = $q_retur->satu; echo number_format($retur_satu);?></td>
                            <td class="text-right"><?php $retur_dua = $q_retur->dua; echo number_format($retur_dua);?></td>
                            <td class="text-right"><?php $retur_tiga = $q_retur->tiga; echo number_format($retur_tiga);?></td>
                            <td class="text-right"><?php $retur_empat = $q_retur->empat; echo number_format($retur_empat);?></td>
                            <td class="text-right"><?php $retur_lima = $q_retur->lima; echo number_format($retur_lima);?></td>
                            <td class="text-right"><?php $retur_enam = $q_retur->enam; echo number_format($retur_enam);?></td>
                            <td class="text-right"><?php $retur_tujuh = $q_retur->tujuh; echo number_format($retur_tujuh);?></td>
                            <td class="text-right"><?php $retur_delapan = $q_retur->delapan; echo number_format($retur_delapan);?></td>
                            <td class="text-right"><?php $retur_sembilan = $q_retur->sembilan; echo number_format($retur_sembilan);?></td>
                            <td class="text-right"><?php $retur_sepuluh = $q_retur->sepuluh; echo number_format($retur_sepuluh);?></td>
                            <td class="text-right"><?php $retur_sebelas = $q_retur->sebelas; echo number_format($retur_sebelas);?></td>
                            <td class="text-right"><?php $retur_duabelas = $q_retur->duabelas; echo number_format($retur_duabelas);?></td>
                            <td class="text-right"><?php $retur_tigabelas = $q_retur->tigabelas; echo number_format($retur_tigabelas);?></td>
                            <td class="text-right"><?php $retur_empatbelas = $q_retur->empatbelas; echo number_format($retur_empatbelas);?></td>
                            <td class="text-right"><?php $retur_limabelas = $q_retur->limabelas; echo number_format($retur_limabelas);?></td>
                            <td class="text-right"><?php $retur_enambelas = $q_retur->enambelas; echo number_format($retur_enambelas);?></td>
                            <td class="text-right"><?php $retur_tujuhbelas = $q_retur->tujuhbelas; echo number_format($retur_tujuhbelas);?></td>
                            <td class="text-right"><?php $retur_delapanbelas = $q_retur->delapanbelas; echo number_format($retur_delapanbelas);?></td>
                            <td class="text-right"><?php $retur_sembilanbelas = $q_retur->sembilanbelas; echo number_format($retur_sembilanbelas);?></td>
                            <td class="text-right"><?php $retur_duapuluh = $q_retur->duapuluh; echo number_format($retur_duapuluh);?></td>
                            <td class="text-right"><?php $retur_duasatu = $q_retur->duasatu; echo number_format($retur_duasatu);?></td>
                            <td class="text-right"><?php $retur_duadua = $q_retur->duadua; echo number_format($retur_duadua);?></td>
                            <td class="text-right"><?php $retur_duatiga = $q_retur->duatiga; echo number_format($retur_duatiga);?></td>
                            <td class="text-right"><?php $retur_duaempat = $q_retur->duaempat; echo number_format($retur_duaempat);?></td>
                            <td class="text-right"><?php $retur_dualima = $q_retur->dualima; echo number_format($retur_dualima);?></td>
                            <td class="text-right"><?php $retur_duaenam = $q_retur->duaenam; echo number_format($retur_duaenam);?></td>
                            <td class="text-right"><?php $retur_duatujuh = $q_retur->duatujuh; echo number_format($retur_duatujuh);?></td>
                            <td class="text-right"><?php $retur_duadelapan = $q_retur->duadelapan; echo number_format($retur_duadelapan);?></td>
                            <td class="text-right"><?php $retur_duasembilan = $q_retur->duasembilan; echo number_format($retur_duasembilan);?></td>
                            <td class="text-right"><?php $retur_tigapuluh = $q_retur->tigapuluh; echo number_format($retur_tigapuluh);?></td>
                            <td class="text-right"><?php $retur_tigasatu = $q_retur->tigasatu; echo number_format($retur_tigasatu);?></td>
                            <?php $tretur = $retur_satu + $retur_dua + $retur_tiga + $retur_empat + $retur_lima + $retur_enam + $retur_tujuh + $retur_delapan + $retur_sembilan + $retur_sepuluh + $retur_sebelas + $retur_duabelas + $retur_tigabelas + $retur_empatbelas + $retur_limabelas + $retur_enambelas + $retur_tujuhbelas + $retur_delapanbelas + $retur_sembilanbelas + $retur_duapuluh + $retur_duasatu + $retur_duadua + $retur_duatiga + $retur_duaempat + $retur_dualima + $retur_duaenam + $retur_duatujuh + $retur_duadelapan + $retur_duasembilan + $retur_tigapuluh + $retur_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tretur); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>BLACKLIST</td>
                            <?php 
                                $tblacklist = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDailyAll('blacklist', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDaily($branch, 'blacklist', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $blacklist_satu = $q_blacklist->satu; echo number_format($blacklist_satu);?></td>
                            <td class="text-right"><?php $blacklist_dua = $q_blacklist->dua; echo number_format($blacklist_dua);?></td>
                            <td class="text-right"><?php $blacklist_tiga = $q_blacklist->tiga; echo number_format($blacklist_tiga);?></td>
                            <td class="text-right"><?php $blacklist_empat = $q_blacklist->empat; echo number_format($blacklist_empat);?></td>
                            <td class="text-right"><?php $blacklist_lima = $q_blacklist->lima; echo number_format($blacklist_lima);?></td>
                            <td class="text-right"><?php $blacklist_enam = $q_blacklist->enam; echo number_format($blacklist_enam);?></td>
                            <td class="text-right"><?php $blacklist_tujuh = $q_blacklist->tujuh; echo number_format($blacklist_tujuh);?></td>
                            <td class="text-right"><?php $blacklist_delapan = $q_blacklist->delapan; echo number_format($blacklist_delapan);?></td>
                            <td class="text-right"><?php $blacklist_sembilan = $q_blacklist->sembilan; echo number_format($blacklist_sembilan);?></td>
                            <td class="text-right"><?php $blacklist_sepuluh = $q_blacklist->sepuluh; echo number_format($blacklist_sepuluh);?></td>
                            <td class="text-right"><?php $blacklist_sebelas = $q_blacklist->sebelas; echo number_format($blacklist_sebelas);?></td>
                            <td class="text-right"><?php $blacklist_duabelas = $q_blacklist->duabelas; echo number_format($blacklist_duabelas);?></td>
                            <td class="text-right"><?php $blacklist_tigabelas = $q_blacklist->tigabelas; echo number_format($blacklist_tigabelas);?></td>
                            <td class="text-right"><?php $blacklist_empatbelas = $q_blacklist->empatbelas; echo number_format($blacklist_empatbelas);?></td>
                            <td class="text-right"><?php $blacklist_limabelas = $q_blacklist->limabelas; echo number_format($blacklist_limabelas);?></td>
                            <td class="text-right"><?php $blacklist_enambelas = $q_blacklist->enambelas; echo number_format($blacklist_enambelas);?></td>
                            <td class="text-right"><?php $blacklist_tujuhbelas = $q_blacklist->tujuhbelas; echo number_format($blacklist_tujuhbelas);?></td>
                            <td class="text-right"><?php $blacklist_delapanbelas = $q_blacklist->delapanbelas; echo number_format($blacklist_delapanbelas);?></td>
                            <td class="text-right"><?php $blacklist_sembilanbelas = $q_blacklist->sembilanbelas; echo number_format($blacklist_sembilanbelas);?></td>
                            <td class="text-right"><?php $blacklist_duapuluh = $q_blacklist->duapuluh; echo number_format($blacklist_duapuluh);?></td>
                            <td class="text-right"><?php $blacklist_duasatu = $q_blacklist->duasatu; echo number_format($blacklist_duasatu);?></td>
                            <td class="text-right"><?php $blacklist_duadua = $q_blacklist->duadua; echo number_format($blacklist_duadua);?></td>
                            <td class="text-right"><?php $blacklist_duatiga = $q_blacklist->duatiga; echo number_format($blacklist_duatiga);?></td>
                            <td class="text-right"><?php $blacklist_duaempat = $q_blacklist->duaempat; echo number_format($blacklist_duaempat);?></td>
                            <td class="text-right"><?php $blacklist_dualima = $q_blacklist->dualima; echo number_format($blacklist_dualima);?></td>
                            <td class="text-right"><?php $blacklist_duaenam = $q_blacklist->duaenam; echo number_format($blacklist_duaenam);?></td>
                            <td class="text-right"><?php $blacklist_duatujuh = $q_blacklist->duatujuh; echo number_format($blacklist_duatujuh);?></td>
                            <td class="text-right"><?php $blacklist_duadelapan = $q_blacklist->duadelapan; echo number_format($blacklist_duadelapan);?></td>
                            <td class="text-right"><?php $blacklist_duasembilan = $q_blacklist->duasembilan; echo number_format($blacklist_duasembilan);?></td>
                            <td class="text-right"><?php $blacklist_tigapuluh = $q_blacklist->tigapuluh; echo number_format($blacklist_tigapuluh);?></td>
                            <td class="text-right"><?php $blacklist_tigasatu = $q_blacklist->tigasatu; echo number_format($blacklist_tigasatu);?></td>
                            <?php $tblacklist = $blacklist_satu + $blacklist_dua + $blacklist_tiga + $blacklist_empat + $blacklist_lima + $blacklist_enam + $blacklist_tujuh + $blacklist_delapan + $blacklist_sembilan + $blacklist_sepuluh + $blacklist_sebelas + $blacklist_duabelas + $blacklist_tigabelas + $blacklist_empatbelas + $blacklist_limabelas + $blacklist_enambelas + $blacklist_tujuhbelas + $blacklist_delapanbelas + $blacklist_sembilanbelas + $blacklist_duapuluh + $blacklist_duasatu + $blacklist_duadua + $blacklist_duatiga + $blacklist_duaempat + $blacklist_dualima + $blacklist_duaenam + $blacklist_duatujuh + $blacklist_duadelapan + $blacklist_duasembilan + $blacklist_tigapuluh + $blacklist_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tblacklist); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>BENTROK</td>
                            <?php 
                                $tbentrok = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDailyAll('bentrok', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDaily($branch, 'bentrok', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $bentrok_satu = $q_bentrok->satu; echo number_format($bentrok_satu);?></td>
                            <td class="text-right"><?php $bentrok_dua = $q_bentrok->dua; echo number_format($bentrok_dua);?></td>
                            <td class="text-right"><?php $bentrok_tiga = $q_bentrok->tiga; echo number_format($bentrok_tiga);?></td>
                            <td class="text-right"><?php $bentrok_empat = $q_bentrok->empat; echo number_format($bentrok_empat);?></td>
                            <td class="text-right"><?php $bentrok_lima = $q_bentrok->lima; echo number_format($bentrok_lima);?></td>
                            <td class="text-right"><?php $bentrok_enam = $q_bentrok->enam; echo number_format($bentrok_enam);?></td>
                            <td class="text-right"><?php $bentrok_tujuh = $q_bentrok->tujuh; echo number_format($bentrok_tujuh);?></td>
                            <td class="text-right"><?php $bentrok_delapan = $q_bentrok->delapan; echo number_format($bentrok_delapan);?></td>
                            <td class="text-right"><?php $bentrok_sembilan = $q_bentrok->sembilan; echo number_format($bentrok_sembilan);?></td>
                            <td class="text-right"><?php $bentrok_sepuluh = $q_bentrok->sepuluh; echo number_format($bentrok_sepuluh);?></td>
                            <td class="text-right"><?php $bentrok_sebelas = $q_bentrok->sebelas; echo number_format($bentrok_sebelas);?></td>
                            <td class="text-right"><?php $bentrok_duabelas = $q_bentrok->duabelas; echo number_format($bentrok_duabelas);?></td>
                            <td class="text-right"><?php $bentrok_tigabelas = $q_bentrok->tigabelas; echo number_format($bentrok_tigabelas);?></td>
                            <td class="text-right"><?php $bentrok_empatbelas = $q_bentrok->empatbelas; echo number_format($bentrok_empatbelas);?></td>
                            <td class="text-right"><?php $bentrok_limabelas = $q_bentrok->limabelas; echo number_format($bentrok_limabelas);?></td>
                            <td class="text-right"><?php $bentrok_enambelas = $q_bentrok->enambelas; echo number_format($bentrok_enambelas);?></td>
                            <td class="text-right"><?php $bentrok_tujuhbelas = $q_bentrok->tujuhbelas; echo number_format($bentrok_tujuhbelas);?></td>
                            <td class="text-right"><?php $bentrok_delapanbelas = $q_bentrok->delapanbelas; echo number_format($bentrok_delapanbelas);?></td>
                            <td class="text-right"><?php $bentrok_sembilanbelas = $q_bentrok->sembilanbelas; echo number_format($bentrok_sembilanbelas);?></td>
                            <td class="text-right"><?php $bentrok_duapuluh = $q_bentrok->duapuluh; echo number_format($bentrok_duapuluh);?></td>
                            <td class="text-right"><?php $bentrok_duasatu = $q_bentrok->duasatu; echo number_format($bentrok_duasatu);?></td>
                            <td class="text-right"><?php $bentrok_duadua = $q_bentrok->duadua; echo number_format($bentrok_duadua);?></td>
                            <td class="text-right"><?php $bentrok_duatiga = $q_bentrok->duatiga; echo number_format($bentrok_duatiga);?></td>
                            <td class="text-right"><?php $bentrok_duaempat = $q_bentrok->duaempat; echo number_format($bentrok_duaempat);?></td>
                            <td class="text-right"><?php $bentrok_dualima = $q_bentrok->dualima; echo number_format($bentrok_dualima);?></td>
                            <td class="text-right"><?php $bentrok_duaenam = $q_bentrok->duaenam; echo number_format($bentrok_duaenam);?></td>
                            <td class="text-right"><?php $bentrok_duatujuh = $q_bentrok->duatujuh; echo number_format($bentrok_duatujuh);?></td>
                            <td class="text-right"><?php $bentrok_duadelapan = $q_bentrok->duadelapan; echo number_format($bentrok_duadelapan);?></td>
                            <td class="text-right"><?php $bentrok_duasembilan = $q_bentrok->duasembilan; echo number_format($bentrok_duasembilan);?></td>
                            <td class="text-right"><?php $bentrok_tigapuluh = $q_bentrok->tigapuluh; echo number_format($bentrok_tigapuluh);?></td>
                            <td class="text-right"><?php $bentrok_tigasatu = $q_bentrok->tigasatu; echo number_format($bentrok_tigasatu);?></td>
                            <?php $tbentrok = $bentrok_satu + $bentrok_dua + $bentrok_tiga + $bentrok_empat + $bentrok_lima + $bentrok_enam + $bentrok_tujuh + $bentrok_delapan + $bentrok_sembilan + $bentrok_sepuluh + $bentrok_sebelas + $bentrok_duabelas + $bentrok_tigabelas + $bentrok_empatbelas + $bentrok_limabelas + $bentrok_enambelas + $bentrok_tujuhbelas + $bentrok_delapanbelas + $bentrok_sembilanbelas + $bentrok_duapuluh + $bentrok_duasatu + $bentrok_duadua + $bentrok_duatiga + $bentrok_duaempat + $bentrok_dualima + $bentrok_duaenam + $bentrok_duatujuh + $bentrok_duadelapan + $bentrok_duasembilan + $bentrok_tigapuluh + $bentrok_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tbentrok); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>MASUK</td>
                            <?php 
                                $tmasuk = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDailyAll('masuk', 'tanggal_masuk', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDaily($branch, 'masuk', 'tanggal_masuk', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $masuk_satu = $q_masuk->satu; echo number_format($masuk_satu);?></td>
                            <td class="text-right"><?php $masuk_dua = $q_masuk->dua; echo number_format($masuk_dua);?></td>
                            <td class="text-right"><?php $masuk_tiga = $q_masuk->tiga; echo number_format($masuk_tiga);?></td>
                            <td class="text-right"><?php $masuk_empat = $q_masuk->empat; echo number_format($masuk_empat);?></td>
                            <td class="text-right"><?php $masuk_lima = $q_masuk->lima; echo number_format($masuk_lima);?></td>
                            <td class="text-right"><?php $masuk_enam = $q_masuk->enam; echo number_format($masuk_enam);?></td>
                            <td class="text-right"><?php $masuk_tujuh = $q_masuk->tujuh; echo number_format($masuk_tujuh);?></td>
                            <td class="text-right"><?php $masuk_delapan = $q_masuk->delapan; echo number_format($masuk_delapan);?></td>
                            <td class="text-right"><?php $masuk_sembilan = $q_masuk->sembilan; echo number_format($masuk_sembilan);?></td>
                            <td class="text-right"><?php $masuk_sepuluh = $q_masuk->sepuluh; echo number_format($masuk_sepuluh);?></td>
                            <td class="text-right"><?php $masuk_sebelas = $q_masuk->sebelas; echo number_format($masuk_sebelas);?></td>
                            <td class="text-right"><?php $masuk_duabelas = $q_masuk->duabelas; echo number_format($masuk_duabelas);?></td>
                            <td class="text-right"><?php $masuk_tigabelas = $q_masuk->tigabelas; echo number_format($masuk_tigabelas);?></td>
                            <td class="text-right"><?php $masuk_empatbelas = $q_masuk->empatbelas; echo number_format($masuk_empatbelas);?></td>
                            <td class="text-right"><?php $masuk_limabelas = $q_masuk->limabelas; echo number_format($masuk_limabelas);?></td>
                            <td class="text-right"><?php $masuk_enambelas = $q_masuk->enambelas; echo number_format($masuk_enambelas);?></td>
                            <td class="text-right"><?php $masuk_tujuhbelas = $q_masuk->tujuhbelas; echo number_format($masuk_tujuhbelas);?></td>
                            <td class="text-right"><?php $masuk_delapanbelas = $q_masuk->delapanbelas; echo number_format($masuk_delapanbelas);?></td>
                            <td class="text-right"><?php $masuk_sembilanbelas = $q_masuk->sembilanbelas; echo number_format($masuk_sembilanbelas);?></td>
                            <td class="text-right"><?php $masuk_duapuluh = $q_masuk->duapuluh; echo number_format($masuk_duapuluh);?></td>
                            <td class="text-right"><?php $masuk_duasatu = $q_masuk->duasatu; echo number_format($masuk_duasatu);?></td>
                            <td class="text-right"><?php $masuk_duadua = $q_masuk->duadua; echo number_format($masuk_duadua);?></td>
                            <td class="text-right"><?php $masuk_duatiga = $q_masuk->duatiga; echo number_format($masuk_duatiga);?></td>
                            <td class="text-right"><?php $masuk_duaempat = $q_masuk->duaempat; echo number_format($masuk_duaempat);?></td>
                            <td class="text-right"><?php $masuk_dualima = $q_masuk->dualima; echo number_format($masuk_dualima);?></td>
                            <td class="text-right"><?php $masuk_duaenam = $q_masuk->duaenam; echo number_format($masuk_duaenam);?></td>
                            <td class="text-right"><?php $masuk_duatujuh = $q_masuk->duatujuh; echo number_format($masuk_duatujuh);?></td>
                            <td class="text-right"><?php $masuk_duadelapan = $q_masuk->duadelapan; echo number_format($masuk_duadelapan);?></td>
                            <td class="text-right"><?php $masuk_duasembilan = $q_masuk->duasembilan; echo number_format($masuk_duasembilan);?></td>
                            <td class="text-right"><?php $masuk_tigapuluh = $q_masuk->tigapuluh; echo number_format($masuk_tigapuluh);?></td>
                            <td class="text-right"><?php $masuk_tigasatu = $q_masuk->tigasatu; echo number_format($masuk_tigasatu);?></td>
                            <?php $tmasuk = $masuk_satu + $masuk_dua + $masuk_tiga + $masuk_empat + $masuk_lima + $masuk_enam + $masuk_tujuh + $masuk_delapan + $masuk_sembilan + $masuk_sepuluh + $masuk_sebelas + $masuk_duabelas + $masuk_tigabelas + $masuk_empatbelas + $masuk_limabelas + $masuk_enambelas + $masuk_tujuhbelas + $masuk_delapanbelas + $masuk_sembilanbelas + $masuk_duapuluh + $masuk_duasatu + $masuk_duadua + $masuk_duatiga + $masuk_duaempat + $masuk_dualima + $masuk_duaenam + $masuk_duatujuh + $masuk_duadelapan + $masuk_duasembilan + $masuk_tigapuluh + $masuk_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tmasuk); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: whitesmoke;">GRAND TOTAL</td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsatu = $sukses_satu + $valid_satu + $cancel_satu + $reject_satu + $pending_satu + $retur_satu + $blacklist_satu + $bentrok_satu + $masuk_satu; echo number_format($tsatu);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdua = $sukses_dua + $valid_dua + $cancel_dua + $reject_dua + $pending_dua + $retur_dua + $blacklist_dua + $bentrok_dua + $masuk_dua; echo number_format($tdua);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttiga = $sukses_tiga + $valid_tiga + $cancel_tiga + $reject_tiga + $pending_tiga + $retur_tiga + $blacklist_tiga + $bentrok_tiga + $masuk_tiga; echo number_format($ttiga);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tempat = $sukses_empat + $valid_empat + $cancel_empat + $reject_empat + $pending_empat + $retur_empat + $blacklist_empat + $bentrok_empat + $masuk_empat; echo number_format($tempat);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tlima = $sukses_lima + $valid_lima + $cancel_lima + $reject_lima + $pending_lima + $retur_lima + $blacklist_lima + $bentrok_lima + $masuk_lima; echo number_format($tlima);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tenam = $sukses_enam + $valid_enam + $cancel_enam + $reject_enam + $pending_enam + $retur_enam + $blacklist_enam + $bentrok_enam + $masuk_enam; echo number_format($tenam);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttujuh = $sukses_tujuh + $valid_tujuh + $cancel_tujuh + $reject_tujuh + $pending_tujuh + $retur_tujuh + $blacklist_tujuh + $bentrok_tujuh + $masuk_tujuh; echo number_format($ttujuh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdelapan = $sukses_delapan + $valid_delapan + $cancel_delapan + $reject_delapan + $pending_delapan + $retur_delapan + $blacklist_delapan + $bentrok_delapan + $masuk_delapan; echo number_format($tdelapan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsembilan = $sukses_sembilan + $valid_sembilan + $cancel_sembilan + $reject_sembilan + $pending_sembilan + $retur_sembilan + $blacklist_sembilan + $bentrok_sembilan + $masuk_sembilan; echo number_format($tsembilan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsepuluh = $sukses_sepuluh + $valid_sepuluh + $cancel_sepuluh + $reject_sepuluh + $pending_sepuluh + $retur_sepuluh + $blacklist_sepuluh + $bentrok_sepuluh + $masuk_sepuluh; echo number_format($tsepuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsebelas = $sukses_sebelas + $valid_sebelas + $cancel_sebelas + $reject_sebelas + $pending_sebelas + $retur_sebelas + $blacklist_sebelas + $bentrok_sebelas + $masuk_sebelas; echo number_format($tsebelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduabelas = $sukses_duabelas + $valid_duabelas + $cancel_duabelas + $reject_duabelas + $pending_duabelas + $retur_duabelas + $blacklist_duabelas + $bentrok_duabelas + $masuk_duabelas; echo number_format($tduabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigabelas = $sukses_tigabelas + $valid_tigabelas + $cancel_tigabelas + $reject_tigabelas + $pending_tigabelas + $retur_tigabelas + $blacklist_tigabelas + $bentrok_tigabelas + $masuk_tigabelas; echo number_format($ttigabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tempatbelas = $sukses_empatbelas + $valid_empatbelas + $cancel_empatbelas + $reject_empatbelas + $pending_empatbelas + $retur_empatbelas + $blacklist_empatbelas + $bentrok_empatbelas + $masuk_empatbelas; echo number_format($tempatbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tlimabelas = $sukses_limabelas + $valid_limabelas + $cancel_limabelas + $reject_limabelas + $pending_limabelas + $retur_limabelas + $blacklist_limabelas + $bentrok_limabelas + $masuk_limabelas; echo number_format($tlimabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tenambelas = $sukses_enambelas + $valid_enambelas + $cancel_enambelas + $reject_enambelas + $pending_enambelas + $retur_enambelas + $blacklist_enambelas + $bentrok_enambelas + $masuk_enambelas; echo number_format($tenambelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttujuhbelas = $sukses_tujuhbelas + $valid_tujuhbelas + $cancel_tujuhbelas + $reject_tujuhbelas + $pending_tujuhbelas + $retur_tujuhbelas + $blacklist_tujuhbelas + $bentrok_tujuhbelas + $masuk_tujuhbelas; echo number_format($ttujuhbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdelapanbelas = $sukses_delapanbelas + $valid_delapanbelas + $cancel_delapanbelas + $reject_delapanbelas + $pending_delapanbelas + $retur_delapanbelas + $blacklist_delapanbelas + $bentrok_delapanbelas + $masuk_delapanbelas; echo number_format($tdelapanbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsembilanbelas = $sukses_sembilanbelas + $valid_sembilanbelas + $cancel_sembilanbelas + $reject_sembilanbelas + $pending_sembilanbelas + $retur_sembilanbelas + $blacklist_sembilanbelas + $bentrok_sembilanbelas + $masuk_sembilanbelas; echo number_format($tsembilanbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduapuluh = $sukses_duapuluh + $valid_duapuluh + $cancel_duapuluh + $reject_duapuluh + $pending_duapuluh + $retur_duapuluh + $blacklist_duapuluh + $bentrok_duapuluh + $masuk_duapuluh; echo number_format($tduapuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduasatu = $sukses_duasatu + $valid_duasatu + $cancel_duasatu + $reject_duasatu + $pending_duasatu + $retur_duasatu + $blacklist_duasatu + $bentrok_duasatu + $masuk_duasatu; echo number_format($tduasatu);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduadua = $sukses_duadua + $valid_duadua + $cancel_duadua + $reject_duadua + $pending_duadua + $retur_duadua + $blacklist_duadua + $bentrok_duadua + $masuk_duadua; echo number_format($tduadua);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduatiga = $sukses_duatiga + $valid_duatiga + $cancel_duatiga + $reject_duatiga + $pending_duatiga + $retur_duatiga + $blacklist_duatiga + $bentrok_duatiga + $masuk_duatiga; echo number_format($tduatiga);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduaempat = $sukses_duaempat + $valid_duaempat + $cancel_duaempat + $reject_duaempat + $pending_duaempat + $retur_duaempat + $blacklist_duaempat + $bentrok_duaempat + $masuk_duaempat; echo number_format($tduaempat);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdualima = $sukses_dualima + $valid_dualima + $cancel_dualima + $reject_dualima + $pending_dualima + $retur_dualima + $blacklist_dualima + $bentrok_dualima + $masuk_dualima; echo number_format($tdualima);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduaenam = $sukses_duaenam + $valid_duaenam + $cancel_duaenam + $reject_duaenam + $pending_duaenam + $retur_duaenam + $blacklist_duaenam + $bentrok_duaenam + $masuk_duaenam; echo number_format($tduaenam);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduatujuh = $sukses_duatujuh + $valid_duatujuh + $cancel_duatujuh + $reject_duatujuh + $pending_duatujuh + $retur_duatujuh + $blacklist_duatujuh + $bentrok_duatujuh + $masuk_duatujuh; echo number_format($tduatujuh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduadelapan = $sukses_duadelapan + $valid_duadelapan + $cancel_duadelapan + $reject_duadelapan + $pending_duadelapan + $retur_duadelapan + $blacklist_duadelapan + $bentrok_duadelapan + $masuk_duadelapan; echo number_format($tduadelapan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduasembilan = $sukses_duasembilan + $valid_duasembilan + $cancel_duasembilan + $reject_duasembilan + $pending_duasembilan + $retur_duasembilan + $blacklist_duasembilan + $bentrok_duasembilan + $masuk_duasembilan; echo number_format($tduasembilan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigapuluh = $sukses_tigapuluh + $valid_tigapuluh + $cancel_tigapuluh + $reject_tigapuluh + $pending_tigapuluh + $retur_tigapuluh + $blacklist_tigapuluh + $bentrok_tigapuluh + $masuk_tigapuluh; echo number_format($ttigapuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigasatu = $sukses_tigasatu + $valid_tigasatu + $cancel_tigasatu + $reject_tigasatu + $pending_tigasatu + $retur_tigasatu + $blacklist_tigasatu + $bentrok_tigasatu + $masuk_tigasatu; echo number_format($ttigasatu);?></td>
                            <?php $ttotal = $tsatu + $tdua + $ttiga + $tempat + $tlima + $tenam + $ttujuh + $tdelapan + $tsembilan + $tsepuluh + $tsebelas + $tduabelas + $ttigabelas + $tempatbelas + $tlimabelas + $tenambelas + $ttujuhbelas + $tdelapanbelas + $tsembilanbelas + $tduapuluh + $tduasatu + $tduadua + $tduatiga + $tduaempat + $tdualima + $tduaenam + $tduatujuh + $tduadelapan + $tduasembilan + $ttigapuluh + $ttigasatu; ?>
                            <td class="text-right" style="background-color: whitesmoke;">
                                <b><?php echo number_format($ttotal); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: whitesmoke;"><b>% SUKSES</b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsatu==0){ $persensatu = 0; }else{ $persensatu = ($sukses_satu/$tsatu)*100; } echo number_format($persensatu)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdua==0){ $persendua = 0; }else{  $persendua = ($sukses_dua/$tdua)*100; } echo number_format($persendua)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttiga==0){ $pertiga = 0; }else{  $persentiga = ($sukses_tiga/$ttiga)*100; } echo number_format($persentiga)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tempat==0){ $persenempat = 0; }else{  $persenempat = ($sukses_empat/$tempat)*100; } echo number_format($persenempat)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tlima==0){ $persenlima = 0; }else{  $persenlima = ($sukses_lima/$tlima)*100; } echo number_format($persenlima)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tenam==0){ $persenenam = 0; }else{  $persenenam = ($sukses_enam/$tenam)*100; } echo number_format($persenenam)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttujuh==0){ $persentujuh = 0; }else{  $persentujuh = ($sukses_tujuh/$ttujuh)*100; } echo number_format($persentujuh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdelapan==0){ $persendelapan = 0; }else{  $persendelapan = ($sukses_delapan/$tdelapan)*100; } echo number_format($persendelapan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsembilan==0){ $persensembilan = 0; }else{  $persensembilan = ($sukses_sembilan/$tsembilan)*100; } echo number_format($persensembilan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsepuluh==0){ $persensepuluh = 0; }else{  $persensepuluh = ($sukses_sepuluh/$tsepuluh)*100; } echo number_format($persensepuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsebelas==0){ $persensebelas = 0; }else{  $persensebelas = ($sukses_sebelas/$tsebelas)*100; } echo number_format($persensebelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduabelas==0){ $persenduabelas = 0; }else{  $persenduabelas = ($sukses_duabelas/$tduabelas)*100; } echo number_format($persenduabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigabelas==0){ $persentigabelas = 0; }else{  $persentigabelas = ($sukses_tigabelas/$ttigabelas)*100; } echo number_format($persentigabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tempatbelas==0){ $persenempatbelas = 0; }else{  $persenempatbelas = ($sukses_empatbelas/$tempatbelas)*100; } echo number_format($persenempatbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tlimabelas==0){ $persenlimabelas = 0; }else{  $persenlimabelas = ($sukses_limabelas/$tlimabelas)*100; } echo number_format($persenlimabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tenambelas==0){ $persenenambelas = 0; }else{  $persenenambelas = ($sukses_enambelas/$tenambelas)*100; } echo number_format($persenenambelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttujuhbelas==0){ $persentujuhbelas = 0; }else{  $persentujuhbelas = ($sukses_tujuhbelas/$ttujuhbelas)*100; } echo number_format($persentujuhbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdelapanbelas==0){ $persendelapanbelas = 0; }else{  $persendelapanbelas = ($sukses_delapanbelas/$tdelapanbelas)*100; } echo number_format($persendelapanbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsembilanbelas==0){ $persensembilanbelas = 0; }else{  $persensembilanbelas = ($sukses_sembilanbelas/$tsembilanbelas)*100; } echo number_format($persensembilanbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduapuluh==0){ $persenduapuluh = 0; }else{  $persenduapuluh = ($sukses_duapuluh/$tduapuluh)*100; } echo number_format($persenduapuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduasatu==0){ $persenduasatu = 0; }else{  $persenduasatu = ($sukses_duasatu/$tduasatu)*100; } echo number_format($persenduasatu)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduadua==0){ $persenduadua = 0; }else{  $persenduadua = ($sukses_duadua/$tduadua)*100; } echo number_format($persenduadua)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduatiga==0){ $persenduatiga = 0; }else{  $persenduatiga = ($sukses_duatiga/$tduatiga)*100; } echo number_format($persenduatiga)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduaempat==0){ $persenduaempat = 0; }else{  $persenduaempat = ($sukses_duaempat/$tduaempat)*100; } echo number_format($persenduaempat)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdualima==0){ $persendualima = 0; }else{  $persendualima = ($sukses_dualima/$tdualima)*100; } echo number_format($persendualima)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduaenam==0){ $persenduaenam = 0; }else{  $persenduaenam = ($sukses_duaenam/$tduaenam)*100; } echo number_format($persenduaenam)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduatujuh==0){ $persenduatujuh = 0; }else{  $persenduatujuh = ($sukses_duatujuh/$tduatujuh)*100; } echo number_format($persenduatujuh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduadelapan==0){ $persenduadelapan = 0; }else{  $persenduadelapan = ($sukses_duadelapan/$tduadelapan)*100; } echo number_format($persenduadelapan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduasembilan==0){ $persenduasembilan = 0; }else{  $persenduasembilan = ($sukses_duasembilan/$tduasembilan)*100; } echo number_format($persenduasembilan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigapuluh==0){ $persentigapuluh = 0; }else{  $persentigapuluh = ($sukses_tigapuluh/$ttigapuluh)*100; } echo number_format($persentigapuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigasatu==0){ $persentigasatu = 0; }else{  $persentigasatu = ($sukses_tigasatu/$ttigasatu)*100; } echo number_format($persentigasatu)." %";?></b></td>
                            <?php if($ttotal==0){ $persentotal = 0; }else{ $persentotal = ($tsukses/$ttotal)*100; } ?>
                            <td style="background-color: whitesmoke;" class="text-right">
                                <b><?php echo number_format($persentotal)." %"; ?></b>
                            </td>
                        </tr>
                    <?php } ?>
                        
                    </tbody>
                </table><?php
        }

    }

}