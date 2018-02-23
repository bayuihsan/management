<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc', TRUE);
        $this->load->model(array('usersmodel','Branchmodel'));
        checkPermission(4);
    }
    
    public function index(){
        redirect('users/view');
    }

	public function view($action='')
	{   

        $data=array();
        $data['users']=$this->usersmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/users/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/users/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/users/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama']           =addslashes($this->input->post('unama',true)); 
            $data['no_hp']          =addslashes($this->input->post('uno_hp',true)); 
            $data['branch_id']      =addslashes($this->input->post('ubranch_id',true)); 
            $data['channel']        =addslashes($this->input->post('uchannel',true));  
            $data['level']          =addslashes($this->input->post('ulevel',true));  
            $data['no_rekening']    =addslashes($this->input->post('uno_rekening',true));  
            $data['nama_bank']      =addslashes($this->input->post('unama_bank',true));
            $data['keterangan']     =addslashes($this->input->post('uketerangan',true));

            $data['username'] = $username = str_replace(" ", "_", addslashes($this->input->post('uusername',true)));         
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('unama', 'Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('uno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('ubranch_id', 'Branch ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('uchannel', 'Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ulevel', 'Level', 'trim|required|xss_clean');
            $this->form_validation->set_rules('uno_rekening', 'No Rekening', 'trim|required|xss_clean|numeric|min_length[8]');
            $this->form_validation->set_rules('unama_bank', 'Nama Bank', 'trim|required|xss_clean');
            $this->form_validation->set_rules('uketerangan', 'Status', 'trim|required|xss_clean');

            $this->form_validation->set_rules('uusername', 'Username', 'trim|required|xss_clean|min_length[3]');

            if($do == 'insert'){
                $data['password']   =addslashes($this->input->post('upassword',true));
                $this->form_validation->set_rules('upassword', 'Password', 'trim|required|xss_clean|matches[urepassword]');
                $this->form_validation->set_rules('urepassword', 'Confirm Password', 'trim|required|xss_clean');
            }


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    if(value_exists("app_users","username",$data['username'])) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        $this->db->insert('app_users',$data);
                        echo "true";
                    }
                     
                }else if($do=='update'){
                    $username1      = addslashes($this->input->post('uusername1',true));
                    if(value_exists("app_users","username",$username1)) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        if($username1 == '' || $username1 == null){
                            $data['username'] = $username;
                        }else{
                            $data['username'] = $username1;
                        }

                        $id=$this->input->post('uid_users',true);
                        
                        $this->db->where('id_users', $id);
                        $this->db->update('app_users', $data);

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
            $this->db->delete('app_users', array('id_users' => $param1));       
        }else if($action == 'reset'){
            $new = "Tsel1234";
            $datax['password']=md5($new);
                        
            $this->db->where(array('id_users' => $param1));
            $this->db->update('app_users', $datax);

            echo $new;
        }
	}

    /** Method For get users information for users Edit **/ 
    public function edit($users_id,$action='')
    {
        $data=array();
        $data['edit_users']=$this->usersmodel->get_users_by_id($users_id); 
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/users/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
    public function export($action=""){
        if($action=="asyn"){
            $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "USERNAME", "NAMA_USER", "BRANCH", "NO_HP", "CHANNEL", "LEVEL", "KETERANGAN", "LAST_LOGIN");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $users = $this->usersmodel->get_all(); 

            $excel_row = 2;

            foreach($users as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_users));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->username));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->no_hp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($channel[$row->channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($level[$row->level]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->keterangan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper($row->last_login));
                $excel_row++;
            }

            $filename = "Users-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
