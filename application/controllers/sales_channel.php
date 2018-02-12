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
        $this->load->model(array('sales_channelmodel'));
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
        $data['kategori_sales_channel']=$this->Kategorisales_channelmodel->get_all(); 
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
            $data['nama_sales_channel']=$this->input->post('nama_sales_channel',true); 
            $data['harga_sales_channel']=$this->input->post('harga_sales_channel',true); 
            $data['aktif']=$this->input->post('aktif',true); 
            $data['id_kategori']=$this->input->post('id_kategori',true);  
            $data['update_by']=$this->input->post('update_by',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_sales_channel', 'Nama sales_channel', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('harga_sales_channel', 'Harga sales_channel', 'trim|required|min_length[4]|numeric');
            $this->form_validation->set_rules('aktif', 'Status', 'trim|required');
            $this->form_validation->set_rules('id_kategori', 'Kategori', 'trim|required');
            $this->form_validation->set_rules('update_by', 'Input By', 'trim|required');

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