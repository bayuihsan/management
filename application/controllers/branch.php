<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        // $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Branchmodel','regionmodel'));
    }
    
    public function index(){
        redirect('branch/view');
    }

	public function view($action='')
	{   
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/branch/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['region']=$this->regionmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/branch/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/branch/add');
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama_branch']    =addslashes($this->input->post('nama_branch',true)); 
            $data['ketua']          =addslashes($this->input->post('ketua',true)); 
            $data['status']         =addslashes($this->input->post('status',true));  
            $data['update_by']      =addslashes($this->input->post('update_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_branch', 'Nama Branch', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('ketua', 'Ketua', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('update_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('branch',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('branch_id',true));
                    
                    $this->db->where('branch_id', $id);
                    $this->db->update('branch', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('branch', array('branch_id' => $param1));       
        }
	}

    /** Method For get branch information for Branch Edit **/ 
    public function edit($branch_id,$action='')
    {
        $data=array();
        $data['edit_branch']=$edit_branch=getOld("branch_id",$branch_id,"branch"); 
        if(empty($edit_branch)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/branch/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA_BRANCH", "KETUA", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $branch = $this->Branchmodel->get_all();

            $excel_row = 2;

            foreach($branch as $row)
            {
                if($row->status == 1){
                    $status = "Aktif";
                }else{
                    $status = "Tidak Aktif";
                }
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->branch_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->ketua));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->time_update));
                $excel_row++;
            }

            $filename = "Branch-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
