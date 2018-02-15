<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesperson extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Salespersonmodel','Branchmodel','usersmodel'));
    }
    
    public function index(){
        redirect('Salesperson/view');
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['salesperson']=$this->Salespersonmodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/sales_person/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/list',$data);
            $this->load->view('theme/include/footer');
        }
	}

    function tl()
    {
        $branch_id = $this->input->post('branch_id');
        //$data['sales'] = $sales_channel;
        $data['tl_branch'] = $this->input->post('tl');
        if($branch_id=="ALL" || $branch_id=='17'){
            $data['tl'] = $this->mcrud->kondisi("app_users",array('level'=>3,'keterangan'=>'Aktif'))->result();
        }else{
            $data['tl'] = $this->mcrud->kondisi("app_users",array('branch_id'=>$branch_id,'level'=>3,'keterangan'=>'Aktif'))->result();
        }
        
        $this->load->view('sales_person/tl',$data);
    }
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_branch_by_id($branch_id);  
            $data['pilihid']=$this->usersmodel->get_all_tl_branch($branch_id, $level); 
        }else{
            $data['pilihbranch']=$this->Branchmodel->get_all();
            $data['pilihid']=$this->usersmodel->get_all_tl();
        }
        
        if($action=='asyn'){
            $this->load->view('content/sales_person/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/sales_person/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));  
            $data['user_sales']     = $user_sales = str_replace(" ", "_", addslashes($this->input->post('user_sales',true)));     
            $data['nama_sales']     =addslashes($this->input->post('nama_sales',true)); 
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['id_users']       =addslashes($this->input->post('id_users',true)); //ID TL 
            $data['no_telp']        =addslashes($this->input->post('no_telp',true));  
            $data['nama_bank']      =addslashes($this->input->post('nama_bank',true));  
            $data['no_rekening']    =addslashes($this->input->post('no_rekening',true));  
            $data['atas_nama']      =addslashes($this->input->post('atas_nama',true));  
            $data['status']         =addslashes($this->input->post('status',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('user_sales', 'User Sales', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('nama_sales', 'Nama Sales', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_users', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|xss_clean|min_length[10]|numeric');
            $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('no_rekening', 'No Rekening', 'trim|required|xss_clean|min_length[5]|numeric');
            $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    if(count($this->Salespersonmodel->get_users_by_username($data['user_sales']))>0) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        $this->db->insert('sales_person',$data);
                        echo "true";
                    }
                    
                }else if($do=='update'){
                    $user_sales1      = addslashes($this->input->post('user_sales1',true));
                    if(count($this->Salespersonmodel->get_users_by_username($user_sales1))>0) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        if($user_sales1 == '' || $user_sales1 == null){
                            $data['user_sales'] = $user_sales;
                        }else{
                            $data['user_sales'] = $user_sales1;
                        }

                        $id=$this->input->post('id_sales',true);
                        
                        $this->db->where('id_sales', $id);
                        $this->db->update('sales_person', $data);

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
            $this->db->delete('sales_person', array('id_sales' => $param1));       
        }
	}

    /** Method For get paket information for Branch Edit **/ 
    public function edit($id_sales,$action='')
    {
        $data=array();
        $data['edit_salesperson']=$this->Salespersonmodel->get_salesperson_by_id($id_sales);
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_branch_by_id($branch_id);  
            $data['pilihid']=$this->usersmodel->get_all_tl_branch($branch_id, $level); 
        }else{
            $data['pilihbranch']=$this->Branchmodel->get_all();
            $data['pilihid']=$this->usersmodel->get_all_tl();
        }
        if($action=='asyn'){
            $this->load->view('content/sales_person/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}