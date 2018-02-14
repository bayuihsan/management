<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msisdn extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Msisdnmodel'));
    }
    
    public function index(){
        redirect('msisdn/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['msisdn']=$this->Msisdnmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/msisdn/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/msisdn/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['msisdn']=$this->Msisdnmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/msisdn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/msisdn/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do=$this->input->post('action',true);     
            $data['msisdn']=$this->input->post('msisdn',true); 
            $data['tipe']=$this->input->post('tipe',true); 
            $data['id_users']=$this->input->post('id_users',true); 
            $data['status']=$this->input->post('status',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('msisdn', 'MSISDN', 'trim|required|min_length[9]|numeric');
            $this->form_validation->set_rules('tipe', 'Tipe', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('id_users', 'ID Users', 'trim|required');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('msisdn',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_haloinstan',true);
                    
                    $this->db->where('id_haloinstan', $id);
                    $this->db->update('msisdn', $data);

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
        $data['edit_paket']=$this->Paketmodel->get_paket_by_id($paket_id);
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}