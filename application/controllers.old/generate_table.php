<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class generate_table extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('generate_tablemodel','Branchmodel'));
    }
    
    public function index(){
        redirect('generate_table/view');
    }

	public function view($action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
	{   
        $data=array();
        if($action=='asyn'){
            $data['generate_table']=$this->generate_tablemodel->get_all(); 
            $this->load->view('content/generate_table/list',$data);
        }else if($action==''){
            $data['generate_table']=$this->generate_tablemodel->get_all(); 
            $this->load->view('theme/include/header');
            $this->load->view('content/generate_table/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        if($action=='asyn'){
            $this->load->view('content/generate_table/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/generate_table/add');
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['bulan']          = $bulan = addslashes($this->input->post('bulan',true)); 
            $data['tahun']          = $tahun = addslashes($this->input->post('tahun',true));  
            $data['status']         =addslashes($this->input->post('status',true));  
            $data['id_users']       =addslashes($this->input->post('input_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('bulan', 'Bulan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('input_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    $qgenerate = $this->db->query("select * from new_psb_temp where bulan='".$bulan."' and tahun='".$tahun."'")->result();
                    if(count($qgenerate) > 0){
                        echo "This Tabel Is Already Exists.!!";
                    }else{
                        if($bulan < 10){
                            $bulan = '0'.$bulan;
                        }else{
                            $bulan = $bulan;
                        }
                        $data['nama_table'] = $nama_table = "sales_psb_".$bulan."_".$tahun;

                        $this->db->insert('new_psb_temp',$data); 

                        $this->db->query("CREATE TABLE ".$nama_table." AS SELECT * 
                            FROM new_psb 
                            WHERE status='sukses' and DATE_FORMAT(tanggal_aktif,'%Y')='".$tahun."' and DATE_FORMAT(tanggal_aktif,'%m')='".$bulan."'
                            ");
                        
                        echo "true";    
                    }
                    
                }      
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){
            $qgenerate = $this->generate_tablemodel->get_generate_by_id($param1);
            $nama_table = $qgenerate->nama_table;    
            if(!empty($nama_table)){
                $this->db->delete('new_psb_temp', array('id_temp' => $param1));  
                $this->db->query("DROP TABLE ".$nama_table);
            }
                
        }
	}

    /** Method For get generate_table information for generate_table Edit **/ 
    public function load_table($id_temp,$action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');

        $qgenerate = $this->generate_tablemodel->get_generate_by_id($id_temp); 
        $nama_table = $qgenerate->nama_table;
        $data['nama_table'] = $nama_table;
        $data['id_temp'] = $id_temp;
        $data['data_list'] = $this->generate_tablemodel->get_all_table($nama_table);
        if($this->session->userdata('level')==4){
            $data['branch'] = $this->Branchmodel->get_all();
        }else{
            $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

        }
        if($action=='asyn'){
            $this->load->view('content/generate_table/list_table',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/generate_table/list_table',$data);
            $this->load->view('theme/include/footer');
        }else if($action == 'cari'){        
            // $reportData=$this->Reportmodel->getSalesCari($account,$from_date,$to_date,$trans_type);
            $data['bbranch_id'] = $branch_id;
            $data['btgl'] = $tgl;
            $data['bstatus'] = $status;
            $data['bfrom_date'] = $from_date;
            $data['bto_date'] = $to_date;
            if($branch_id == 17){
                $data['data_list'] = $cari = $this->generate_tablemodel->get_all_cari_all($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }else{

                $data['data_list'] = $cari = $this->generate_tablemodel->get_all_cari($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }

            $this->load->view('content/generate_table/list_table',$data);
        } 
    }

    public function export($id_temp='',$action="",$branch_id='',$tgl='',$status='',$from_date='',$to_date=''){
        if($action=="asyn"){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "MSISDN", "NAMA_PELANGGAN", "BRANCH", "PAKET", "TL", "SALES_PERSON", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "FA_ID", "ACCOUNT_ID", "ALAMAT", "ALAMAT2", "DISCOUNT (RB)", "PERIODE (BULAN)", "BILL CYCLE", "CHANNEL", "SUB SALES CHANNEL", "JENIS EVENT", "NAMA EVENT", "VALIDATOR", "AKTIVATOR", "STATUS", "KETERANGAN", "TANGGAL_INPUT (SYSTEM)", "TANGGAL_UPDATE (SYSTEM)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $qgenerate = $this->generate_tablemodel->get_generate_by_id($id_temp); 
            $nama_table = $qgenerate->nama_table;

            if($branch_id == 17){
                $generate_table = $this->generate_tablemodel->get_all_cari_all($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }else{
                $generate_table = $this->generate_tablemodel->get_all_cari($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }

            $excel_row = 2;

            foreach($generate_table as $row)
            {
                // if(!empty($row->tanggal_aktif)){
                //     $tgl_aktif = new DateTime($row->tanggal_aktif);
                // }else{
                //     $tgl_aktif = new DateTime();
                // }
                // $tgl_masuk = new DateTime($row->tanggal_masuk);
                // $diff = $tgl_aktif->diff($tgl_masuk); 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->TL));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                // $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($diff->d));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($row->fa_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($row->account_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, strtoupper($row->alamat));
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, strtoupper($row->alamat2));
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, strtoupper($discount[$row->discount]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, strtoupper($periode[$row->periode]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, strtoupper($row->bill_cycle));
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, strtoupper($channel[$row->sales_channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, strtoupper($jenis_event[$row->jenis_event]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, strtoupper($row->nama_event));
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, strtoupper($row->validator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, strtoupper($row->aktivator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, strtoupper($row->deskripsi));
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, strtoupper($row->tanggal_input));
                $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "generate_table-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
