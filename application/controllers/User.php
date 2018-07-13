<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
		// $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Usermodel'));

		/*cash control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
         

    }
         
	//Method for view login page 
	public function index(){
	    if($this->session->userdata('logged_in')==TRUE){
        	redirect('Admin/home');    
        }
        $data=array();
		$this->load->view('theme/login');	
	}

	//Method for varify login information 
	public function varifyUser(){
        if($this->session->userdata('logged_in')==TRUE){
    	    redirect('Admin');    
        }
        $username   =addslashes($this->input->post('username',true));
        $password   =addslashes($this->input->post('password',true));
        
        $this->form_validation->set_rules('username','Username','trim|strip_tags|xss_clean|required|min_length[3]');
		$this->form_validation->set_rules('password','Password','trim|xss_clean|required');

        if(!$this->form_validation->run() == FALSE){
        	if($this->Usermodel->login($username,md5($password))){
        		echo "true";	
        	}else{
        		echo "False";
        	}
        	
        }else{
        	echo "False";
        }

	}
	   
	//Method for logout
	public function logout(){
	   	$this->Usermodel->logout();
	   	redirect('User');
	}


}