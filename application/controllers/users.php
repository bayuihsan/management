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
        $this->db2 = $this->load->database('hvc', TRUE);
        $this->load->model(array('usersmodel','Branchmodel'));
    }
    
    public function index(){
        redirect('users/view');
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
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama']           =addslashes($this->input->post('nama',true)); 
            $data['no_hp']          =addslashes($this->input->post('no_hp',true)); 
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['channel']        =addslashes($this->input->post('channel',true));  
            $data['level']          =addslashes($this->input->post('level',true));  
            $data['no_rekening']    =addslashes($this->input->post('no_rekening',true));  
            $data['nama_bank']      =addslashes($this->input->post('nama_bank',true));
            $data['keterangan']     =addslashes($this->input->post('keterangan',true));

            $data['username'] = $username = str_replace(" ", "_", addslashes($this->input->post('username',true)));         
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('channel', 'Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');
            $this->form_validation->set_rules('no_rekening', 'No Rekening', 'trim|required|xss_clean|numeric|min_length[5]');
            $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'trim|required|xss_clean');
            $this->form_validation->set_rules('keterangan', 'Status', 'trim|required|xss_clean');

            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|alpa_numeric|min_length[5]');

            if($do == 'insert'){
                $data['password']   =addslashes($this->input->post('password',true));
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[repassword]');
                $this->form_validation->set_rules('repassword', 'Confirm Password', 'trim|required|xss_clean');
            }


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    if(count($this->usersmodel->get_users_by_username($data['username']))>0) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        $this->db->insert('app_users',$data);
                        echo "true";
                    }
                     
                }else if($do=='update'){
                    $username1      = addslashes($this->input->post('username1',true));
                    if(count($this->usersmodel->get_users_by_username($username1))>0) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        if($username1 == '' || $username1 == null){
                            $data['username'] = $username;
                        }else{
                            $data['username'] = $username1;
                        }

                        $id=$this->input->post('id_users',true);
                        
                        $this->db->where('id_users', $id);
                        $this->db->update('app_users', $data);

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
            $this->db->delete('app_users', array('id_users' => $param1));       
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
