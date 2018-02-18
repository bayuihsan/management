<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctp extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Ctpmodel','Paketmodel','Branchmodel','usersmodel'));
    }
    
    public function index(){
        redirect('ctp/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['ctp']=$this->Ctpmodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/ctp/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/ctp/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['paket']=$this->Paketmodel->get_all(); 
        $data['branch']=$this->Branchmodel->get_all(); 
        $data['users']=$this->usersmodel->get_all_ctp(); 
        if($action=='asyn'){
            $this->load->view('content/ctp/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/ctp/add',$data);
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
            $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
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
                    $id=addslashes($this->input->post('id_ctp',true));
                    
                    $this->db->where('id_ctp', $id);
                    $this->db->update('ctp', $data);

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
    public function edit($id_ctp,$action='')
    {
        $data=array();
        $data['edit_ctp']=$this->Ctpmodel->get_ctp_by_id($id_ctp);
        if($action=='asyn'){
            $this->load->view('content/ctp/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/ctp/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}