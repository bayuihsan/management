<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class custom_fields extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('custom_fieldsmodel'));
    }
    
    public function index(){
        redirect('custom_fields/add');
    }

    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        checkPermission(4);
        $data['custom_fields']=$this->custom_fieldsmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/custom_fields/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/custom_fields/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For eksekusi-----// 
        if($action=='eksekusi'){  
            $data=array();
            $do=$this->input->post('action',true);     
            $data['desc_query'] = $input_query = $this->input->post('input_query',true); 
            $data['id_users'] = $this->input->post('id_users',true);
            //-----Validation-----//   
            $this->form_validation->set_rules('input_query', 'Query', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('id_users', 'Silahkan Login. ID User', 'trim|required|numeric');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='eksekusi'){ 
                    $this->db->insert('custom_fields',$data);
                    $this->db->query($input_query); 
                    
                    echo "true";         
                }
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
	}

    /** Method For get custom_fields information for custom_fields Edit **/ 
    public function edit($custom_fields_id,$action='')
    {
        checkPermission(4);
        $data=array();
        $data['edit_custom_fields']=$this->custom_fieldsmodel->get_custom_fields_by_id($custom_fields_id); 
        if($action=='asyn'){
            $this->load->view('content/custom_fields/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/custom_fields/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
   
}
