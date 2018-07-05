<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

class Main extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Reportmodel','Adminmodel','Branchmodel'));
	}

	public function export_paket($action="",$branch_id,$tanggal,$status,$last_month,$to_date){
        if($action=="asyn"){
        	$tgl        		= date('d', strtotime($to_date));
        	$last_month        	= strtoupper($last_month);
        	$this_month        	= strtoupper(date('M', strtotime($to_date)));
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "PAKET", $last_month, $this_month, "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $reportData = $this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$to_date);

            $excel_row = 2;
            $no = 1;
            $tlm = 0;
		    $ttm = 0;
		    $tsla = 0;
            foreach($reportData as $row)
            {
                $lm = $row->last_month;
		        $tm = $row->this_month;
		        $avg = $tm/$tgl;
		        $sla = $row->sla;
		        $avgsla = $sla/$tm;
		        if($lm == 0){ 
		            $mom = 'Infinity'; 
		        }else{ 
		            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
		            
		        } 

		        if($avgsla < 16){
	                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
	                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_paket));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($lm));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($tm));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, round($avg));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, number_format($avgsla)." Hari");
	                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $mom.' %');
	                $excel_row++;

			        $tlm = $tlm + $lm;
			        $ttm = $ttm + $tm; 
			        $tsla = $tsla + $sla; 
		        }
            }

	        //Summery value
	        $tavg = $ttm/$tgl;
	        $tavgsla = $tsla/$ttm; 
	        $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($reportData)+1, 'TOTAL');
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($reportData)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($reportData)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($reportData)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($reportData)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($reportData)+1, $tmom);

            $filename = "PaketReport-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    public function export_branch($action="",$tanggal,$status,$last_month,$to_date){
        if($action=="asyn"){
        	$tgl        = date('d', strtotime($to_date));
        	$last_month        	= strtoupper($last_month);
        	$this_month        	= strtoupper(date('M', strtotime($to_date)));
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", $last_month, $this_month, "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$to_date);

            $excel_row = 2;
            $no = 1;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            foreach($reportData as $row)
            {
                $lm = $row->last_month;
		        $tm = $row->this_month;
		        $avg = $tm/$tgl;
		        $sla = $row->sla;
		        $avgsla = $sla/$tm;
		        if($lm == 0){ 
		            $mom = 'Infinity'; 
		        }else{ 
		            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
		            
		        } 

		        if($avgsla < 16){
	                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
	                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, number_format($lm));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($tm));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, round($avg));
	                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($avgsla)." Hari");
	                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $mom.' %');
	                $excel_row++;

			        $tlm = $tlm + $lm;
			        $ttm = $ttm + $tm; 
			        $tsla = $tsla + $sla; 
		        }
            }

	        //Summery value
	        $tavg = $ttm/$tgl;
	        $tavgsla = $tsla/$ttm; 
	        $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($reportData)+1, 'TOTAL');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($reportData)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($reportData)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($reportData)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($reportData)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($reportData)+1, $tmom." %");

            $filename = "BranchReport-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
