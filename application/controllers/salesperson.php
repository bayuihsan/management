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
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['salesperson']=$this->Salespersonmodel->get_all();    
        }else{
            $data['salesperson']=$this->Salespersonmodel->get_all_by($sess_branch);    
        }
        if($action=='asyn'){
            $this->load->view('content/sales_person/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/list',$data);
            $this->load->view('theme/include/footer');
        }
	}

    /** Method For Add New sales person and sales person Page View **/ 	
    public function add($action='',$param1='')
	{
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_all_by($branch_id);  
            $data['pilihid']=$this->usersmodel->get_all_tl_branch($branch_id); 
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
            $data['branch_id']      =addslashes($this->input->post('sbranch_id',true)); 
            $data['id_users']       =addslashes($this->input->post('id_users',true)); //ID TL 
            $data['no_telp']        =addslashes($this->input->post('no_telp',true));  
            $data['nama_bank']      =addslashes($this->input->post('snama_bank',true));  
            $data['no_rekening']    =addslashes($this->input->post('sno_rekening',true));  
            $data['atas_nama']      =addslashes($this->input->post('atas_nama',true));  
            $data['status']         =addslashes($this->input->post('status',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('user_sales', 'User Sales', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('nama_sales', 'Nama Sales', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sbranch_id', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_users', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_bank', 'Nama Bank', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sno_rekening', 'No Rekening', 'trim|required|xss_clean|min_length[8]|numeric');
            $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    if(value_exists("sales_person","user_sales",$data['user_sales'])) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        $this->db->insert('sales_person',$data);
                        echo "true";
                    }
                    
                }else if($do=='update'){
                    $user_sales1      = addslashes($this->input->post('user_sales1',true));
                    if(value_exists("sales_person","user_sales",$user_sales1)) {  

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

    /** Method For get sales person information for sales person Edit **/ 
    public function edit($id_sales,$action='')
    {
        $data=array();
        $data['edit_salesperson']=getOld("id_sales",$id_sales,"sales_person");
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_all_by($branch_id);  
            $data['pilihid']=$this->usersmodel->get_all_tl_branch($branch_id); 
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

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "USER", "NAMA_SALES", "BRANCH", "USERS", "NO_HP", "NAMA_BANK", "NO_REKENING", "ATAS_NAMA", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $salesperson = $this->Salespersonmodel->get_all();

            $excel_row = 2;

            foreach($salesperson as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_sales));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->user_sales));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_sales));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->no_telp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->nama_bank));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->no_rekening));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper($row->atas_nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "SalesPerson-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }
        
    }
}