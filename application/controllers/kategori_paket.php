<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_paket extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc', TRUE);
        $this->load->model('Kategoripaketmodel');
        $this->load->library('form_validation');
    }
    
	public function view($action='')
	{
        $data=array();
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/kategori_paket/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/kategori_paket/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        if($action=='asyn'){
        $this->load->view('content/kategori_paket/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/kategori_paket/add');
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){
            $data=array();
            $do=$this->input->post('action',true);     
            $data['nama_kategori']=$this->input->post('nama_kategori',true); 
            $data['harga_kategori']=$this->input->post('harga_kategori',true);   
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('harga_kategori', 'Harga Kategori', 'trim|required|min_length[3]');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db2->insert('kategori_paket',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_kategori',true);
                    
                    $this->db2->where('id_kategori', $id);
                    $this->db2->update('kategori_paket', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db2->delete('kategori_paket', array('id_kategori' => $param1));       
        }
	}

    /** Method For get branch information for Branch Edit **/ 
    public function edit($id_kategori,$action='')
    {
        $data=array();
        $data['edit_kategori']=$this->Kategoripaketmodel->get_kategori_paket_by_id($id_kategori); 
        if($action=='asyn'){
            $this->load->view('content/kategori_paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/kategori_paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
}