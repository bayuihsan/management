<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedbacks extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Feedbacksmodel'));
    }
    
    public function index(){
        redirect('feedbacks/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['feedbacks']=$this->Feedbacksmodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/feedbacks/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/feedbacks/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['feedbacks']=$this->Feedbacksmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/feedbacks/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/feedbacks/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['no_hp']          =addslashes($this->input->post('no_hp',true)); 
            $data['nama_pelanggan'] =addslashes($this->input->post('nama_pelanggan',true)); 
            $data['kota']           =addslashes($this->input->post('kota',true)); 
            $data['saran']          =addslashes($this->input->post('saran',true));  
            $data['id_users']       =addslashes($this->input->post('id_users',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|numeric');
            $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('kota', 'Kota', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('saran', 'Saran', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('id_users', 'ID Users', 'trim|required|xss_clean|min_length[3]');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('feedback',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_feedback',true));
                    
                    $this->db->where('id_feedback', $id);
                    $this->db->update('feedback', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('feedback', array('id_feedback' => $param1));       
        }
	}

    /** Method For get paket information for Branch Edit **/ 
    public function edit($id_feedback,$action='')
    {
        $data=array();
        $data['edit_feedbacks']=$edit_feedbacks=getOld("id_feedback",$id_feedback,"feedback");
        if(empty($edit_feedbacks)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/feedbacks/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/feedbacks/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NO_HP", "NAMA_PELANGGAN", "KOTA", "SARAN", "USERS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $feedbacks = $this->Feedbacksmodel->get_all();

            $excel_row = 2;

            foreach($feedbacks as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_feedback));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->no_hp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->kota));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->saran));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->tgl_update));
                $excel_row++;
            }

            $filename = "Feedback-Exported-on-".date("Y-m-d-H-i-s").".xls";

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