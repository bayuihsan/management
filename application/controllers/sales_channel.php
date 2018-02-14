<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sales_channel extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('sales_channelmodel','Branchmodel'));
    }
    
    public function index(){
        redirect('sales_channel/view');
    }
	public function view($action='')
	{   
        $data=array();
        $data['sales_channel']=$this->sales_channelmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/sales_channel/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_channel/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/sales_channel/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/sales_channel/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do=$this->input->post('action',true);     
            $data['sales_channel']=$this->input->post('sales_channel',true); 
            $data['branch_id']=$this->input->post('branch_id',true); 
            $data['sub_channel']=$this->input->post('sub_channel',true); 
            $data['username']=$this->input->post('username',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('sales_channel', 'Nama Channel', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('sub_channel', 'Sub Channel', 'trim|required|xss_clean|alpa_numeric|min_length[3]');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|alpa_numeric');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('sales_channel',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('sales_channel_id',true);
                    
                    $this->db->where('sales_channel_id', $id);
                    $this->db->update('sales_channel', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('sales_channel', array('sales_channel_id' => $param1));       
        }
	}

    /** Method For get sales_channel information for Branch Edit **/ 
    public function edit($sales_channel_id,$action='')
    {
        $data=array();
        $data['edit_sales_channel']=$this->sales_channelmodel->get_sales_channel_by_id($sales_channel_id);
        $data['kategori_sales_channel']=$this->Kategorisales_channelmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/sales_channel/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_channel/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}