<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sales extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        date_default_timezone_set(get_current_setting('timezone')); 
        $this->db2 = $this->load->database('hvc', TRUE);
        $this->load->model(array('salesmodel','Branchmodel','Reportmodel','Sales_channelmodel','Paketmodel','usersmodel','Salespersonmodel'));
    }
    
    public function index(){
        redirect('sales/view');
    }

	public function view($action='')
    {   
        $data=array();
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $tanggal = $tanggal->tgl_max;
        $data['sales'] = $this->salesmodel->get_all($tanggal);
        if($action=='asyn'){
            $this->load->view('content/sales/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    public function view2($action='')
	{   
        $data=array();
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $tanggal = $tanggal->tgl_max;
        if($action=='asyn'){
            $this->load->view('content/sales/list2',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/list2',$data);
            $this->load->view('theme/include/footer');
        }
	}

    public function ajax_list()
    {
        $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $tanggal = $tanggal->tgl_max;
        $list = $this->salesmodel->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $new) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $new->msisdn;
            $row[] = strtoupper($new->nama_pelanggan);
            $row[] = strtoupper($new->nama_branch);
            $row[] = isset($new->tanggal_masuk)? date('Y-m-d', strtotime($new->tanggal_masuk)) : '';
            $row[] = isset($new->tanggal_validasi)? date('Y-m-d', strtotime($new->tanggal_validasi)) : '';
            $row[] = isset($new->tanggal_aktif)? date('Y-m-d', strtotime($new->tanggal_aktif)) : '';
            $row[] = strtoupper($new->fa_id);
            $row[] = strtoupper($new->account_id);
            $row[] = strtoupper($new->alamat);
            $row[] = strtoupper($new->nama_paket);
            $row[] = strtoupper($channel[$new->sales_channel]);
            $row[] = strtoupper($new->sub_channel);
            $row[] = strtoupper($new->sales_person);
            $row[] = strtoupper($new->nama);
            $row[] = strtoupper($new->username);
            $row[] = strtoupper($new->status);
            $row[] = strtoupper($new->tanggal_update);
            
            //add html for action
            $row[] = '<a class="mybtn btn-info btn-xs edit-btn" data-toggle="tooltip" 
                title="Click For Edit" href="'.site_url('sales/edit/'.$new->psb_id).'">Edit</a> &nbsp; 
                <a class="mybtn btn-danger btn-xs sales-remove-btn" data-toggle="tooltip" title="Click For Delete" href="'.site_url('add/remove/'.$new->psb_id).'">Delete</a>';
        
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->salesmodel->count_all(),
                        "recordsFiltered" => $this->salesmodel->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        $data['branch']=$this->Branchmodel->get_all();
        $data['sub_channel']=$this->Sales_channelmodel->get_all();
        $data['paket']=$this->Paketmodel->get_all();
        $data['tl']=$this->usersmodel->get_all_tl();
        $data['validasi']=$this->usersmodel->get_all_validasi();
        $data['sales_person']=$this->Salespersonmodel->get_all();

        if($action=='asyn'){
            $this->load->view('content/sales/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/sales/add',$data);
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama']           =addslashes($this->input->post('nama',true)); 
            $data['no_hp']          =addslashes($this->input->post('no_hp',true)); 
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['channel']        =addslashes($this->input->post('channel',true));  
            $data['level']          =addslashes($this->input->post('level',true));  
            $data['no_rekening']    =addslashes($this->input->post('no_rekening',true));  
            $data['nama_bank']      =addslashes($this->input->post('nama_bank',true));
            $data['keterangan']     =addslashes($this->input->post('keterangan',true));

            $data['username'] = $username = str_replace(" ", "_", addslashes($this->input->post('username',true)));         
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('channel', 'Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');
            $this->form_validation->set_rules('no_rekening', 'No Rekening', 'trim|required|xss_clean|numeric|min_length[5]');
            $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'trim|required|xss_clean');
            $this->form_validation->set_rules('keterangan', 'Status', 'trim|required|xss_clean');

            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|alpa_numeric|min_length[5]');

            if($do == 'insert'){
                $data['password']   =addslashes($this->input->post('password',true));
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[repassword]');
                $this->form_validation->set_rules('repassword', 'Confirm Password', 'trim|required|xss_clean');
            }


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    if(value_exists("app_sales","username",$data['username'])) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        $this->db->insert('app_sales',$data);
                        echo "true";
                    }
                     
                }else if($do=='update'){
                    $username1      = addslashes($this->input->post('username1',true));
                    if(value_exists("app_sales","username",$username1)) {  

                        echo "This Username Is Already Exists !!!!"; 
                                            
                    }else{
                        if($username1 == '' || $username1 == null){
                            $data['username'] = $username;
                        }else{
                            $data['username'] = $username1;
                        }

                        $id=$this->input->post('id_sales',true);
                        
                        $this->db->where('id_sales', $id);
                        $this->db->update('app_sales', $data);

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
            $this->db->delete('app_sales', array('id_sales' => $param1));       
        }else if($action == 'reset'){
            $new = "Tsel1234";
            $datax['password']=md5($new);
                        
            $this->db->where(array('id_sales' => $param1));
            $this->db->update('app_sales', $datax);

            echo $new;
        }
	}

    /** Method For get sales information for sales Edit **/ 
    public function edit($sales_id,$action='')
    {
        $data=array();
        $data['edit_sales']=$this->salesmodel->get_sales_by_id($sales_id); 
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/sales/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
   
}
