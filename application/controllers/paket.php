<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Kategoripaketmodel', 'Paketmodel'));
    }
    
	public function view($action='')
	{   
        $data=array();
        $data['paket']=$this->Paketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/paket/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/paket/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/paket/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do=$this->input->post('action',true);     
            $data['nama_paket']=$this->input->post('nama_paket',true); 
            $data['harga_paket']=$this->input->post('harga_paket',true); 
            $data['aktif']=$this->input->post('aktif',true); 
            $data['id_kategori']=$this->input->post('id_kategori',true);  
            $data['update_by']=$this->input->post('update_by',true);  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required|min_length[4]|numeric');
            $this->form_validation->set_rules('aktif', 'Status', 'trim|required');
            $this->form_validation->set_rules('id_kategori', 'Kategori', 'trim|required');
            $this->form_validation->set_rules('update_by', 'Input By', 'trim|required');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('paket',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('paket_id',true);
                    
                    $this->db->where('paket_id', $id);
                    $this->db->update('paket', $data);

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