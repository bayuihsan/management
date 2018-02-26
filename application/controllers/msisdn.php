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
        $this->load->model(array('usersmodel','Msisdnmodel','Branchmodel'));
    }
    
    public function index(){
        redirect('msisdn/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level == 4){
            $data['msisdn']=$this->Msisdnmodel->get_all(); 
        }else{
            $data['msisdn']=$this->Msisdnmodel->get_all_by($sess_branch); 
        }
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
        
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['TL'] = $this->usersmodel->get_all_tl();
            $data['branch'] = $this->Branchmodel->get_all();
        }else{
            $data['TL']=$this->usersmodel->get_all_tl_by($sess_branch);
            $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);
        }
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
            $msisdn_all = $this->input->post('msisdn',true); 
            $data['branch_id'] =$branch_id=$this->input->post('mbranch_id',true); 
            $data['tipe'] =$tipe=$this->input->post('tipe',true); 
            $data['id_users']=$id_tl=$this->input->post('mid_users',true); 
       
            //-----Validation-----//   
            $this->form_validation->set_rules('msisdn', 'MSISDN', 'trim|required|min_length[10]');
            $this->form_validation->set_rules('mbranch_id', 'Branch', 'trim|required');
            $this->form_validation->set_rules('tipe', 'Tipe', 'trim|required');
            $this->form_validation->set_rules('mid_users', 'ID Users', 'trim|required');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    $msisdn_all = str_replace(" ", "", $msisdn_all);
                    $msisdn_pecah = explode(',',$msisdn_all);
                    //print_r($msisdn_pecah);die();
                    foreach($msisdn_pecah as $new) {
                        if(substr($new, 0,3) == 628){
                            if(!value_exists("msisdn","msisdn",$new)) {
                                if($new != "" || $new != null){
                                    $data = array(
                                        'msisdn' => $new,
                                        'branch_id' => $branch_id,
                                        'tipe' => $tipe,
                                        'id_users' => $id_tl
                                    );
                                    $this->db->insert("msisdn", $data);
                                }
            
                            }    
                        }

                        
                    }
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
            $this->db->delete('msisdn', array('id_haloinstan' => $param1));       
        }
	}
    /** Method For get paket information for Branch Edit **/ 
    public function edit($id_haloinstan,$action='')
    {
        $data=array();
        $data['edit_msisdn']=$this->Msisdnmodel->get_msisdn_by_id($id_haloinstan);
        $data['TL']=$this->usersmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/msisdn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/msisdn/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}