<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        // $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Kategoripaketmodel', 'Paketmodel'));
    }
    
    public function index(){
        redirect('Paket/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['paket']=$this->Paketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/paket/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/paket/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/paket/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama_paket']     =addslashes($this->input->post('nama_paket',true)); 
            $data['harga_paket']    =addslashes($this->input->post('harga_paket',true)); 
            $data['aktif']          =addslashes($this->input->post('aktif',true)); 
            $data['id_kategori']    =addslashes($this->input->post('id_kategori',true));  
            $data['update_by']      =addslashes($this->input->post('update_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required|xss_clean|min_length[5]|numeric');
            $this->form_validation->set_rules('aktif', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_kategori', 'Kategori', 'trim|required|xss_clean');
            $this->form_validation->set_rules('update_by', 'Input By', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('paket',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('paket_id',true);
                    
                    $this->db->where('paket_id', $id);
                    $this->db->update('paket', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('paket', array('paket_id' => $param1));       
        }
	}

    /** Method For get paket information for Branch Edit **/ 
    public function edit($paket_id,$action='')
    {
        $data=array();
        $data['edit_paket']=$edit_paket=getOld("paket_id",$paket_id,"paket");
        if(empty($edit_paket)){
            redirect('Admin/home');
        }
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }  

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA", "HARGA", "KATEGORI", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $paket = $this->Paketmodel->get_all();

            $excel_row = 2;

            foreach($paket as $row)
            {
                if ($row->aktif == "y") { $status = "AKTIF"; }else{ $status="TIDAK AKTIF"; }
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->paket_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, get_current_setting('currency_code')." ".number_format($row->harga_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->time_update));
                $excel_row++;
            }

            $filename = "Paket-Exported-on-".date("Y-m-d-H-i-s").".xls";

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