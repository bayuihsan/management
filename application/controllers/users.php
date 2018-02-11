<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Usermodel','Branchmodel'));
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['users']=$this->usersmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/users/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/users/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/users/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do=$this->input->post('action',true);     
            $data['nama']=$this->input->post('nama',true); 
            $data['branch_id']=$this->input->post('branch_id',true); 
            $data['channel']=$this->input->post('channel',true);  
            $data['level']=$this->input->post('level',true);  
            $data['no_rekening']=$this->input->post('no_rekening',true);  
            $data['nama_bank']=$this->input->post('nama_bank',true);

            $data['username']=$this->input->post('username',true);     
            $data['password']=$this->input->post('password',true);     
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('branch_id', 'Branch ', 'trim|required');
            $this->form_validation->set_rules('channel', 'Channel', 'trim|requddired');
            $this->form_validation->set_rules('level', 'Level', 'trim|required');
            $this->form_validation->set_rules('no_rekening', 'No Rekening', 'trim|required|numeric|min_length[5]');
            $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'trim|required');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('users',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('users_id',true);
                    
                    $this->db->where('users_id', $id);
                    $this->db->update('users', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('users', array('users_id' => $param1));       
        }
	}

    /** Method For get users information for users Edit **/ 
    public function edit($users_id,$action='')
    {
        $data=array();
        $data['edit_users']=$this->usersmodel->get_users_by_id($users_id); 
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/users/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
   
}
