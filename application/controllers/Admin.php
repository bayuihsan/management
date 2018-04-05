<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Adminmodel','salesmodel','Reportmodel','Branchmodel'));
    }
    
	public function home($action='')
	{   
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');

        $data['title'] = "Data belum diproses";
        if($sess_level !=4){
            $data['sales'] = $sales = $this->salesmodel->getAllSalesBy($sess_level, $sess_branch);
        }

        $data['jumlah_sales'] = @count($sales);

        if($action=='asyn'){
            $this->load->view('content/index_2', $data);
        }else{
            $this->load->view('theme/include/header');
            $this->load->view('content/index_2', $data);
            $this->load->view('theme/include/footer');
        }
        
	}
	
    /** Method For dashboard **/ 
    public function dashboard($action='', $param1='')
	{
        $data=array();
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $tanggal->tgl_max;
        $data['lmonth'] = $lm = date('Y-m-d', strtotime('-1 month', strtotime( $tanggal->tgl_max )));
        $data['lyear'] = $ly = date('Y-m-d', strtotime('-1 year', strtotime( $tanggal->tgl_max )));
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $param1 = $tanggal->tgl_max;
            $data['cart_summery']=$this->Reportmodel->getCurMont($param1);
            $data['line_chart']=$this->Reportmodel->dayByDaySales($param1);
            $data['top_branch']=$this->Reportmodel->getTopBranch(20,$ly,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_tl']=$this->Reportmodel->getTopTL(10,$ly,$lm,$param1);
            $data['pie_data']=$this->Reportmodel->getContrStatus($param1);
            $data['last_login']=$this->Adminmodel->getLastLogin(10);
            $this->load->view('content/index',$data);
        }else if($action==''){
            $param1 = $tanggal->tgl_max;
            $data['cart_summery']=$this->Reportmodel->getCurMont($param1);
            $data['line_chart']=$this->Reportmodel->dayByDaySales($param1);
            $data['top_branch']=$this->Reportmodel->getTopBranch(20,$ly,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_tl']=$this->Reportmodel->getTopTL(10,$ly,$lm,$param1);
            $data['pie_data']=$this->Reportmodel->getContrStatus($param1);
            $data['last_login']=$this->Adminmodel->getLastLogin(10);
            $this->load->view('theme/include/header');
    		$this->load->view('content/index',$data);
    		$this->load->view('theme/include/footer');
        }else if($action=='view'){
            $data['max_tanggal'] = $param1 = $param1;
            $data['lmonth'] = $lm = date('Y-m-d', strtotime('-1 month', strtotime( $param1 )));
            $data['lyear'] = $ly = date('Y-m-d', strtotime('-1 year', strtotime( $param1 )));
            $data['cart_summery']=$this->Reportmodel->getCurMont($param1);
            $data['line_chart']=$this->Reportmodel->dayByDaySales($param1);
            $data['top_branch']=$this->Reportmodel->getTopBranch(20,$ly,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_tl']=$this->Reportmodel->getTopTL(10,$ly,$lm,$param1);
            $data['pie_data']=$this->Reportmodel->getContrStatus($param1);
            $data['last_login']=$this->Adminmodel->getLastLogin(10);
            $this->load->view('content/index',$data);
        }
	}

    function load_modal()
    {
        $tanggal    = $param1 = $this->input->post('tanggal',true);
        $date1      = $this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$tanggal."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
        $data['from_date'] = $date1;
        $data['to_date'] = $tanggal;
        $data['line_chart_branch']=$this->Reportmodel->dayByDaySalesBranch($date1, $tanggal);
        $this->load->view('content/load_modal_branch',$data);
    }

    function load_modal_detail_tsa()
    {
        $branch_id  = $this->input->post('branch_id',true);
        $tgl        = date('Y-m-d', strtotime($this->input->post('tanggal',true)));
        $tipe       = $this->input->post('tipe',true);
        if($tipe == "k30"){
            $data['title'] = "Detail Data <= 30";
            $data['branch_30'] = $this->db->query("SELECT a.branch_id, b.nama_branch, a.sales_person, COUNT(a.psb_id) AS jumlah
                                FROM new_psb a
                                JOIN branch b ON a.branch_id=b.branch_id
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id'
                                GROUP BY a.branch_id, a.sales_person
                                HAVING jumlah <= 30
                                ORDER BY jumlah DESC")->result();
        }else{
            $data['title'] = "Detail Data > 30";
            $data['branch_30'] = $this->db->query("SELECT a.branch_id, b.nama_branch, a.sales_person, COUNT(a.psb_id) AS jumlah
                                FROM new_psb a
                                JOIN branch b ON a.branch_id=b.branch_id
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id'
                                GROUP BY a.branch_id, a.sales_person
                                HAVING jumlah > 30
                                ORDER BY jumlah DESC")->result();
        }
        $data['branch_id'] = $branch_id;
        $this->load->view('content/load_modal_branch_detail_tsa',$data);
    }

    function load_modal_detail_tl()
    {
        $branch_id  = $this->input->post('branch_id',true);
        $tgl        = date('Y-m-d', strtotime($this->input->post('tanggal',true)));
        $tipe       = $this->input->post('tipe',true);
        if($tipe == "k200"){
            $data['title'] = "Detail Data <= 200";
            $data['branch_200'] = $this->db->query("SELECT a.branch_id, c.nama_branch, a.tl, b.nama, COUNT(psb_id) AS jumlah
                                FROM new_psb a 
                                JOIN app_users b ON a.tl = b.username 
                                JOIN branch c ON a.branch_id=c.branch_id
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id' AND b.level='3'
                                GROUP BY a.branch_id, a.tl
                                HAVING jumlah <= 200
                                ORDER BY jumlah DESC")->result();
        }else{
            $data['title'] = "Detail Data > 200";
            $data['branch_200'] = $this->db->query("SELECT a.branch_id, c.nama_branch, a.tl, b.nama, COUNT(psb_id) AS jumlah
                                FROM new_psb a 
                                JOIN app_users b ON a.tl = b.username 
                                JOIN branch c ON a.branch_id=c.branch_id
                                WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' AND a.branch_id='$branch_id' AND b.level='3'
                                GROUP BY a.branch_id, a.tl
                                HAVING jumlah > 200
                                ORDER BY jumlah DESC")->result();
        }
        $data['branch_id'] = $branch_id;
        $this->load->view('content/load_modal_branch_detail_tl',$data);
    }
    /** Method For general settings page  **/
    public function generalSettings($action='')
    {
        checkPermission(4);
        $data=array();  
        $data['settings']=$this->Adminmodel->getAllSettings();
        $data['timezone']=timezone_list();
        if($action=='asyn'){
            $this->load->view('content/General_settings',$data);
        }else if($action==''){    
            $this->load->view('theme/include/header');
            $this->load->view('content/General_settings',$data);
            $this->load->view('theme/include/footer');
        }

        //update general Settings 
        if($action=='update'){
            $data = array(
                array(
                  'settings' => 'company_name' ,
                  'value' => $this->input->post("company-name",true)
                ),
                array(
                  'settings' => 'language' ,
                  'value' => $this->input->post("language",true)
                ),
                array(
                  'settings' => 'currency_code' ,
                  'value' => $this->input->post("cur-symbol",true)
                ),
                array(
                  'settings' => 'email_address' ,
                  'value' => $this->input->post("email",true)
                ),
                array(
                  'settings' => 'address' ,
                  'value' => $this->input->post("address",true)
                ),
                array(
                  'settings' => 'phone' ,
                  'value' => $this->input->post("phone",true)
                ),
                array(
                  'settings' => 'website' ,
                  'value' => $this->input->post("website",true)
                ),
                array(
                  'settings' => 'timezone' ,
                  'value' => $this->input->post("timezone",true)
)
            );
            //-----Validation-----//   
            $this->form_validation->set_rules('company-name', 'Company Name', 'trim|required|min_length[2]|max_length[50]');
            if (!$this->form_validation->run() == FALSE)
            {
                //Update Code
                $this->db->update_batch('settings', $data, 'settings');
                echo "true";
                //Finish Update Code
            }else{
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }

        }

    }

    /** Method For upload new logo  **/
    public function uploadLogo()
    {
        checkPermission(4);
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload("logo"))
        {
            echo $this->upload->display_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $object=array();
            $object['value']=$data['upload_data']['file_name'];
            $this->db2->where('settings','logo_path');
            if($this->db2->update('settings', $object)){
                echo "true";
            }
        } 

    }

    /** Method For backup database  **/
    public function backupDatabase($action='',$table='')
    {
        checkPermission(4);
        if($action=='asyn'){
            $this->load->view('content/backup_database');
        }else if($action==''){    
            $this->load->view('theme/include/header');
            $this->load->view('content/backup_database');
            $this->load->view('theme/include/footer');
        }

        if($action=='backup'){
            $this->load->model('Datamodel');
            $this->Datamodel->backup($table);
        }
        /*
        else if($action=='delete'){
        $this->load->model('Datamodel');
        $this->Datamodel->truncate($table);
        }*/

    }

    /** Method For update profile  **/
    public function updateProfile(){
        $data=array();
        //-----Validation-----//   
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length[3]');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean|min_length[6]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|numeric');
        
        if (!$this->form_validation->run() == FALSE)
        {    
            $data['username']=$this->input->post('username',true);
            $data['nama']=$this->input->post('nama',true);
            $data['no_hp']=$this->input->post('no_hp',true);

            $id=$this->session->userdata('id_users');     
            $this->db->where('id_users', $id);
            $this->db->update('app_users', $data);
            //update session
            $this->session->set_userdata('username',$data['username']);
            $this->session->set_userdata('nama',$data['nama']);
            $this->session->set_userdata('no_hp',$data['no_hp']);

            echo "true";
                
        }else{
            //echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');    
            echo "All Field Must Required With Valid Information !";

        }  
    }

    /** Method For change password  **/ 
    public function changePassword(){
        $this->form_validation->set_rules('new-password', 'Password', 'trim|required|min_length[5]');
        if (!$this->form_validation->run() == FALSE)
        {
            $data=array();    
            $data['password']=md5($this->input->post('new-password',true)); 
            $cp=md5($this->input->post('confrim-password',true)); 
            if($data['password']!=$cp){
                echo "New Password And Confrim Password Has Not Match !";
            }else{
            //update Password
                $id=$this->session->userdata('id_users');    
                $this->db->where('id_users',$id);
                $this->db->update('app_users',$data);
                echo "true";
            }   
        }else{
            echo "The Password field must be at least 5 characters in length";
        }    

    }
   
}
