<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class grapari extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('graparimodel','Branchmodel'));
    }
    
    public function index(){
        redirect('grapari/view');
    }
	public function view($action='')
	{   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['grapari']=$this->graparimodel->get_all(); 
        }else{
            $data['grapari']=$this->graparimodel->get_all_by($sess_branch); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/grapari/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/grapari/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/grapari/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/grapari/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['nama_grapari']    =addslashes($this->input->post('nama_grapari',true)); 
            $data['username']       =addslashes($this->input->post('username',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('nama_grapari', 'Nama Grapari', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('grapari',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_grapari',true);
                    
                    $this->db->where('id_grapari', $id);
                    $this->db->update('grapari', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('grapari', array('id_grapari' => $param1));       
        }
	}

    /** Method For get grapari information for Branch Edit **/ 
    public function edit($grapari_id,$action='')
    {
        $data=array();
        $data['edit_grapari']=getOld("id_grapari",$grapari_id,"grapari");
        $data['branch']=$this->Branchmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/grapari/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/grapari/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "BRANCH", "NAMA GRAPARI", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $data_grapari = $this->graparimodel->get_all();

            }else{
                $data_grapari = $this->graparimodel->get_all_by($sess_branch);
            }
            
            $excel_row = 2;
            foreach($data_grapari as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_grapari));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_grapari));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->tgl_update);
                $excel_row++;
            }

            $filename = "Grapari-Exported-on-".date("Y-m-d-H-i-s").".xls";

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