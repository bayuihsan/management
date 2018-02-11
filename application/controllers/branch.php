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
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Branchmodel'));
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
            $do=$this->input->post('action',true);     
            $data['nama_branch']=$this->input->post('nama_branch',true); 
            $data['ketua']=$this->input->post('ketua',true); 
            $data['status']=$this->input->post('status',true);  
            $data['update_by']=$this->input->post('update_by',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_branch', 'Nama Branch', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('ketua', 'Ketua', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');
            $this->form_validation->set_rules('update_by', 'Input by', 'trim|required');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('branch',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('branch_id',true);
                    
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
        $data['edit_branch']=$this->Branchmodel->get_branch_by_id($branch_id); 
        if($action=='asyn'){
            $this->load->view('content/branch/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
   
}
