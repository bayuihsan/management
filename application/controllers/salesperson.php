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
        $data['add-salesperson']=$this->Salespersonmodel->get_all();
        $data['pilihbranch']=$this->Branchmodel->get_all();  
        $data['pilihid']=$this->usersmodel->get_all_tl(); 
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
            $do=$this->input->post('action',true);     
            $data['user_sales']=$this->input->post('user_sales',true); 
            $data['nama_sales']=$this->input->post('nama_sales',true); 
            $data['branch_id']=$this->input->post('branch_id',true); 
            $data['id_users']=$this->input->post('id_kategori',true);  
            $data['status']=$this->input->post('status',true);  
            $data['nama_bank']=$this->input->post('nama_bank',true);  
            $data['no_rekening']=$this->input->post('no_rekening',true);  
            $data['atas_nama']=$this->input->post('atas_nama',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('user_sales', 'User Sales', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('nama_sales', 'Nama Sales', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required');
            $this->form_validation->set_rules('id_users', 'ID Users', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('sales_person',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_sales',true);
                    
                    $this->db->where('id_sales', $id);
                    $this->db->update('sales_person', $data);

                    echo "true";

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
        $data['edit_salesperson']=$this->Salespersonmodel->get_salesperson_by_id($id_salesperson);
        $data['pilihbranch']=$this->Branchmodel->get_all();  
        $data['pilihid']=$this->usersmodel->get_all_tl();  
        if($action=='asyn'){
            $this->load->view('content/sales_person/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   
}