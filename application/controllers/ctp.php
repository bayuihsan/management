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
            $do                             =addslashes($this->input->post('action',true));
            $data['order_id']               =addslashes($this->input->post('order_id',true)); 
            $data['order_submit_date']      =addslashes($this->input->post('order_submit_date',true)); 
            $data['order_completed_date']   =addslashes($this->input->post('order_completed_date',true)); 
            $data['msisdn_ctp']             =addslashes($this->input->post('msisdn_ctp',true));  
            $data['nama_pelanggan_ctp']     =addslashes($this->input->post('nama_pelanggan_ctp',true));  
            $data['order_type_name']        =addslashes($this->input->post('order_type_name',true)); 
            $data['user_id']                =addslashes($this->input->post('user_id',true)); 
            $data['employee_name']          =addslashes($this->input->post('employee_name',true)); 
            $data['paket_id']               =addslashes($this->input->post('paket_id',true)); 
            $data['id_users']               =addslashes($this->input->post('id_users',true)); 
            $data['cbranch_id']             =addslashes($this->input->post('branch_id',true)); 
            $data['tgl_upload']             =addslashes($this->input->post('tgl_upload',true)); 
       
            //-----Validation-----//   
            $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_submit_date', 'Order Submit Date', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('order_completed_date', 'Order Complete Date', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('msisdn_ctp', 'MSISDN', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('nama_pelanggan_ctp', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('order_type_name', 'Order Type', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('employee_name', 'Employee Name', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('paket_id', 'Paket', 'trim|required|xss_clean|min_length[1]');
            $this->form_validation->set_rules('id_users', 'Admin CTP', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('cbranch_id', 'Branch', 'trim|required|xss_clean|min_length[1]');
            $this->form_validation->set_rules('tgl_upload', 'Tgl Upload', 'trim|required|xss_clean|min_length[3]');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('ctp',$data); 
                    
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
            $this->db->delete('ctp', array('id_ctp' => $param1));       
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