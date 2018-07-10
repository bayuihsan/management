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
        // $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Adminmodel','Bastmodel','Regionmodel','Branchmodel','Targetsalesmodel','Targetchurnmodel','Churnmodel','Ctpmodel','Msisdnmodel','Custom_fieldsmodel','Datamodel','Feedbacksmodel','Generate_tablemodel','Graparimodel','Kategoripaketmodel','Paketmodel','Reasonmodel','Reportmodel','Sales_channelmodel','Salesmodel','Salespersonmodel','Usermodel','Usersmodel'));
    }
    
	public function home($action=''){   
	   // print_r("0o");die();
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');

        $data['title'] = "Data belum diproses";
        if($sess_level !=4){
            $data['sales'] = $sales = $this->Salesmodel->getAllSalesBy($sess_level, $sess_branch);
        }

        $data['jumlah_sales'] = @count($sales);

        if($action=='asyn'){
            // print_r("asyn");die();
            $this->load->view('content/index_2', $data);
        }else{
            // print_r("view");die();
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
            $data['branch_info']=$this->Reportmodel->getBranchInfo(20,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_subchannel']=$this->Reportmodel->getTopSubSalesChannel(10,$ly,$lm,$param1);
            $data['top_tl']=$this->Reportmodel->getTopTL(10,$ly,$lm,$param1);
            $data['pie_data']=$this->Reportmodel->getContrStatus($param1);
            $data['last_login']=$this->Adminmodel->getLastLogin(10);
            $this->load->view('content/index',$data);
        }else if($action==''){
            $param1 = $tanggal->tgl_max;
            $data['cart_summery']=$this->Reportmodel->getCurMont($param1);
            $data['line_chart']=$this->Reportmodel->dayByDaySales($param1);
            $data['top_branch']=$this->Reportmodel->getTopBranch(20,$ly,$lm,$param1);
            $data['branch_info']=$this->Reportmodel->getBranchInfo(20,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_subchannel']=$this->Reportmodel->getTopSubSalesChannel(10,$ly,$lm,$param1);
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
            $data['branch_info']=$this->Reportmodel->getBranchInfo(20,$lm,$param1);
            $data['top_paket']=$this->Reportmodel->getTopPaket(10,$ly,$lm,$param1);
            $data['top_channel']=$this->Reportmodel->getTopChannel(10,$ly,$lm,$param1);
            $data['top_subchannel']=$this->Reportmodel->getTopSubSalesChannel(10,$ly,$lm,$param1);
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
    
    public function cek_msisdn($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('content/sales/cek_msisdn',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/cek_msisdn',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $cek    =$this->input->post('cek',true); 
            $this->form_validation->set_rules('cek', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            if (!$this->form_validation->run() == FALSE)
            {
                $msisdnData=$this->Salesmodel->getMSISDN($cek);
                if(empty($msisdnData)){
                    echo "false";
                }else{
                    $no=1 ;
                    foreach ($msisdnData as $row) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row->msisdn ?></td>
                            <td><?php echo $row->nama_pelanggan ?></td>
                            <td><?php echo $row->nama_branch ?></td>
                            <td><?php echo $row->tanggal_masuk ?></td>
                            <td><?php echo $row->tanggal_validasi ?></td>
                            <td><?php echo $row->tanggal_aktif ?></td>
                            <td><?php echo $row->status ?></td>
                            <td><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_msisdn_<?php echo $row->msisdn?>">View Detail</a></td>
                        </tr>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var $modal = $('#load_popup_modal_show_msisdn');
                                $('#click_to_load_modal_popup_msisdn_<?php echo $row->msisdn?>').on('click', function(){
                                    $modal.load('<?php echo base_url()?>sales/load_modal/',{'msisdn': "<?php echo $row->msisdn ?>",'id2':'2'},
                                    function(){
                                        $modal.modal('show');
                                    });

                                });
                            });

                        </script>
                    <?php 
                    
                    }  
                }
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
    
    // Controller BAST
    public function bast_view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast']=$this->Bastmodel->get_all_by('');
        }else{
            $data['bast']=$this->Bastmodel->get_all_by($sess_branch);
        } 
        if($action=='asyn'){
            $this->load->view('content/bast/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For View,insert, update and delete Bast Header  **/
    public function bast_create($action='', $param1='')
    {
        $data=array();  
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->Bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->Bastmodel->get_all_bydate($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
        }
        
        //----For ajax load-----//
        if($action=='asyn'){
            $this->load->view('content/bast/add',$data);
        }else if($action==''){  
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add',$data);
            $this->load->view('theme/include/footer');
        }
        
        //----End Page Load------//
        //----For Insert update and delete-----//
        if($action=='insert'){
            $data=array();
            $do=$this->input->post('action',true);     
            $data['no_bast']=$no_bast=$this->input->post('bno_bast',true); 
            $data['branch_id']=$this->input->post('bbranch',true); 
            $data['tanggal_masuk']=$this->input->post('btanggal_masuk',true);  
            $data['id_users']=$this->session->userdata('id_users');  
            //-----Validation-----//    
            if($data['no_bast']!="" && $data['branch_id']!="" && $data['tanggal_masuk']!=""){
                if($do=='insert'){
                    //Check Duplicate Entry    
                    if(!value_exists("bast_header","no_bast",$data['no_bast'])){
                        if($sess_level == 4){
                            $jml_bast=$this->Bastmodel->get_all_bydate('');
                        }else{
                            $jml_bast=$this->Bastmodel->get_all_bydate($sess_branch);
                        }

                        if(count($jml_bast)>=2){
                            echo '{"result":"false", "message":"Pembuatan BAST maksimal 2 kali dalam sehari.!!"}';;
                        }else{
                            if($this->db->insert('bast_header',$data)){
                                $last_id=$this->db->insert_id();    
                                echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
                            }
                        }
                    }else{
                        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
                    }
                }else if($do=='update'){
                    $id=$this->input->post('id_header',true); 
                    $no_bast2=$this->input->post('bno_bast2',true);
                    if($no_bast2 == ""){
                        $data['no_bast'] = $no_bast;
                    }else{
                        $data['no_bast'] = $no_bast2;
                    }
                    //Check Duplicate Entry  
                    if(!value_exists("bast_header","no_bast",$no_bast2)){ 
                        $this->db->where('id_header', $id);
                        $this->db->update('bast_header', $data);
                        echo '{"result":"true","action":"update"}'; 
                    }else{
                        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
                    }      
                }    
            }else{
                echo '{"result":"false", "message":"All Field Must Required With Valid Length !"}';
            }
            //----End validation----//
                
        }else if($action=='remove'){    
            $this->db->delete('bast_header', array('id_header' => $param1));        
        }else if($action=='receive'){    
            $datareceive['tanggal_terima'] = date('Y-m-d h:i:s');
            $this->db->where('id_header', $param1);
            $this->db->update('bast_header', $datareceive);     
        } 
    }
    /** Method For Add New Account and Account Page View **/    
    public function bast_add($no_bast='',$action='',$param1='')
    {
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->Usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['grapari']=$this->Graparimodel->get_all();
            $datax['validasi']=$this->Usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->Usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->Graparimodel->get_all_by($sess_branch);
            $datax['validasi']=$this->Usersmodel->get_all_validasi_by($sess_branch);
        }
        
        $datax['paket']=$this->Paketmodel->get_all();
        $qbast = $this->Bastmodel->get_all_by_no_bast_row($no_bast);
        if(empty($qbast)){
            redirect('Admin/home');
        }
        $datax['no_bast'] = $qbast->no_bast;
        $datax['tanggal_masuk'] = $qbast->tanggal_masuk;
        if($action=='asyn'){
            $this->load->view('content/bast/add_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add_sales',$datax);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['paket_id']              =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             ="masuk";
        
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));

            $data['tanggal_input']      = date('Y-m-d h:i:s');   
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');

            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');

            if($do == "update"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='insert'){
                    
                    if(value_exists("new_psb","msisdn",$msisdn)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{
                        $user_tl = $this->Usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $this->db->insert('new_psb',$data);

                        $datastatus['status']             ="masuk";
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                     
                }else if($do=='update'){
                    
                    if($msisdn1 == "" || $msisdn1 == null){
                        $data['msisdn'] = $msisdn;
                    }else{
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{

                        $user_tl = $this->Usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

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
            // $datastatus['status']             ="masuk";
            // $this->db->where('msisdn', $msisdn);
            // $this->db->update('msisdn', $datastatus);

            $this->db->delete('new_psb', array('psb_id' => $param1));       
        }
    }

    //Cek MSISDN// 
    public function cek_bast($action='', $param1='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('content/bast/cek_bast',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/cek_bast',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='receive'){    
            $datareceive['tanggal_terima'] = date('Y-m-d h:i:s');
            $datareceive['id_penerima'] = $this->session->userdata('id_users');
            $this->db->where('id_header', $param1);
            $this->db->update('bast_header', $datareceive);    
            echo "terima"; 
        } else if($action=='view'){
            $cek    =addslashes(trim($this->input->post('cek',true))); 
            $this->form_validation->set_rules('cek', 'MSISDN', 'trim|required|xss_clean');
            if (!$this->form_validation->run() == FALSE)
            {
                $bastData=$this->Bastmodel->get_all_by_no_bast($cek);
                if(empty($bastData)){
                    echo "false";
                }else{
                    $no=1 ;
                    foreach ($bastData as $row) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row->no_bast ?></td>
                            <td><?php echo $row->nama_branch ?></td>
                            <td><?php echo $row->tanggal_masuk ?></td>
                            <td><?php echo $row->tanggal_terima ?></td>
                            <td><?php if(!empty($row->tanggal_terima)){
                                $tgl_terima = new DateTime($row->tanggal_terima);
                            }else{
                                $tgl_terima = new DateTime();
                            }
                            $tgl_masuk = new DateTime($row->tanggal_masuk);
                            $diff = $tgl_terima->diff($tgl_masuk); echo $diff->d." Hari"; ?></td>
                            <td><?php echo $row->nama ?></td>
                            <td><?php echo $row->nama_penerima ?></td>
                            <td><?php echo $row->jumlah." MSISDN" ?></td>
                            <td>
                            <?php 
                            if($this->session->userdata('branch_id')==$row->branch_id && ($this->session->userdata('level')==4 || $this->session->userdata('level')==5)){ ?>
                            <a class="mybtn btn-default btn-xs" style="cursor: pointer;" data-toggle="tooltip" title="Click For Add MSISDN" href="<?php echo site_url('bast/add/'.$row->no_bast) ?>">Tambah</a>
                            <?php } 
                            if($this->session->userdata('branch_id')==$row->branch_id && empty($row->tanggal_terima) && ($this->session->userdata('level')>5 || $this->session->userdata('level')==4)){ ?>
                                <input type="hidden" name="xno_bast" id="xno_bast" value="<?php echo $row->no_bast?>">
                                <a class="mybtn btn-success btn-xs bast-terima-btn" style="cursor: pointer;" data-toggle="tooltip" title="Click For Receive" href="<?php echo site_url('bast/cek_bast/receive/'.$row->id_header) ?>">Terima</a>
                            <?php } ?>
                            <a href="<?php echo site_url('bast/detail').'/'.$row->no_bast; ?>" data-toggle="tooltip" title="Click For Detail BAST" class="mybtn btn-info btn-xs">Detail</a></td>
                        </tr>
                    <?php 
                    
                    }  
                }
            }else{
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
        }

    }

    function bast_load_modal()
    {
        $data['no_bast'] = $no_bast = $this->input->post('no_bast',true);

        $data['detail_bast']=$this->Salesmodel->getBASTDetail($no_bast);
        $this->load->view('content/bast/load_modal_bast',$data);
    }

    /** Method For get sales information for sales Edit **/ 
    public function bast_edit($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->Usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->Usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->Usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
        }

        $datax['edit_sales']=$this->Salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        if($action=='asyn'){
            $this->load->view('content/bast/add_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add_sales',$datax);
            $this->load->view('theme/include/footer');
        }    
    }

    /** Method For get sales information for sales Edit **/ 
    public function bast_validasi($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $datax['reason']=$this->Reasonmodel->get_all(); 
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['grapari']=$this->Graparimodel->get_all();
            $datax['tl']=$this->Usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->Graparimodel->get_all_by($sess_branch);
            $datax['tl']=$this->Usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
        }

        $datax['edit_sales']=$this->Salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        $datax['psb_id'] = $psb_id;
        if($action=='asyn'){
            $this->load->view('content/bast/validasi_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/validasi_sales',$datax);
            $this->load->view('theme/include/footer');
        }

        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['id_grapari']          =addslashes($this->input->post('sgrapari',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['tanggal_validasi']   =$tanggal_validasi = addslashes(date('Y-m-d h:i:s'));


            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['paket_id']           =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             =$status = addslashes($this->input->post('sstatus',true));
            $data['deskripsi']          =addslashes($this->input->post('id_reason',true));
            $data['validasi_by']        =addslashes($this->session->userdata('username'));
        
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_reason', 'Keterangan', 'trim|required|xss_clean');

            $this->form_validation->set_rules('sno_bast', 'No BAST', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sgrapari', 'Grapari', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_validasi', 'Tanggal Validasi', 'trim|required|xss_clean');

            if($do == "validasi"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='validasi'){
                    
                    if($msisdn1 == "" || $msisdn1 == null){
                        $data['msisdn'] = $msisdn;
                    }else{
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{

                        $user_tl = $this->Usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        $datastatus['status']             =$status;
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        } 
    }

    /** Method For get sales information for sales Edit **/ 
    public function bast_aktivasi($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $datax['reason']=$this->Reasonmodel->get_all(); 
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['grapari']=$this->Graparimodel->get_all();
            $datax['tl']=$this->Usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->Usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->Graparimodel->get_all_by($sess_branch);
            $datax['tl']=$this->Usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
            $datax['validasi']=$this->Usersmodel->get_all_validasi_by($sess_branch);
        }

        $datax['edit_sales']=$this->Salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        $datax['psb_id'] = $psb_id;
        if($action=='asyn'){
            $this->load->view('content/bast/aktivasi_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/aktivasi_sales',$datax);
            $this->load->view('theme/include/footer');
        }

        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['id_grapari']          =addslashes($this->input->post('sgrapari',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['tanggal_validasi']   =$tanggal_validasi = addslashes($this->input->post('stanggal_validasi',true));
            $data['tanggal_aktif']      =$tanggal_aktif = addslashes(date('Y-m-d h:i:s'));


            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['paket_id']           =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['bill_cycle']         =addslashes($this->input->post('sbill_cycle',true));
            $data['fa_id']              = $fa_id = addslashes($this->input->post('sfa_id',true));
            $data['account_id']         = $account_id =addslashes($this->input->post('saccount_id',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             =$status = addslashes($this->input->post('sstatus',true));
            $data['deskripsi']          =addslashes($this->input->post('id_reason',true));
            $data['validasi_by']        =addslashes($this->input->post('svalidasi_by',true));
            $data['username']           =addslashes($this->input->post('susername',true));
        
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbill_cycle', 'Bill Cycle', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sfa_id', 'FA ID', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('saccount_id', 'Account ID', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_reason', 'Keterangan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('svalidasi_by', 'Validasi By', 'trim|required|xss_clean');

            $this->form_validation->set_rules('sno_bast', 'No BAST', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sgrapari', 'Grapari', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_validasi', 'Tanggal Validasi', 'trim|required|xss_clean');

            if($do == "aktivasi"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='aktivasi'){
                    $account = $this->Salesmodel->get_sales_by_account($account_id); 
                    $fa = $this->Salesmodel->get_sales_by_fa($fa_id); 
                    if($msisdn1 == "" || $msisdn1 == null){
                        $msisdn = $msisdn;
                        $data['msisdn'] = $msisdn;
                    }else{
                        $msisdn = $msisdn1;
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else if($account->jumlah>10){

                        echo "Account ID sudah digunakan untuk 10 MSISDN";

                    }else if($fa->jumlah>10){

                        echo "FA ID sudah digunakan untuk 10 MSISDN";

                    }else{

                        $user_tl = $this->Usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        $datastatus['status']             =$status;
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        } 
    }

    /** Method For get sales information for sales Edit **/ 
    public function bast_edits($id_header,$action='')
    {
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->Bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->Bastmodel->get_all_bydate($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
        }

        $data['edit_bast']=$edit_bast=$this->Bastmodel->get_all_by_id($id_header); 
        if(empty($edit_bast)){
            redirect('Admin/home');
        }

        if($action=='asyn'){
            $this->load->view('content/bast/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function bast_detail($no_bast='', $action='')
    {   
        $data=array();
        $data['edit_bast']=$edit_bast=$this->Bastmodel->get_all_by_no_bast_row($no_bast); 
        if(empty($edit_bast)){
            redirect('Admin/home');
        }
        $data['detail_bast']=$this->Salesmodel->getBASTDetail($no_bast);
        $data['cno_bast']=$no_bast;
        if($action=='asyn'){
            $this->load->view('content/bast/list_detail',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/list_detail',$data);
            $this->load->view('theme/include/footer');
        }
    }

    public function bast_export($no_bast='', $action=''){
        if($action=='asyn'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);
            //Array Hari
            $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Ahad");
            $hari = $array_hari[date("N")];

            //Format Tanggal
            $tanggal = date ("j");
            
            //Array Bulan
            $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
            $bulan = $array_bulan[date("n")];

            //Format Tahun
            $tahun = date("Y");

            $data['edit_bast']=$edit_bast=$this->Bastmodel->get_all_by_no_bast_row($no_bast); 
            // if(empty($edit_bast)){
            //     redirect('Admin/home');
            // }

            $data['detail_bast']=$detail=$this->Salesmodel->getBASTDetail($no_bast);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "BERITA ACARA SERAH TERIMA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "PT. TELEKOMUNIKASI SELULAR");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 4, "-------------------------------------------------------");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NOMOR");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, $no_bast);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 6, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 7, "Pada hari ini ".strtoupper($hari)." tanggal ".$tanggal." bulan ".strtoupper($bulan)." tahun ".$tahun.", telah diserahkan data dari : ");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 8, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 9, "Pihak I");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 10, "Nama");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 10, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 10, strtoupper($edit_bast->nama));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 11, "Jabatan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 11, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 11, "FOS HVC ".strtoupper($edit_bast->nama_branch));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 12, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 13, "Telah diserah terimakan Formulir PSB dengan kelengkapan dokumen Foto Copy KTP, MATERAI, KETERANGAN DUKCAPIL, sebanyak ".count($detail)." DATA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 14, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 15, "KEPADA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 16, "Pihak II");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 17, "Nama");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 17, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 17, strtoupper($edit_bast->nama_penerima));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 18, "Jabatan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 18, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 18, "FOS GRAPARI ".strtoupper($edit_bast->nama_branch));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 19, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 20, "Dengan telah ditandatanganinya Berita Acara Serah Terima data ini, maka selanjutnya data yang telah diserahkan akan di proses sesuai ketentuan berlaku.");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 21, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 22, "Demikian Berita Acara Serah Terima data ini dibuat dan untuk dapat dipergunakan sebagaimana mestinya.");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 23, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 24, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 25, "Yang Menyerahkan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, 25, "Yang Menerima");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 26, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 27, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 28, "");
            if(!empty($edit_bast->nama)){ 
                $var_menyerahkan = strtoupper($edit_bast->nama); 
            } else { 
                $var_menyerahkan = "..............."; 
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 29, $var_menyerahkan);
            if(!empty($edit_bast->nama_penerima)){ 
                $var_menerima = strtoupper($edit_bast->nama_penerima); 
            } else { 
                $var_menerima = "..............."; 
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, 29, $var_menerima);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 30, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 31, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 32, "Lampiran");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 33, "");

            $table_columns = array("NO", "PSB_ID", "MSISDN", "NAMA_PELANGGAN", "PAKET", "NO_HP", "SALES_PERSON", "TL", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "SERVICE (HARI)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 34, $field);
                $column++;
            }

            $excel_row = 35;
            $no = 1;
            foreach($detail as $row)
            {
                if(!empty($row->tanggal_aktif)){
                    $tgl_aktif = new DateTime($row->tanggal_aktif);
                }else{
                    $tgl_aktif = new DateTime();
                }
                $tgl_masuk = new DateTime($row->tanggal_masuk);
                $diff = $tgl_aktif->diff($tgl_masuk); 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->no_hp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->nama_tl));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($diff->d));
                $excel_row++;
            }

            $filename = "BAST-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller BAST
    
    //controller region
    public function region_view($action='')
    {   
        $data=array();
        $data['region']=$this->Regionmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/region/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/region/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For Add New region and Region Page View **/    
    public function region_add($action='',$param1='')
    {
        if($action=='asyn'){
            $this->load->view('content/region/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/region/add');
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama_region']    =addslashes($this->input->post('nama_region',true)); 
            $data['status_region']         =addslashes($this->input->post('status_region',true));  
            $data['update_by']      =addslashes($this->input->post('update_by',true));  
        
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_region', 'Nama Region', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('status_region', 'Status Region', 'trim|required|xss_clean');
            $this->form_validation->set_rules('update_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('region',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_region',true));
                    
                    $this->db->where('id_region', $id);
                    $this->db->update('region', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('region', array('id_region' => $param1));       
        }
    }

    /** Method For get region information for region Edit **/ 
    public function region_edit($id_region,$action='')
    {
        $data=array();
        $data['edit_region']=$edit_region=getOld("id_region",$id_region,"region"); 
        if(empty($edit_region)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/region/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/region/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function region_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA_REGION", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $region = $this->Regionmodel->get_all();

            $excel_row = 2;

            foreach($region as $row)
            {
                if($row->status_region == 1){
                    $status = "Aktif";
                }else{
                    $status = "Tidak Aktif";
                }
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_region));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_region));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "Region-sExported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller region

    //controller branch
    public function branch_view($action='')
    {   
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/branch/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New branch and Branch Page View **/    
    public function branch_add($action='',$param1='')
    {
        $data['region']=$this->Regionmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/branch/add', $data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/add', $data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['id_region']      =addslashes($this->input->post('id_region',true)); 
            $data['nama_branch']    =addslashes($this->input->post('nama_branch_b',true)); 
            $data['ketua']          =addslashes($this->input->post('ketua',true)); 
            $data['status']         =addslashes($this->input->post('status',true));  
            $data['update_by']      =addslashes($this->input->post('update_by',true));  
       
            //-----Validation-----//  
            $this->form_validation->set_rules('id_region', 'Region', 'trim|required|xss_clean'); 
            $this->form_validation->set_rules('nama_branch_b', 'Nama Branch', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('ketua', 'Ketua', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('update_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('branch',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_branch',true));
                    
                    $this->db->where('branch_id', $id);
                    $this->db->update('branch', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('branch', array('branch_id' => $param1));       
        }
    }

    /** Method For get branch information for Branch Edit **/ 
    public function branch_edits($branch_id,$action='')
    {
        $data=array();
        $data['region']=$this->Regionmodel->get_all(); 
        $data['edit_branch']=$edit_branch=getOld("branch_id",$branch_id,"branch"); 
        if(empty($edit_branch)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/branch/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/branch/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function branch_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA_BRANCH", "KETUA", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $branch = $this->Branchmodel->get_all();

            $excel_row = 2;

            foreach($branch as $row)
            {
                if($row->status == 1){
                    $status = "Aktif";
                }else{
                    $status = "Tidak Aktif";
                }
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->branch_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->ketua));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->time_update));
                $excel_row++;
            }

            $filename = "Branch-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller branch

    //controller ctp
    public function ctp_view($action='')
    {   
        $data=array();
        $data['ctp']=$this->Ctpmodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/ctp/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/ctp/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function ctp_add($action='',$param1='')
    {
        $data['paket']=$this->Paketmodel->get_all(); 
        $data['branch']=$this->Branchmodel->get_all(); 
        $data['users']=$this->Usersmodel->get_all_ctp(); 
        if($action=='asyn'){
            $this->load->view('content/ctp/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/ctp/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                             =addslashes($this->input->post('action',true));
            $data['order_id']               =addslashes($this->input->post('order_id',true)); 
            $data['order_submit_date']      =addslashes($this->input->post('order_submit_date',true)); 
            $data['order_completed_date']   =addslashes($this->input->post('order_completed_date',true)); 
            $data['msisdn_ctp']             =addslashes($this->input->post('msisdn_ctp',true));  
            $data['nama_pelanggan_ctp']     =addslashes($this->input->post('nama_pelanggan_ctp',true));  
            $data['order_type_name']        =addslashes($this->input->post('order_type_name',true)); 
            $data['user_id']                =addslashes($this->input->post('user_id',true)); 
            $data['employee_name']          =addslashes($this->input->post('employee_name',true)); 
            $data['paket_id']               =addslashes($this->input->post('paket_id',true)); 
            $data['id_users']               =addslashes($this->input->post('id_users',true)); 
            $data['branch_id']             =addslashes($this->input->post('cbranch_id',true)); 
            $data['tgl_upload']             =addslashes($this->input->post('tgl_upload',true)); 
       
            //-----Validation-----//   
            $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_submit_date', 'Order Submit Date', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('order_completed_date', 'Order Complete Date', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('msisdn_ctp', 'MSISDN', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('nama_pelanggan_ctp', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('order_type_name', 'Order Type', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('employee_name', 'Employee Name', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('paket_id', 'Paket', 'trim|required|xss_clean|min_length[1]');
            $this->form_validation->set_rules('id_users', 'Admin CTP', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('cbranch_id', 'Branch', 'trim|required|xss_clean|min_length[1]');
            $this->form_validation->set_rules('tgl_upload', 'Tgl Upload', 'trim|required|xss_clean|min_length[3]');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('ctp',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_ctp',true));
                    
                    $this->db->where('id_ctp', $id);
                    $this->db->update('ctp', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('ctp', array('id_ctp' => $param1));       
        }
    }

    /** Method For get paket information for Branch Edit **/ 
    public function ctp_edits($id_ctp,$action='')
    {
        $data=array();
        $data['edit_ctp']=$edit_ctp=$this->Ctpmodel->get_ctp_by_id($id_ctp);
        if(empty($edit_ctp)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/ctp/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/ctp/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    public function ctp_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ORDER_ID", "ORDER_SUBMIT_DATE", "ORDER_COMPLETED_DATE", "NAMA_PELANGGAN", "USER_ID","EMPLOYEE_NAME","NAMA_PAKET");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ctp = $this->Ctpmodel->get_all();

            $excel_row = 2;

            foreach($ctp as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->order_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->order_submit_date));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->order_completed_date));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_pelanggan_ctp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->user_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->employee_name));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->nama_paket));
                $excel_row++;
            }

            $filename = "CTP-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller ctp

    //controller custom_fields
        public function customfields_add($action='',$param1='')
    {
        checkPermission(4);
        $data['custom_fields']=$this->Custom_fieldsmodel->get_all();
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
            $this->form_validation->set_rules('input_query', 'Query', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('id_users', 'Silahkan Login. ID User', 'trim|required|xss_clean|numeric');


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
    public function customfields_edit($custom_fields_id,$action='')
    {
        checkPermission(4);
        $data=array();
        $data['edit_custom_fields']=$this->Custom_fieldsmodel->get_custom_fields_by_id($custom_fields_id); 
        if($action=='asyn'){
            $this->load->view('content/custom_fields/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/custom_fields/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    //end controller custom_fields

    //controller feedback
        public function feedback_view($action='')
    {   
        $data=array();
        $data['feedbacks']=$this->Feedbacksmodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/feedbacks/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/feedbacks/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function feedback_add($action='',$param1='')
    {
        $data['feedbacks']=$this->Feedbacksmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/feedbacks/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/feedbacks/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['no_hp']          =addslashes($this->input->post('no_hp_f',true)); 
            $data['nama_pelanggan'] =addslashes($this->input->post('nama_pelanggan',true)); 
            $data['kota']           =addslashes($this->input->post('kota',true)); 
            $data['saran']          =addslashes($this->input->post('saran',true));  
            $data['id_users']       =addslashes($this->input->post('id_users',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('no_hp_f', 'No HP', 'trim|required|xss_clean|min_length[10]|numeric');
            $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('kota', 'Kota', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('saran', 'Saran', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('id_users', 'ID Users', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('feedback',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_feedback',true));
                    
                    $this->db->where('id_feedback', $id);
                    $this->db->update('feedback', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('feedback', array('id_feedback' => $param1));       
        }
    }

    /** Method For get paket information for Branch Edit **/ 
    public function feedback_edits($id_feedback,$action='')
    {
        $data=array();
        $data['edit_feedbacks']=$edit_feedbacks=getOld("id_feedback",$id_feedback,"feedback");
        if(empty($edit_feedbacks)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/feedbacks/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/feedbacks/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   

    public function feedback_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NO_HP", "NAMA_PELANGGAN", "KOTA", "SARAN", "USERS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $feedbacks = $this->Feedbacksmodel->get_all();

            $excel_row = 2;

            foreach($feedbacks as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_feedback));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->no_hp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->kota));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->saran));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->tgl_update));
                $excel_row++;
            }

            $filename = "Feedback-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller feedback

    //controller generate_table
        public function generatetable_view($action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
    {   
        $data=array();
        if($action=='asyn'){
            $data['generate_table']=$this->Generate_tablemodel->get_all(); 
            $this->load->view('content/generate_table/list',$data);
        }else if($action==''){
            $data['generate_table']=$this->Generate_tablemodel->get_all(); 
            $this->load->view('theme/include/header');
            $this->load->view('content/generate_table/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function generatetable_add($action='',$param1='')
    {
        if($action=='asyn'){
            $this->load->view('content/generate_table/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/generate_table/add');
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['bulan']          = $bulan = addslashes($this->input->post('bulan',true)); 
            $data['tahun']          = $tahun = addslashes($this->input->post('tahun',true));  
            $data['status']         =addslashes($this->input->post('status',true));  
            $data['id_users']       =addslashes($this->input->post('input_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('bulan', 'Bulan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('input_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 
                    $qgenerate = $this->db->query("select * from new_psb_temp where bulan='".$bulan."' and tahun='".$tahun."'")->result();
                    if(count($qgenerate) > 0){
                        echo "This Tabel Is Already Exists.!!";
                    }else{
                        if($bulan < 10){
                            $bulan = '0'.$bulan;
                        }else{
                            $bulan = $bulan;
                        }
                        $data['nama_table'] = $nama_table = "sales_psb_".$bulan."_".$tahun;

                        $this->db->insert('new_psb_temp',$data); 

                        $this->db->query("CREATE TABLE ".$nama_table." AS SELECT * 
                            FROM new_psb 
                            WHERE status='sukses' and DATE_FORMAT(tanggal_aktif,'%Y')='".$tahun."' and DATE_FORMAT(tanggal_aktif,'%m')='".$bulan."'
                            ");
                        
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
            $qgenerate = $this->Generate_tablemodel->get_generate_by_id($param1);
            $nama_table = $qgenerate->nama_table;    
            if(!empty($nama_table)){
                $this->db->delete('new_psb_temp', array('id_temp' => $param1));  
                $this->db->query("DROP TABLE ".$nama_table);
            }
                
        }
    }

    /** Method For get generate_table information for generate_table Edit **/ 
    public function generatetable_load_table($id_temp,$action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');

        $qgenerate = $this->Generate_tablemodel->get_generate_by_id($id_temp); 
        $nama_table = $qgenerate->nama_table;
        $data['nama_table'] = $nama_table;
        $data['id_temp'] = $id_temp;
        $data['data_list'] = $this->Generate_tablemodel->get_all_table($nama_table);
        if($this->session->userdata('level')==4){
            $data['branch'] = $this->Branchmodel->get_all();
        }else{
            $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

        }
        if($action=='asyn'){
            $this->load->view('content/generate_table/list_table',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/generate_table/list_table',$data);
            $this->load->view('theme/include/footer');
        }else if($action == 'cari'){        
            // $reportData=$this->Reportmodel->getSalesCari($account,$from_date,$to_date,$trans_type);
            $data['bbranch_id'] = $branch_id;
            $data['btgl'] = $tgl;
            $data['bstatus'] = $status;
            $data['bfrom_date'] = $from_date;
            $data['bto_date'] = $to_date;
            if($branch_id == 17){
                $data['data_list'] = $cari = $this->Generate_tablemodel->get_all_cari_all($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }else{

                $data['data_list'] = $cari = $this->Generate_tablemodel->get_all_cari($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }

            $this->load->view('content/generate_table/list_table',$data);
        } 
    }

    public function generatetable_export($id_temp='',$action="",$branch_id='',$tgl='',$status='',$from_date='',$to_date=''){
        if($action=="asyn"){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "MSISDN", "NAMA_PELANGGAN", "BRANCH", "PAKET", "TL", "SALES_PERSON", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "FA_ID", "ACCOUNT_ID", "ALAMAT", "ALAMAT2", "DISCOUNT (RB)", "PERIODE (BULAN)", "BILL CYCLE", "CHANNEL", "SUB SALES CHANNEL", "JENIS EVENT", "NAMA EVENT", "VALIDATOR", "AKTIVATOR", "STATUS", "KETERANGAN", "TANGGAL_INPUT (SYSTEM)", "TANGGAL_UPDATE (SYSTEM)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $qgenerate = $this->Generate_tablemodel->get_generate_by_id($id_temp); 
            $nama_table = $qgenerate->nama_table;

            if($branch_id == 17){
                $generate_table = $this->Generate_tablemodel->get_all_cari_all($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }else{
                $generate_table = $this->Generate_tablemodel->get_all_cari($nama_table,$branch_id,$tgl,$status,$from_date,$to_date);
            }

            $excel_row = 2;

            foreach($generate_table as $row)
            {
                // if(!empty($row->tanggal_aktif)){
                //     $tgl_aktif = new DateTime($row->tanggal_aktif);
                // }else{
                //     $tgl_aktif = new DateTime();
                // }
                // $tgl_masuk = new DateTime($row->tanggal_masuk);
                // $diff = $tgl_aktif->diff($tgl_masuk); 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->TL));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                // $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($diff->d));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($row->fa_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($row->account_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, strtoupper($row->alamat));
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, strtoupper($row->alamat2));
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, strtoupper($discount[$row->discount]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, strtoupper($periode[$row->periode]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, strtoupper($row->bill_cycle));
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, strtoupper($channel[$row->sales_channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, strtoupper($jenis_event[$row->jenis_event]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, strtoupper($row->nama_event));
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, strtoupper($row->validator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, strtoupper($row->aktivator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, strtoupper($row->deskripsi));
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, strtoupper($row->tanggal_input));
                $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "generate_table-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller generate_table

    //controller churn
    public function churn_view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['churn']=$this->Churnmodel->get_all(); 
        }else{
            $data['churn']=$this->Churnmodel->get_all_by($sess_branch); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/churn/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/churn/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For Add New Churn and CHurn Page View **/    
    public function churn_add($action='',$param1='')
    {
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/churn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/churn/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['branch_id']      = $branch_id = addslashes($this->input->post('branch_id_c',true)); 
            $data['tanggal_churn']  = $tanggal_churn = addslashes(date('Y-m-d', strtotime($this->input->post('tanggal_churn',true)))); 
            $data['nilai_churn']    =addslashes($this->input->post('nilai_churn',true)); 
            $data['updated_by']     =addslashes($this->input->post('updated_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('branch_id_c', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('tanggal_churn', 'Tanggal churn', 'trim|required|xss_clean');
            $this->form_validation->set_rules('nilai_churn', 'Nilai churn', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('updated_by', 'Updated by', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                $cek = $this->Churnmodel->cek_churn($branch_id, $tanggal_churn);

                if($do=='insert'){ 
                    if($cek>0){
                        echo "exist";
                    }else{
                        $this->db->insert('churn',$data); 
                    
                        echo "true"; 
                    }
                }else if($do=='update'){
                    $id=$this->input->post('id_churn',true);
                    
                    $this->db->where('id_churn', $id);
                    $this->db->update('churn', $data);

                    echo "true";

                }         
                    
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('churn', array('id_churn' => $param1));       
        }
    }

    /** Method For get churn information for churn Edit **/ 
    public function churn_edits($id_churn,$action='')
    {
        $data=array();
        $data['edit_churn']=getOld("id_churn",$id_churn,"churn");
        $data['branch']=$this->Branchmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/churn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/churn/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function churn_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "BRANCH", "TANGGAL CHURN", "NILAI CHURN", "UPDATED BY", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $data_churn = $this->Churnmodel->get_all();

            }else{
                $data_churn = $this->Churnmodel->get_all_by($sess_branch);
            }
            
            $excel_row = 2;
            foreach($data_churn as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_churn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->tanggal_churn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nilai_churn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->tanggal_update);
                $excel_row++;
            }

            $filename = "Churn-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller churn

    //controller Target Sales
    public function target_view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['target']=$this->Targetsalesmodel->get_all(); 
        }else{
            $data['target']=$this->Targetsalesmodel->get_all_by($sess_branch); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/target_sales/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_sales/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For Add New Target and Target Page View **/    
    public function target_add($action='',$param1='')
    {
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/target_sales/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_sales/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['branch_id']      = $branch_id = addslashes($this->input->post('branch_id_t',true)); 
            $data['tahun_target']   = $tahun = addslashes($this->input->post('tahun_target',true)); 
            $data['bulan_target']   = $bulan = addslashes($this->input->post('bulan_target',true)); 
            $data['nilai_target']   =addslashes($this->input->post('nilai_target',true)); 
            $data['updated_by']     =addslashes($this->input->post('updated_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('branch_id_t', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('tahun_target', 'Tahun', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('bulan_target', 'Bulan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('nilai_target', 'Nilai target', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('updated_by', 'Updated by', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                $cek = $this->Targetsalesmodel->cek_target($branch_id, $tahun, $bulan);

                if($do=='insert'){ 
                    if($cek>0){
                        echo "exist";
                    }else{
                        $this->db->insert('target_sales',$data); 
                    
                        echo "true"; 
                    }
                }else if($do=='update'){
                    $id=$this->input->post('id_target',true);
                    
                    $this->db->where('id_target', $id);
                    $this->db->update('target_sales', $data);

                    echo "true";

                }         
                    
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('target_sales', array('id_target' => $param1));       
        }
    }

    /** Method For get target information for target Edit **/ 
    public function target_edits($id_target,$action='')
    {
        $data=array();
        $data['edit_target']=getOld("id_target",$id_target,"target_sales");
        $data['branch']=$this->Branchmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/target_sales/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_sales/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function target_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $namaBulan = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des");
            $table_columns = array("ID", "BRANCH", "BULAN", "NILAI TARGET", "UPDATED BY", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $data_target = $this->Targetsalesmodel->get_all();

            }else{
                $data_target = $this->Targetsalesmodel->get_all_by($sess_branch);
            }
            
            $excel_row = 2;
            foreach($data_target as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_target));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $namaBulan[$row->bulan_target]."-".$row->tahun_target);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nilai_target));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->tanggal_update);
                $excel_row++;
            }

            $filename = "Target-Sales-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller target sales

    //controller Target CHurn
    public function target_churn_view($action='')
    {   
        $data=array();
        $sess_region = $this->session->userdata('id_region');
        if($this->session->userdata('level')==4){
            $data['target_churn']=$this->Targetchurnmodel->get_all(); 
        }else{
            $data['target_churn']=$this->Targetchurnmodel->get_all_by($sess_region); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/target_churn/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_churn/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For Add New Target and Target Page View **/    
    public function target_churn_add($action='',$param1='')
    {
        $data['region']=$this->Regionmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/target_churn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_churn/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['id_region']      = $id_region = addslashes($this->input->post('id_region_t',true)); 
            $data['tahun_target_churn']   = $tahun = addslashes($this->input->post('tahun_target_churn',true)); 
            $data['bulan_target_churn']   = $bulan = addslashes($this->input->post('bulan_target_churn',true)); 
            $data['nilai_target_churn']   =addslashes($this->input->post('nilai_target_churn',true)); 
            $data['updated_by']     =addslashes($this->input->post('updated_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('id_region_t', 'Region', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('tahun_target_churn', 'Tahun', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('bulan_target_churn', 'Bulan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('nilai_target_churn', 'Nilai target', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('updated_by', 'Updated by', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                $cek = $this->Targetchurnmodel->cek_target($id_region, $tahun, $bulan);

                if($do=='insert'){ 
                    if($cek>0){
                        echo "exist";
                    }else{
                        $this->db->insert('target_churn',$data); 
                    
                        echo "true"; 
                    }
                }else if($do=='update'){
                    $id=$this->input->post('id_target_churn',true);
                    
                    $this->db->where('id_target_churn', $id);
                    $this->db->update('target_churn', $data);

                    echo "true";

                }         
                    
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('target_churn', array('id_target_churn' => $param1));       
        }
    }

    /** Method For get target churn information for target churn Edit **/ 
    public function target_churn_edits($id_target,$action='')
    {
        $data=array();
        $data['edit_target_churn']=getOld("id_target_churn",$id_target,"target_churn");
        $data['region']=$this->Regionmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/target_churn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/target_churn/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function target_churn_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $namaBulan = array("","Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des");
            $table_columns = array("ID", "REGION", "BULAN", "NILAI TARGET", "UPDATED BY", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_region = $this->session->userdata('id_region');
            if($this->session->userdata('level')==4){
                $data_target = $this->Targetchurnmodel->get_all();

            }else{
                $data_target = $this->Targetchurnmodel->get_all_by($sess_region);
            }
            
            $excel_row = 2;
            foreach($data_target as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_target_churn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_region));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $namaBulan[$row->bulan_target_churn]."-".$row->tahun_target_churn);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nilai_target_churn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->tanggal_update);
                $excel_row++;
            }

            $filename = "Target-Churn-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller target churn

    //controller grapari
    public function grapari_view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['grapari']=$this->Graparimodel->get_all(); 
        }else{
            $data['grapari']=$this->Graparimodel->get_all_by($sess_branch); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/grapari/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/grapari/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function grapari_add($action='',$param1='')
    {
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/grapari/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/grapari/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['nama_grapari']    =addslashes($this->input->post('nama_grapari',true)); 
            $data['username']       =addslashes($this->input->post('username',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('nama_grapari', 'Nama Grapari', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('grapari',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_grapari',true);
                    
                    $this->db->where('id_grapari', $id);
                    $this->db->update('grapari', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('grapari', array('id_grapari' => $param1));       
        }
    }

    /** Method For get grapari information for grapari Edit **/ 
    public function grapari_edits($grapari_id,$action='')
    {
        $data=array();
        $data['edit_grapari']=getOld("id_grapari",$grapari_id,"grapari");
        $data['branch']=$this->Branchmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/grapari/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/grapari/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function grapari_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "BRANCH", "NAMA GRAPARI", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $data_grapari = $this->Graparimodel->get_all();

            }else{
                $data_grapari = $this->Graparimodel->get_all_by($sess_branch);
            }
            
            $excel_row = 2;
            foreach($data_grapari as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_grapari));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_grapari));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->tgl_update);
                $excel_row++;
            }

            $filename = "Grapari-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller grapari

    //controller kategori_paket
        public function kategoripaket_view($action='')
    {
        checkPermission(4);
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
    public function kategoripaket_add($action='',$param1='')
    {
        checkPermission(4);
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
            $data['nama_kategori']  =addslashes($this->input->post('nama_kategori',true)); 
            $data['harga_kategori'] =addslashes($this->input->post('harga_kategori',true));   
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('harga_kategori', 'Harga Kategori', 'trim|required|xss_clean|min_length[3]');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('kategori_paket',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_kategori',true));
                    
                    $this->db->where('id_kategori', $id);
                    $this->db->update('kategori_paket', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('kategori_paket', array('id_kategori' => $param1));       
        }
    }

    /** Method For get branch information for Branch Edit **/ 
    public function kategoripaket_edits($id_kategori,$action='')
    {
        checkPermission(4);
        $data=array();
        $data['edit_kategori']=getOld("id_kategori",$id_kategori,"kategori_paket"); 
        if($action=='asyn'){
            $this->load->view('content/kategori_paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/kategori_paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function kategoripaket_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA_KATEGORI", "HARGA_KATEGORI", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $kategori_paket = $this->Kategoripaketmodel->get_all();

            $excel_row = 2;

            foreach($kategori_paket as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, get_current_setting('currency_code')." ".number_format($row->harga_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->last_update));
                $excel_row++;
            }

            $filename = "KategoriPaket-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end kategori_paket

   //controller Main
   public function main_export_paket($action="",$branch_id,$tanggal,$status,$last_month,$to_date){
        if($action=="asyn"){
            $tgl                = date('d', strtotime($to_date));
            $last_month         = strtoupper($last_month);
            $this_month         = strtoupper(date('M', strtotime($to_date)));
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "PAKET", $last_month, $this_month, "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $reportData = $this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$to_date);

            $excel_row = 2;
            $no = 1;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            foreach($reportData as $row)
            {
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;
                $avgsla = $sla/$tm;
                if($lm == 0){ 
                    $mom = 'Infinity'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                    
                } 

                if($avgsla < 16){
                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_paket));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($lm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($tm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, round($avg));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, number_format($avgsla)." Hari");
                    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $mom.' %');
                    $excel_row++;

                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                }
            }

            //Summery value
            $tavg = $ttm/$tgl;
            $tavgsla = $tsla/$ttm; 
            $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($reportData)+1, 'TOTAL');
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($reportData)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($reportData)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($reportData)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($reportData)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($reportData)+1, $tmom);

            $filename = "PaketReport-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    public function main_export_branch($action="",$tanggal,$status,$last_month,$to_date){
        if($action=="asyn"){
            $tgl        = date('d', strtotime($to_date));
            $last_month         = strtoupper($last_month);
            $this_month         = strtoupper(date('M', strtotime($to_date)));
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", $last_month, $this_month, "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$to_date);

            $excel_row = 2;
            $no = 1;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            foreach($reportData as $row)
            {
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;
                $avgsla = $sla/$tm;
                if($lm == 0){ 
                    $mom = 'Infinity'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                    
                } 

                if($avgsla < 16){
                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, number_format($lm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($tm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, round($avg));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($avgsla)." Hari");
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $mom.' %');
                    $excel_row++;

                    $tlm = $tlm + $lm;
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                }
            }

            //Summery value
            $tavg = $ttm/$tgl;
            $tavgsla = $tsla/$ttm; 
            $tmom = (($ttm-$tlm)/$tlm)*100; $tmom = decimalPlace($tmom);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($reportData)+1, '');
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($reportData)+1, 'TOTAL');
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($reportData)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($reportData)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($reportData)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($reportData)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($reportData)+1, $tmom." %");

            $filename = "BranchReport-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller Main

    //controller manageuser
        public function manageuser_view($action='')
    {   
        $data=array();
        $data['manageuser']=$this->Manageusermodel->get_all();    
        if($action=='asyn'){
            $this->load->view('content/manageuser/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/manageuser/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    public function manageuser_tl()
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
    public function manageuser_add($action='',$param1='')
    {
        $data['add-salesperson']=$this->Salespersonmodel->get_all();
        $data['pilihbranch']=$this->Branchmodel->get_all();  
        $data['pilihid']=$this->Usersmodel->get_all_tl(); 
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
    public function manageuser_edits($id_sales,$action='')
    {
        $data=array();
        $data['edit_salesperson']=$this->Salespersonmodel->get_salesperson_by_id($id_salesperson);
        $data['pilihbranch']=$this->Branchmodel->get_all();  
        $data['pilihid']=$this->Usersmodel->get_all_tl();  
        if($action=='asyn'){
            $this->load->view('content/sales_person/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    //end controller manageuser

    //controller msisdn
        public function msisdn_view($action='')
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
    public function msisdn_add($action='',$param1='')
    {
        
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['TL'] = $this->Usersmodel->get_all_tl();
            $data['branch'] = $this->Branchmodel->get_all();
        }else{
            $data['TL']=$this->Usersmodel->get_all_tl_by($sess_branch);
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
    public function msisdn_edits($id_haloinstan,$action='')
    {
        $data=array();
        $data['edit_msisdn']=$edit_msisdn=$this->Msisdnmodel->get_msisdn_by_id($id_haloinstan);
        if(empty($edit_msisdn)){
            redirect('Admin/home');
        }
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['TL'] = $this->Usersmodel->get_all_tl();
            $data['branch'] = $this->Branchmodel->get_all();
        }else{
            $data['TL']=$this->Usersmodel->get_all_tl_by($sess_branch);
            $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);
        }
        if($action=='asyn'){
            $this->load->view('content/msisdn/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/msisdn/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    public function msisdn_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ORDER_ID", "ORDER_SUBMIT_DATE", "ORDER_COMPLETED_DATE", "NAMA_PELANGGAN", "USER_ID","EMPLOYEE_NAME","NAMA_PAKET");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ctp = $this->Ctpmodel->get_all();

            $excel_row = 2;

            foreach($ctp as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->order_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->order_submit_date));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->order_completed_date));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_pelanggan_ctp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->user_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->employee_name));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->nama_paket));
                $excel_row++;
            }

            $filename = "CTP-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller msisdn

    //controller paket
        public function paket_view($action='')
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
    public function paket_add($action='',$param1='')
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
            $do                     =addslashes($this->input->post('action',true));     
            $data['nama_paket']     =addslashes($this->input->post('nama_paket',true)); 
            $data['harga_paket']    =addslashes($this->input->post('harga_paket',true)); 
            $data['aktif']          =addslashes($this->input->post('aktif',true)); 
            $data['id_kategori']    =addslashes($this->input->post('id_kategori',true));  
            $data['update_by']      =addslashes($this->input->post('update_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required|xss_clean|min_length[5]|numeric');
            $this->form_validation->set_rules('aktif', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_kategori', 'Kategori', 'trim|required|xss_clean');
            $this->form_validation->set_rules('update_by', 'Input By', 'trim|required|xss_clean');

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

    /** Method For get paket information for Paket Edit **/ 
    public function paket_edits($paket_id,$action='')
    {
        $data=array();
        $data['edit_paket']=$edit_paket=getOld("paket_id",$paket_id,"paket");
        if(empty($edit_paket)){
            redirect('Admin/home');
        }
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }  

    public function paket_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA", "HARGA", "KATEGORI", "STATUS", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $paket = $this->Paketmodel->get_all();

            $excel_row = 2;

            foreach($paket as $row)
            {
                if ($row->aktif == "y") { $status = "AKTIF"; }else{ $status="TIDAK AKTIF"; }
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->paket_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, get_current_setting('currency_code')." ".number_format($row->harga_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->time_update));
                $excel_row++;
            }

            $filename = "Paket-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller paket

    //controller reason
        public function reason_view($action='')
    {   
        $data=array();
        $data['reason']=$this->Reasonmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/reason/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/reason/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function reason_add($action='',$param1='')
    {
        if($action=='asyn'){
            $this->load->view('content/reason/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/reason/add');
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['status']    =addslashes($this->input->post('sstatus',true));  
            $data['nama_reason']    =addslashes($this->input->post('nama_reason',true));  
            $data['update_by']       =addslashes($this->input->post('update_by',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('nama_reason', 'Reason', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('update_by', 'Input by', 'trim|required|xss_clean');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('reason',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_reason',true));
                    
                    $this->db->where('id_reason', $id);
                    $this->db->update('reason', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('reason', array('id_reason' => $param1));       
        }
    }

    /** Method For get branch information for Branch Edit **/ 
    public function reason_edits($id_reason,$action='')
    {
        $data=array();
        $data['edit_reason']=$edit_reason=getOld("id_reason",$id_reason,"reason"); 
        if(empty($edit_reason)){
            redirect('Admin/home');
        }
        if($action=='asyn'){
            $this->load->view('content/reason/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/reason/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function reason_export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "STATUS","NAMA REASON", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $reasons = $this->Reasonmodel->get_all(); 

            $excel_row = 2;

            foreach($reasons as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_reason));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_reason));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->tgl_update));
                $excel_row++;
            }

            $filename = "Reason-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller reason

    //controller reports
        public function reports_fee_sales_tsa($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('reports/fee_sales',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/fee_sales',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getFeeSales($branch_id, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tpaketkurang = 0;
                $tpaketlebih = 0;
                $tfeepaketkurang = 0;
                $tfeepaketlebih = 0;
                $ttransport = 0;
                $ttfeepaket = 0;
                foreach ($reportData as $report) { 
                    $paketkurang = $report->jml_paketkurang;
                    if($paketkurang>0){
                        $feepaketkurang = $paketkurang*50000;
                    }else{
                        $feepaketkurang = $paketkurang*0;
                    }
                    
                    $paketlebih = $report->jml_paketlebih;
                    if($paketlebih>60){
                        $feepaketlebih = $paketlebih*65000;
                    }else if($paketlebih<=60 && $paketlebih > 30){
                        $feepaketlebih = $paketlebih*60000;
                    }else if($paketlebih<=30 && $paketlebih > 0){
                        $feepaketlebih = $paketlebih*55000;
                    }else{
                        $feepaketlebih = $paketlebih*0;
                    }
                    
                    $tpaket = $paketkurang + $paketlebih;
                    if($tpaket > 60){
                        $kategori_trans = "> 60 KH";
                        $transport = 1125000;
                    }else if($tpaket <= 60 && $tpaket > 30){
                        $kategori_trans = "31 KH - 60 KH";
                        $transport = 900000;
                    }else if($tpaket <= 30 && $tpaket > 19){
                        $kategori_trans = "20 KH - 30 KH";
                        $transport = 450000;
                    }else{
                        $kategori_trans = "< 20 KH";
                        $transport = 0;
                    }
                    $tfeepaket = $feepaketkurang + $feepaketlebih + $transport;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo strtoupper($report->nama_branch) ?></td>
                        <td><?php echo strtoupper($report->nama) ?></td>
                        <td><?php echo strtoupper($report->nama_sales) ?></td>
                        <?php // Kategori Paket ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper(number_format($tpaket)) ?></b></td>
                        <?php // Kategori Transport ?>
                        <td nowrap="" style="background-color: whitesmoke"><?php echo strtoupper($kategori_trans) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($transport)) ?></td>
                        <?php // FEE ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($transport)) ?></b></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($tfeepaket)) ?></b></td>

                        <td><?php echo strtoupper($report->nama_bank) ?></td>
                        <td><?php echo strtoupper($report->no_rekening) ?></td>
                        <td><?php echo strtoupper($report->atas_nama) ?></td>
                    </tr>
                <?php 
                    $tpaketkurang = $tpaketkurang + $paketkurang;
                    $tpaketlebih = $tpaketlebih +$paketlebih;
                    $ttpaket = $tpaketlebih + $tpaketkurang;

                    $tfeepaketlebih = $tfeepaketlebih + $feepaketlebih;
                    $tfeepaketkurang = $tfeepaketkurang + $feepaketkurang;
                    $ttransport = $ttransport + $transport;
                    $ttfeepaket = $tfeepaketlebih + $tfeepaketkurang + $ttransport;
                }  
                 // //Summery value
                   
                 echo "<tr><td colspan='4'><b>GRAND TOTAL</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>".number_format($ttpaket)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td colspan='2' class='text-right'></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttransport)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttfeepaket)."</b></td>";
                 echo "<td class='text-right' nowrap='' colspan='3' ></td>";
                 echo "</tr>"; 
            }
        }

    }

    //View Fee Sales TSA Report// 
    public function reports_fee_sales_tl($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all();
        $channel_x = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
        if($action=='asyn'){
            $this->load->view('reports/fee_sales_tl',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/fee_sales_tl',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            
            $reportData=$this->Reportmodel->getFeeSalesTL($branch_id, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tpaketkurang = 0;
                $tpaketlebih = 0;
                $tfeepaketkurang = 0;
                $tfeepaketlebih = 0;
                $ttransport = 0;
                $ttfeepaket = 0;
                foreach ($reportData as $report) { 
                    $paketkurang = $report->jml_paketkurang;
                    if($paketkurang>0){
                        $feepaketkurang = $paketkurang*10000;
                    }else{
                        $feepaketkurang = $paketkurang*0;
                    }
                    
                    $paketlebih = $report->jml_paketlebih;
                    if($paketlebih > 600){
                        $feepaketlebih = $paketlebih*13000;
                    }else if($paketlebih <= 600 && $paketlebih > 400 ){
                        $feepaketlebih = $paketlebih*12000;
                    }else if($paketlebih <= 400 && $paketlebih > 200){
                        $feepaketlebih = $paketlebih*11000;
                    }else if($paketlebih <=200 && $paketlebih >0){
                        $feepaketlebih = $paketlebih*10000;
                    }else{
                        $feepaketlebih = $paketlebih*0;
                    }
                    
                    $tpaket = $paketkurang + $paketlebih;
                    if($tpaket > 400){
                        $kategori_trans = "> 400 KH";
                        $transport = 1500000;
                    }else if($tpaket <= 400 && $tpaket > 200){
                        $kategori_trans = "201 KH - 400 KH";
                        $transport = 1200000;
                    }else if($tpaket <= 200 && $tpaket > 0){
                        $kategori_trans = "<= 200 KH";
                        $transport = 600000;
                    }else{
                        $kategori_trans = "<= 200 KH";
                        $transport = 0;
                    }
                    $tfeepaket = $feepaketkurang + $feepaketlebih + $transport;
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo strtoupper($report->nama_branch) ?></td>
                        <td><?php echo strtoupper($channel_x[$report->channel]) ?></td>
                        <td><?php echo strtoupper($report->nama) ?></td>
                        <?php // Kategori Paket ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper(number_format($paketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper(number_format($tpaket)) ?></b></td>
                        <?php // Kategori Transport ?>
                        <td nowrap="" style="background-color: whitesmoke"><?php echo strtoupper($kategori_trans) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($transport)) ?></td>
                        <?php // FEE ?>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketkurang)) ?></td>
                        <td class="text-right" nowrap=""><?php echo strtoupper("Rp ".number_format($feepaketlebih)) ?></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($transport)) ?></b></td>
                        <td class="text-right" nowrap="" style="background-color: whitesmoke"><b><?php echo strtoupper("Rp ".number_format($tfeepaket)) ?></b></td>
                    </tr>
                <?php 
                    $tpaketkurang = $tpaketkurang + $paketkurang;
                    $tpaketlebih = $tpaketlebih +$paketlebih;
                    $ttpaket = $tpaketlebih + $tpaketkurang;

                    $tfeepaketlebih = $tfeepaketlebih + $feepaketlebih;
                    $tfeepaketkurang = $tfeepaketkurang + $feepaketkurang;
                    $ttransport = $ttransport + $transport;
                    $ttfeepaket = $tfeepaketlebih + $tfeepaketkurang + $ttransport;
                }  
                 // //Summery value
                   
                 echo "<tr><td colspan='4'><b>GRAND TOTAL</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>".number_format($tpaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>".number_format($ttpaket)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td colspan='2' class='text-right'></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketkurang)."</b></td>";
                 echo "<td class='text-right' nowrap=''><b>Rp ".number_format($tfeepaketlebih)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttransport)."</b></td>";
                 echo "<td class='text-right' nowrap='' style='background-color: whitesmoke'><b>Rp ".number_format($ttfeepaket)."</b></td>";
                 echo "</tr>"; 
            }
        }

    }

    //View Branch Report// 
    public function reports_branch($action='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/branch',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/branch',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
                            $mom = '0'; 
                            $style = "style='background-color:#D3D3D3'";
                        }else{ 
                            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                            if($mom > 0){
                                $style = "style='background-color:#7CFC00'";
                            }else if($mom < 0){
                                $style = "style='background-color:#F08080'";
                            }else{
                                $style = "style='background-color:#D3D3D3'";
                            }
                        } ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                            <!-- <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td> -->
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm;
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='2'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     //echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
            
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $branch=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($branch as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $ly);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $lm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $tm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $avgsla." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm;
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($branch)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($branch)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($branch)+1, $tly);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($branch)+1, $tlm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($branch)+1, $ttm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($branch)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($branch)+1, $tavgsla." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($branch)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($branch)+1, $tyoy."%");

            $filename = "ReportBranch-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

        //View Branch Report// 
    public function reports_scn_weekly($action='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/scn_weekly',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/scn_weekly',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
                            $mom = '0'; 
                            $style = "style='background-color:#D3D3D3'";
                        }else{ 
                            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                            if($mom > 0){
                                $style = "style='background-color:#7CFC00'";
                            }else if($mom < 0){
                                $style = "style='background-color:#F08080'";
                            }else{
                                $style = "style='background-color:#D3D3D3'";
                            }
                        } ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                            <!-- <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td> -->
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm;
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='2'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     //echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
            
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $branch=$this->Reportmodel->getReportBranch($tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($branch as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $ly);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $lm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $tm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $avgsla." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm;
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($branch)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($branch)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($branch)+1, $tly);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($branch)+1, $tlm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($branch)+1, $ttm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($branch)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($branch)+1, $tavgsla." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($branch)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($branch)+1, $tyoy."%");

            $filename = "ReportBranch-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View SLA Report// 
    public function reports_service_level($action='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/service_level',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/service_level',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $tanggal    =$this->input->post('vtanggal',true); 
            $status     =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{
                $tgl        = date('d', strtotime($to_date));
                $reportData=$this->Reportmodel->getReportSLA($tanggal,$status,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;

                    foreach ($reportData as $report) { 
                        $slagr  = $report->sla_grapari;
                        $jmlgr  = $report->jumlah_grapari;
                        if ($jmlgr == 0){
                            $rata2gr = 0;
                        }else{
                            $rata2gr = $slagr/$jmlgr;
                            
                        }
                        $slahvc = $report->sla_hvc;
                        $jmlhvc = $report->jumlah_hvc;

                        if($jmlhvc == 0){
                            $rata2hvc = 0;
                        }else{
                            $rata2hvc = $slahvc/$jmlhvc;
                        }
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $rata2gr ?></td>
                            <td><?php echo $rata2hvc ?></td>
                            <td><?php echo $rata2hvc + $rata2gr ?></td>
                        </tr>
                    <?php 
                        
                    }  
                }

        }

    }}

    public function reports_allbranch($action='', $opsi='', $to_date='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('reports/allbranch',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/allbranch',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $opsi    =$this->input->post('opsi',true);  
            $to_date    =$this->input->post('vto-date',true);  
            $reportData=$this->Reportmodel->getStatusBranch($opsi, $to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tsukses = 0; $tvalid = 0; $tcancel = 0; $treject = 0; $tpending = 0; $tretur = 0; $tbentrok = 0; $tblacklist = 0; $tmasuk = 0; $total = 0; $ttotal = 0;
                foreach ($reportData as $report) { 
                    $sukses = $report->sukses;
                    if($opsi=="opsi2"){
                        $sukses_masuk = $report->sukses_masuk;
                    }
                    $valid = $report->valid;
                    $cancel = $report->cancel;
                    $reject = $report->reject;
                    $pending = $report->pending;
                    $retur = $report->retur;
                    $bentrok = $report->bentrok;
                    $blacklist = $report->blacklist;
                    $masuk = $report->masuk;
                    if($opsi == "opsi2"){
                        $total = $sukses_masuk+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        $total_by_sukses = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        if($total_by_sukses == 0){
                            $persensukses = "0";
                        }else{
                            $persensukses = ($sukses/$total_by_sukses)*100;
                        }
                    }else{
                        $total = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                        if($total == 0){
                            $persensukses = "0";
                        }else{
                            $persensukses = ($sukses/$total)*100;
                        }
                    }
                    ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td class="text-right"><?php echo number_format($sukses);?></td>
                        <td class="text-right"><?php echo number_format($valid);?></td>
                        <td class="text-right"><?php echo number_format($cancel);?></td>
                        <td class="text-right"><?php echo number_format($reject);?></td>
                        <td class="text-right"><?php echo number_format($pending);?></td>
                        <td class="text-right"><?php echo number_format($retur);?></td>
                        <td class="text-right"><?php echo number_format($bentrok);?></td>
                        <td class="text-right"><?php echo number_format($blacklist);?></td>
                        <td class="text-right"><?php echo number_format($masuk);?></td>
                        <td class="text-right"><b><?php echo number_format($total);?></b></td>
                        <td class="text-right"><b><?php echo number_format($persensukses)." %";?></b></td>
                    </tr>
                <?php 
                    $tsukses = $tsukses + $sukses;
                    $tvalid = $tvalid + $valid;
                    $tcancel = $tcancel + $cancel;
                    $treject = $treject + $reject;
                    $tpending = $tpending + $pending;
                    $tretur = $tretur + $retur;
                    $tbentrok = $tbentrok + $bentrok;
                    $tblacklist = $tblacklist + $blacklist;
                    $tmasuk = $tmasuk + $masuk;
                    $ttotal = $ttotal + $total;
                }  
                if($ttotal == 0){
                    $tpersensukses = "0";
                }else{
                    $tpersensukses = ($tsukses/$ttotal)*100;
                }
                 
                 echo "<tr><td colspan='2'><b>Grand Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($tsukses)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tvalid)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tcancel)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($treject)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tpending)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tretur)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tbentrok)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tblacklist)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tmasuk)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttotal)."</b></td>";
                 echo "<td class='text-right'><b>".number_format($tpersensukses)." %</b></td>";
                 echo "</tr>"; 
            }
        }else if($action=='export'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');

            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "AKTIF","VALID", "CANCEL", "REJECT", "PENDING", "RETUR", "BENTROK", "BLACKLIST", "ON PROCESS", "DATA MASUK", "% SUKSES");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $allbranch=$this->Reportmodel->getStatusBranch($opsi, $to_date);

            $excel_row = 2;

            $no=1 ;
            $tsukses = 0; $tvalid = 0; $tcancel = 0; $treject = 0; $tpending = 0; $tretur = 0; $tbentrok = 0; $tblacklist = 0; $tmasuk = 0; $total = 0; $ttotal = 0;
            foreach($allbranch as $row)
            {
                $sukses = $row->sukses;
                if($opsi=="opsi2"){
                    $sukses_masuk = $row->sukses_masuk;
                }
                $valid = $row->valid;
                $cancel = $row->cancel;
                $reject = $row->reject;
                $pending = $row->pending;
                $retur = $row->retur;
                $bentrok = $row->bentrok;
                $blacklist = $row->blacklist;
                $masuk = $row->masuk;
                if($opsi == "opsi2"){
                    $total = $sukses_masuk+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    $total_by_sukses = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    if($total_by_sukses == 0){
                        $persensukses = "0";
                    }else{
                        $persensukses = ($sukses/$total_by_sukses)*100;
                    }
                }else{
                    $total = $sukses+$valid+$cancel+$reject+$pending+$retur+$bentrok+$blacklist+$masuk;
                    if($total == 0){
                        $persensukses = "0";
                    }else{
                        $persensukses = ($sukses/$total)*100;
                    }
                }

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $sukses);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $valid);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $cancel);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $reject);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $pending);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $retur);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $bentrok);
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $blacklist);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $masuk);
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $total);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $persensukses."%");
                $excel_row++;

                $tsukses = $tsukses + $sukses;
                $tvalid = $tvalid + $valid;
                $tcancel = $tcancel + $cancel;
                $treject = $treject + $reject;
                $tpending = $tpending + $pending;
                $tretur = $tretur + $retur;
                $tbentrok = $tbentrok + $bentrok;
                $tblacklist = $tblacklist + $blacklist;
                $tmasuk = $tmasuk + $masuk;
                $ttotal = $ttotal + $total;
            }

            if($ttotal == 0){
                $tpersensukses = "0";
            }else{
                $tpersensukses = ($tsukses/$ttotal)*100;
            }
             
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($allbranch)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($allbranch)+1, "Grand Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($allbranch)+1, $tsukses);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($allbranch)+1, $tvalid);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($allbranch)+1, $tcancel);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($allbranch)+1, $treject);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($allbranch)+1, $tpending);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($allbranch)+1, $tretur);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($allbranch)+1, $bentrok);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($allbranch)+1, $tblacklist);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, count($allbranch)+1, $tmasuk);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, count($allbranch)+1, $ttotal);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, count($allbranch)+1, $tpersensukses."%");

            $filename = "ReportAllBranch-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View Paket Report// 
    public function reports_paket($action='',$branch_id='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/paket',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/paket',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{  
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
                            $mom = '0'; 
                            $style = "style='background-color:#D3D3D3'";
                        }else{ 
                            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                            if($mom > 0){
                                $style = "style='background-color:#7CFC00'";
                            }else if($mom < 0){
                                $style = "style='background-color:#F08080'";
                            }else{
                                $style = "style='background-color:#D3D3D3'";
                            }
                        } ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $report->nama_paket ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='3'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "PAKET", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $paket = $this->Reportmodel->getReportPaket($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($paket as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $ly);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $lm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $tm);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $avgsla." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;
            
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($paket)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($paket)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($paket)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($paket)+1, $tly);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($paket)+1, $tlm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($paket)+1, $ttm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($paket)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($paket)+1, $tavgsla." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($paket)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($paket)+1, $tyoy."%");

            $filename = "ReportPaket-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View TL Report// 
    public function reports_tl($action='',$branch_id='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/tl',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/tl',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportTL($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
                            $mom = '0'; 
                            $style = "style='background-color:#D3D3D3'";
                        }else{ 
                            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                            if($mom > 0){
                                $style = "style='background-color:#7CFC00'";
                            }else if($mom < 0){
                                $style = "style='background-color:#F08080'";
                            }else{
                                $style = "style='background-color:#D3D3D3'";
                            }
                        } ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $report->nama ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                    <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='3'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "NAMA TL", "LAST_YEAR", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM", "%YOY");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $TL=$this->Reportmodel->getReportTL($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($TL as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($ly));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($lm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($tm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, number_format($avgsla)." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($TL)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($TL)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($TL)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($TL)+1, number_format($tly));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($TL)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($TL)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($TL)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($TL)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($TL)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($TL)+1, $tyoy."%");

            $filename = "ReportTL-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View Sub Channel Report// 
    public function reports_sub_channel($action='',$branch_id='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/sub_channel',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sub_channel',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status    =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            if(date('Y-m-d', strtotime($to_date)) > date('Y-m-d')){
                echo "error_tgl_lebih";
            }else{  
                $tgl        = date('d', strtotime($to_date));
                $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
                $reportData=$this->Reportmodel->getReportSubChannel($branch_id,$tanggal,$status,$ly,$to_date);
                if(empty($reportData)){
                    echo "false";
                }else{
                    $no=1 ;
                    $tly = 0;
                    $tlm = 0;
                    $ttm = 0;
                    $tsla = 0;
                    foreach ($reportData as $report) { 
                        $ly = $report->last_year;
                        $lm = $report->last_month;
                        $tm = $report->this_month;
                        $avg = $tm/$tgl;
                        $sla = $report->sla;
                        if($tm == 0){
                            $avgsla = 0;
                        }else{
                            $avgsla = $sla/$tm;
                        }
                        
                        if($ly == 0){ 
                            $yoy = '0'; 
                            $style_ly = "style='background-color:#D3D3D3'";
                        }else{ 
                            $yoy = (($tm-$ly)/$ly)*100; $yoy = decimalPlace($yoy);
                            if($yoy > 0){
                                $style_ly = "style='background-color:#7CFC00'";
                            }else if($yoy < 0){
                                $style_ly = "style='background-color:#F08080'";
                            }else{
                                $style_ly = "style='background-color:#D3D3D3'";
                            }
                        } 

                        if($lm == 0){ 
                            $mom = '0'; 
                            $style = "style='background-color:#D3D3D3'";
                        }else{ 
                            $mom = (($tm-$lm)/$lm)*100; $mom = decimalPlace($mom);
                            if($mom > 0){
                                $style = "style='background-color:#7CFC00'";
                            }else if($mom < 0){
                                $style = "style='background-color:#F08080'";
                            }else{
                                $style = "style='background-color:#D3D3D3'";
                            }
                        } ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $report->nama_branch ?></td>
                            <td><?php echo $channel[$report->sales_channel] ?></td>
                            <td><?php echo $report->sub_channel ?></td>
                            <td class="text-right"><?php echo number_format($ly);?></td>
                            <td class="text-right"><?php echo number_format($lm);?></td>
                            <td class="text-right"><?php echo number_format($tm);?></td>
                            <td class="text-right"><?php echo round($avg);?></td>
                            <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                            <td class="text-right" <?php echo $style?>><b><?php  echo $mom.' %'; ?></b></td>
                            <td class="text-right" <?php echo $style_ly?>><b><?php  echo $yoy.' %'; ?></b></td>
                        </tr>
                <?php 
                        $tly = $tly + $ly;
                        $tlm = $tlm + $lm;
                        $ttm = $ttm + $tm; 
                        $tsla = $tsla + $sla; 
                        
                    }  
                     //Summery value
                        $tavg = $ttm/$tgl;
                        if($ttm == 0){
                            $tavgsla = 0;
                        }else{
                            $tavgsla = $tsla/$ttm;
                        }

                        if($tlm == 0){
                            $tmom = 0;
                        }else{
                            $tmom = (($ttm-$tlm)/$tlm)*100; 
                        }
                        $tmom = decimalPlace($tmom);

                        if($tly == 0){
                            $tyoy = 0;
                        }else{
                            $tyoy = (($ttm-$tly)/$tly)*100;
                        }
                        $tyoy = decimalPlace($tyoy);

                        if($tmom > 0){
                            $tstyle = "style='background-color:#7CFC00'";
                        }else if($tmom < 0){
                            $tstyle = "style='background-color:#F08080'";
                        }else{
                            $tstyle = "style='background-color:#D3D3D3'";
                        }
                        if($tyoy > 0){
                            $tstyle_ly = "style='background-color:#7CFC00'";
                        }else if($tyoy < 0){
                            $tstyle_ly = "style='background-color:#F08080'";
                        }else{
                            $tstyle_ly = "style='background-color:#D3D3D3'";
                        }
                     echo "<tr><td colspan='4'><b>Total</b></td>";
                     echo "<td class='text-right'><b>".number_format($tly)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($tlm)."</b></td>";
                     echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                     echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                     //echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                     echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
                     echo "<td class='text-right' ".$tstyle."><b>".$tmom." %</b></td>"; 
                     echo "<td class='text-right' ".$tstyle_ly."><b>".$tyoy." %</b></td></tr>"; 
                }
            }
        }else if($action=='export'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');

            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "CHANNEL","SUB CHANNEL", "LAST_MONTH", "THIS_MONTH", "AVG/HARI", "RATA2 SLA", "%MOM");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $ly = date('Y-m-d', strtotime('-1 year', strtotime( $to_date )));
            $sub_channel=$this->Reportmodel->getReportSubChannel($branch_id,$tanggal,$status,$ly,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tly = 0;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($sub_channel as $row)
            {
                $ly = $row->last_year;
                $lm = $row->last_month;
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;

                if($tm == 0){
                    $avgsla = 0;
                }else{
                    $avgsla = $sla/$tm;
                }

                if($lm == 0){ 
                    $mom = '0'; 
                }else{ 
                    $mom = (($tm-$lm)/$lm)*100; $mom = $mom;
                } 

                if($ly == 0){ 
                    $yoy = '0'; 
                }else{ 
                    $yoy = (($tm-$ly)/$ly)*100; $yoy = $yoy;
                } 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $channel[$row->sales_channel] );
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, number_format($ly));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($lm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, number_format($tm));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, round($avg));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, number_format($avgsla)." Hari");
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $mom."%");
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $yoy."%");
                $excel_row++;

                $tly = $tly + $ly;
                $tlm = $tlm + $lm;
                $ttm = $ttm + $tm; 
                $tsla = $tsla + $sla; 
                
            }

            $tavg = $ttm/$tgl;
            if($ttm == 0){
                $tavgsla = 0;
            }else{
                $tavgsla = $tsla/$ttm;
            }

            if($tlm == 0){
                $tmom = 0;
            }else{
                $tmom = (($ttm-$tlm)/$tlm)*100; 
            }
            $tmom = $tmom;

            if($tly == 0){
                $tyoy = 0;
            }else{
                $tyoy = (($ttm-$tly)/$tly)*100;
            }
            $tyoy = $tyoy;

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($sub_channel)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($sub_channel)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($sub_channel)+1, number_format($tly));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($sub_channel)+1, number_format($tlm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, count($sub_channel)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, count($sub_channel)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, count($sub_channel)+1, number_format($tavgsla)." Hari");
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, count($sub_channel)+1, $tmom."%");
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, count($sub_channel)+1, $tyoy."%");

            $filename = "ReportSubChannel-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View Sales Person Report// 
    public function reports_sales_person($action='',$branch_id='',$tanggal='',$status='',$to_date='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('reports/sales_person',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/sales_person',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $tanggal    =$this->input->post('vtanggal',true); 
            $status     =$this->input->post('vstatus',true); 
            $to_date    =$this->input->post('vto-date',true);  
            $tgl        = date('d', strtotime($to_date));
            $reportData=$this->Reportmodel->getReportSalesPerson($branch_id,$tanggal,$status,$to_date);
            if(empty($reportData)){
                echo "false";
            }else{
                $no=1 ;
                $tlm = 0;
                $ttm = 0;
                $tsla = 0;
                foreach ($reportData as $report) { 
                    $tm = $report->this_month;
                    $avg = $tm/$tgl;
                    $sla = $report->sla;
                    $avgsla = $sla/$tm;
                    
                    if($avgsla < 16){ ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $report->nama_branch ?></td>
                        <td><?php echo $report->nama_sales ?></td>
                        <td class="text-right"><?php echo number_format($tm);?></td>
                        <td class="text-right"><?php echo round($avg);?></td>
                        <td class="text-right"><?php echo number_format($avgsla)." Hari";?></td>
                    </tr>
                <?php 
                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                    }
                }  
                 //Summery value
                  $tavgsla = $tsla/$ttm;
                  $tavg = $ttm/$tgl;
                 echo "<tr><td colspan='3'><b>Total</b></td>";
                 echo "<td class='text-right'><b>".number_format($ttm)."</b></td>";
                 echo "<td class='text-right'><b>".round($tavg)."</b></td>";
                 // echo "<td class='text-right'><b>".number_format($tsla)." Hari</b></td>";
                 echo "<td class='text-right'><b>".number_format($tavgsla)." Hari</b></td>";
            }
        }else if($action=='export'){
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("NO", "BRANCH", "NAMA SALES", "JUMLAH", "AVG/HARI", "RATA2 SLA");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sales_person=$this->Reportmodel->getReportSalesPerson($branch_id,$tanggal,$status,$to_date);

            $excel_row = 2;

            $no=1 ;
            $tlm = 0;
            $ttm = 0;
            $tsla = 0;
            $tgl        = date('d', strtotime($to_date));
            foreach($sales_person as $row)
            {
                $tm = $row->this_month;
                $avg = $tm/$tgl;
                $sla = $row->sla;
                $avgsla = $sla/$tm; 

                if($avgsla < 16){

                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_branch));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_sales));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, number_format($tm));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, round($avg));
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, number_format($avgsla)." Hari");
                    $excel_row++;

                    $ttm = $ttm + $tm; 
                    $tsla = $tsla + $sla; 
                }
                
            }

            $tavg = $ttm/$tgl;
            $tavgsla = $tsla/$ttm; 

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, count($sales_person)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, count($sales_person)+1, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, count($sales_person)+1, "Total");
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, count($sales_person)+1, number_format($ttm));
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, count($sales_person)+1, round($tavg));
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, count($sales_person)+1, number_format($tavgsla)." Hari");

            $filename = "ReportSalesPerson-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //View Status Daily Report// 
    public function reports_status_daily($action='')
    {
        $data=array();
        $data['branch']=$this->Branchmodel->get_all(); 
        // $data['data_tanggal']=$this->Reportmodel->get_tanggal(); 
        if($action=='asyn'){
            $this->load->view('reports/status_daily',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('reports/status_daily',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $branch_id    =$this->input->post('vbranch',true); 
            $bulan    =$this->input->post('bulan',true); 
            $tahun    =$this->input->post('tahun',true); 
            ?>  <table class="table table-bordered hoverTable">
                    <thead>
                        <th style="background-color: whitesmoke">STATUS</th>
                        <?php 
                        // $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                        for ($i=1; $i <= 31; $i++) { 
                            if($i<10){
                                $i = '0'.$i;
                            }else{
                                $i = $i;
                            }

                            ?>
                            <th style="background-color: whitesmoke" class="text-right"><?php echo $i?></th>
                        <?php }
                        ?>
                        <th style="background-color: whitesmoke" class="text-right">TOTAL</th>
                    </thead>
                    <tbody>
                    <?php if($branch_id == 17){
                        $qbranch = $this->Branchmodel->get_all();
                    }else{
                        $qbranch = $this->Branchmodel->get_all_by($branch_id);
                    } 
                    foreach($qbranch as $row){
                        $branch = $row->branch_id;
                    ?> 
                        <tr>
                            <td colspan="33" style="background-color: whitesmoke;"><?php echo $row->branch_id." - ".strtoupper($row->nama_branch)?></td>
                        </tr>
                        <tr>
                            <td>SUKSES</td>
                            <?php 
                                $tsukses = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDailyAll('sukses', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_sukses = $this->Reportmodel->getStatusDaily($branch, 'sukses', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $sukses_satu = $q_sukses->satu; echo number_format($sukses_satu);?></td>
                            <td class="text-right"><?php $sukses_dua = $q_sukses->dua; echo number_format($sukses_dua);?></td>
                            <td class="text-right"><?php $sukses_tiga = $q_sukses->tiga; echo number_format($sukses_tiga);?></td>
                            <td class="text-right"><?php $sukses_empat = $q_sukses->empat; echo number_format($sukses_empat);?></td>
                            <td class="text-right"><?php $sukses_lima = $q_sukses->lima; echo number_format($sukses_lima);?></td>
                            <td class="text-right"><?php $sukses_enam = $q_sukses->enam; echo number_format($sukses_enam);?></td>
                            <td class="text-right"><?php $sukses_tujuh = $q_sukses->tujuh; echo number_format($sukses_tujuh);?></td>
                            <td class="text-right"><?php $sukses_delapan = $q_sukses->delapan; echo number_format($sukses_delapan);?></td>
                            <td class="text-right"><?php $sukses_sembilan = $q_sukses->sembilan; echo number_format($sukses_sembilan);?></td>
                            <td class="text-right"><?php $sukses_sepuluh = $q_sukses->sepuluh; echo number_format($sukses_sepuluh);?></td>
                            <td class="text-right"><?php $sukses_sebelas = $q_sukses->sebelas; echo number_format($sukses_sebelas);?></td>
                            <td class="text-right"><?php $sukses_duabelas = $q_sukses->duabelas; echo number_format($sukses_duabelas);?></td>
                            <td class="text-right"><?php $sukses_tigabelas = $q_sukses->tigabelas; echo number_format($sukses_tigabelas);?></td>
                            <td class="text-right"><?php $sukses_empatbelas = $q_sukses->empatbelas; echo number_format($sukses_empatbelas);?></td>
                            <td class="text-right"><?php $sukses_limabelas = $q_sukses->limabelas; echo number_format($sukses_limabelas);?></td>
                            <td class="text-right"><?php $sukses_enambelas = $q_sukses->enambelas; echo number_format($sukses_enambelas);?></td>
                            <td class="text-right"><?php $sukses_tujuhbelas = $q_sukses->tujuhbelas; echo number_format($sukses_tujuhbelas);?></td>
                            <td class="text-right"><?php $sukses_delapanbelas = $q_sukses->delapanbelas; echo number_format($sukses_delapanbelas);?></td>
                            <td class="text-right"><?php $sukses_sembilanbelas = $q_sukses->sembilanbelas; echo number_format($sukses_sembilanbelas);?></td>
                            <td class="text-right"><?php $sukses_duapuluh = $q_sukses->duapuluh; echo number_format($sukses_duapuluh);?></td>
                            <td class="text-right"><?php $sukses_duasatu = $q_sukses->duasatu; echo number_format($sukses_duasatu);?></td>
                            <td class="text-right"><?php $sukses_duadua = $q_sukses->duadua; echo number_format($sukses_duadua);?></td>
                            <td class="text-right"><?php $sukses_duatiga = $q_sukses->duatiga; echo number_format($sukses_duatiga);?></td>
                            <td class="text-right"><?php $sukses_duaempat = $q_sukses->duaempat; echo number_format($sukses_duaempat);?></td>
                            <td class="text-right"><?php $sukses_dualima = $q_sukses->dualima; echo number_format($sukses_dualima);?></td>
                            <td class="text-right"><?php $sukses_duaenam = $q_sukses->duaenam; echo number_format($sukses_duaenam);?></td>
                            <td class="text-right"><?php $sukses_duatujuh = $q_sukses->duatujuh; echo number_format($sukses_duatujuh);?></td>
                            <td class="text-right"><?php $sukses_duadelapan = $q_sukses->duadelapan; echo number_format($sukses_duadelapan);?></td>
                            <td class="text-right"><?php $sukses_duasembilan = $q_sukses->duasembilan; echo number_format($sukses_duasembilan);?></td>
                            <td class="text-right"><?php $sukses_tigapuluh = $q_sukses->tigapuluh; echo number_format($sukses_tigapuluh);?></td>
                            <td class="text-right"><?php $sukses_tigasatu = $q_sukses->tigasatu; echo number_format($sukses_tigasatu);?></td>
                            <?php $tsukses = $sukses_satu + $sukses_dua + $sukses_tiga + $sukses_empat + $sukses_lima + $sukses_enam + $sukses_tujuh + $sukses_delapan + $sukses_sembilan + $sukses_sepuluh + $sukses_sebelas + $sukses_duabelas + $sukses_tigabelas + $sukses_empatbelas + $sukses_limabelas + $sukses_enambelas + $sukses_tujuhbelas + $sukses_delapanbelas + $sukses_sembilanbelas + $sukses_duapuluh + $sukses_duasatu + $sukses_duadua + $sukses_duatiga + $sukses_duaempat + $sukses_dualima + $sukses_duaenam + $sukses_duatujuh + $sukses_duadelapan + $sukses_duasembilan + $sukses_tigapuluh + $sukses_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tsukses); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>VALID</td>
                            <?php 
                                $tvalid = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDailyAll('valid', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_valid = $this->Reportmodel->getStatusDaily($branch, 'valid', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $valid_satu = $q_valid->satu; echo number_format($valid_satu);?></td>
                            <td class="text-right"><?php $valid_dua = $q_valid->dua; echo number_format($valid_dua);?></td>
                            <td class="text-right"><?php $valid_tiga = $q_valid->tiga; echo number_format($valid_tiga);?></td>
                            <td class="text-right"><?php $valid_empat = $q_valid->empat; echo number_format($valid_empat);?></td>
                            <td class="text-right"><?php $valid_lima = $q_valid->lima; echo number_format($valid_lima);?></td>
                            <td class="text-right"><?php $valid_enam = $q_valid->enam; echo number_format($valid_enam);?></td>
                            <td class="text-right"><?php $valid_tujuh = $q_valid->tujuh; echo number_format($valid_tujuh);?></td>
                            <td class="text-right"><?php $valid_delapan = $q_valid->delapan; echo number_format($valid_delapan);?></td>
                            <td class="text-right"><?php $valid_sembilan = $q_valid->sembilan; echo number_format($valid_sembilan);?></td>
                            <td class="text-right"><?php $valid_sepuluh = $q_valid->sepuluh; echo number_format($valid_sepuluh);?></td>
                            <td class="text-right"><?php $valid_sebelas = $q_valid->sebelas; echo number_format($valid_sebelas);?></td>
                            <td class="text-right"><?php $valid_duabelas = $q_valid->duabelas; echo number_format($valid_duabelas);?></td>
                            <td class="text-right"><?php $valid_tigabelas = $q_valid->tigabelas; echo number_format($valid_tigabelas);?></td>
                            <td class="text-right"><?php $valid_empatbelas = $q_valid->empatbelas; echo number_format($valid_empatbelas);?></td>
                            <td class="text-right"><?php $valid_limabelas = $q_valid->limabelas; echo number_format($valid_limabelas);?></td>
                            <td class="text-right"><?php $valid_enambelas = $q_valid->enambelas; echo number_format($valid_enambelas);?></td>
                            <td class="text-right"><?php $valid_tujuhbelas = $q_valid->tujuhbelas; echo number_format($valid_tujuhbelas);?></td>
                            <td class="text-right"><?php $valid_delapanbelas = $q_valid->delapanbelas; echo number_format($valid_delapanbelas);?></td>
                            <td class="text-right"><?php $valid_sembilanbelas = $q_valid->sembilanbelas; echo number_format($valid_sembilanbelas);?></td>
                            <td class="text-right"><?php $valid_duapuluh = $q_valid->duapuluh; echo number_format($valid_duapuluh);?></td>
                            <td class="text-right"><?php $valid_duasatu = $q_valid->duasatu; echo number_format($valid_duasatu);?></td>
                            <td class="text-right"><?php $valid_duadua = $q_valid->duadua; echo number_format($valid_duadua);?></td>
                            <td class="text-right"><?php $valid_duatiga = $q_valid->duatiga; echo number_format($valid_duatiga);?></td>
                            <td class="text-right"><?php $valid_duaempat = $q_valid->duaempat; echo number_format($valid_duaempat);?></td>
                            <td class="text-right"><?php $valid_dualima = $q_valid->dualima; echo number_format($valid_dualima);?></td>
                            <td class="text-right"><?php $valid_duaenam = $q_valid->duaenam; echo number_format($valid_duaenam);?></td>
                            <td class="text-right"><?php $valid_duatujuh = $q_valid->duatujuh; echo number_format($valid_duatujuh);?></td>
                            <td class="text-right"><?php $valid_duadelapan = $q_valid->duadelapan; echo number_format($valid_duadelapan);?></td>
                            <td class="text-right"><?php $valid_duasembilan = $q_valid->duasembilan; echo number_format($valid_duasembilan);?></td>
                            <td class="text-right"><?php $valid_tigapuluh = $q_valid->tigapuluh; echo number_format($valid_tigapuluh);?></td>
                            <td class="text-right"><?php $valid_tigasatu = $q_valid->tigasatu; echo number_format($valid_tigasatu);?></td>
                            <?php $tvalid = $valid_satu + $valid_dua + $valid_tiga + $valid_empat + $valid_lima + $valid_enam + $valid_tujuh + $valid_delapan + $valid_sembilan + $valid_sepuluh + $valid_sebelas + $valid_duabelas + $valid_tigabelas + $valid_empatbelas + $valid_limabelas + $valid_enambelas + $valid_tujuhbelas + $valid_delapanbelas + $valid_sembilanbelas + $valid_duapuluh + $valid_duasatu + $valid_duadua + $valid_duatiga + $valid_duaempat + $valid_dualima + $valid_duaenam + $valid_duatujuh + $valid_duadelapan + $valid_duasembilan + $valid_tigapuluh + $valid_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tvalid); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>CANCEL</td>
                            <?php 
                                $tcancel = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDailyAll('cancel', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_cancel = $this->Reportmodel->getStatusDaily($branch, 'cancel', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $cancel_satu = $q_cancel->satu; echo number_format($cancel_satu);?></td>
                            <td class="text-right"><?php $cancel_dua = $q_cancel->dua; echo number_format($cancel_dua);?></td>
                            <td class="text-right"><?php $cancel_tiga = $q_cancel->tiga; echo number_format($cancel_tiga);?></td>
                            <td class="text-right"><?php $cancel_empat = $q_cancel->empat; echo number_format($cancel_empat);?></td>
                            <td class="text-right"><?php $cancel_lima = $q_cancel->lima; echo number_format($cancel_lima);?></td>
                            <td class="text-right"><?php $cancel_enam = $q_cancel->enam; echo number_format($cancel_enam);?></td>
                            <td class="text-right"><?php $cancel_tujuh = $q_cancel->tujuh; echo number_format($cancel_tujuh);?></td>
                            <td class="text-right"><?php $cancel_delapan = $q_cancel->delapan; echo number_format($cancel_delapan);?></td>
                            <td class="text-right"><?php $cancel_sembilan = $q_cancel->sembilan; echo number_format($cancel_sembilan);?></td>
                            <td class="text-right"><?php $cancel_sepuluh = $q_cancel->sepuluh; echo number_format($cancel_sepuluh);?></td>
                            <td class="text-right"><?php $cancel_sebelas = $q_cancel->sebelas; echo number_format($cancel_sebelas);?></td>
                            <td class="text-right"><?php $cancel_duabelas = $q_cancel->duabelas; echo number_format($cancel_duabelas);?></td>
                            <td class="text-right"><?php $cancel_tigabelas = $q_cancel->tigabelas; echo number_format($cancel_tigabelas);?></td>
                            <td class="text-right"><?php $cancel_empatbelas = $q_cancel->empatbelas; echo number_format($cancel_empatbelas);?></td>
                            <td class="text-right"><?php $cancel_limabelas = $q_cancel->limabelas; echo number_format($cancel_limabelas);?></td>
                            <td class="text-right"><?php $cancel_enambelas = $q_cancel->enambelas; echo number_format($cancel_enambelas);?></td>
                            <td class="text-right"><?php $cancel_tujuhbelas = $q_cancel->tujuhbelas; echo number_format($cancel_tujuhbelas);?></td>
                            <td class="text-right"><?php $cancel_delapanbelas = $q_cancel->delapanbelas; echo number_format($cancel_delapanbelas);?></td>
                            <td class="text-right"><?php $cancel_sembilanbelas = $q_cancel->sembilanbelas; echo number_format($cancel_sembilanbelas);?></td>
                            <td class="text-right"><?php $cancel_duapuluh = $q_cancel->duapuluh; echo number_format($cancel_duapuluh);?></td>
                            <td class="text-right"><?php $cancel_duasatu = $q_cancel->duasatu; echo number_format($cancel_duasatu);?></td>
                            <td class="text-right"><?php $cancel_duadua = $q_cancel->duadua; echo number_format($cancel_duadua);?></td>
                            <td class="text-right"><?php $cancel_duatiga = $q_cancel->duatiga; echo number_format($cancel_duatiga);?></td>
                            <td class="text-right"><?php $cancel_duaempat = $q_cancel->duaempat; echo number_format($cancel_duaempat);?></td>
                            <td class="text-right"><?php $cancel_dualima = $q_cancel->dualima; echo number_format($cancel_dualima);?></td>
                            <td class="text-right"><?php $cancel_duaenam = $q_cancel->duaenam; echo number_format($cancel_duaenam);?></td>
                            <td class="text-right"><?php $cancel_duatujuh = $q_cancel->duatujuh; echo number_format($cancel_duatujuh);?></td>
                            <td class="text-right"><?php $cancel_duadelapan = $q_cancel->duadelapan; echo number_format($cancel_duadelapan);?></td>
                            <td class="text-right"><?php $cancel_duasembilan = $q_cancel->duasembilan; echo number_format($cancel_duasembilan);?></td>
                            <td class="text-right"><?php $cancel_tigapuluh = $q_cancel->tigapuluh; echo number_format($cancel_tigapuluh);?></td>
                            <td class="text-right"><?php $cancel_tigasatu = $q_cancel->tigasatu; echo number_format($cancel_tigasatu);?></td>
                            <?php $tcancel = $cancel_satu + $cancel_dua + $cancel_tiga + $cancel_empat + $cancel_lima + $cancel_enam + $cancel_tujuh + $cancel_delapan + $cancel_sembilan + $cancel_sepuluh + $cancel_sebelas + $cancel_duabelas + $cancel_tigabelas + $cancel_empatbelas + $cancel_limabelas + $cancel_enambelas + $cancel_tujuhbelas + $cancel_delapanbelas + $cancel_sembilanbelas + $cancel_duapuluh + $cancel_duasatu + $cancel_duadua + $cancel_duatiga + $cancel_duaempat + $cancel_dualima + $cancel_duaenam + $cancel_duatujuh + $cancel_duadelapan + $cancel_duasembilan + $cancel_tigapuluh + $cancel_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tcancel); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>REJECT</td>
                            <?php 
                                $treject = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDailyAll('reject', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_reject = $this->Reportmodel->getStatusDaily($branch, 'reject', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $reject_satu = $q_reject->satu; echo number_format($reject_satu);?></td>
                            <td class="text-right"><?php $reject_dua = $q_reject->dua; echo number_format($reject_dua);?></td>
                            <td class="text-right"><?php $reject_tiga = $q_reject->tiga; echo number_format($reject_tiga);?></td>
                            <td class="text-right"><?php $reject_empat = $q_reject->empat; echo number_format($reject_empat);?></td>
                            <td class="text-right"><?php $reject_lima = $q_reject->lima; echo number_format($reject_lima);?></td>
                            <td class="text-right"><?php $reject_enam = $q_reject->enam; echo number_format($reject_enam);?></td>
                            <td class="text-right"><?php $reject_tujuh = $q_reject->tujuh; echo number_format($reject_tujuh);?></td>
                            <td class="text-right"><?php $reject_delapan = $q_reject->delapan; echo number_format($reject_delapan);?></td>
                            <td class="text-right"><?php $reject_sembilan = $q_reject->sembilan; echo number_format($reject_sembilan);?></td>
                            <td class="text-right"><?php $reject_sepuluh = $q_reject->sepuluh; echo number_format($reject_sepuluh);?></td>
                            <td class="text-right"><?php $reject_sebelas = $q_reject->sebelas; echo number_format($reject_sebelas);?></td>
                            <td class="text-right"><?php $reject_duabelas = $q_reject->duabelas; echo number_format($reject_duabelas);?></td>
                            <td class="text-right"><?php $reject_tigabelas = $q_reject->tigabelas; echo number_format($reject_tigabelas);?></td>
                            <td class="text-right"><?php $reject_empatbelas = $q_reject->empatbelas; echo number_format($reject_empatbelas);?></td>
                            <td class="text-right"><?php $reject_limabelas = $q_reject->limabelas; echo number_format($reject_limabelas);?></td>
                            <td class="text-right"><?php $reject_enambelas = $q_reject->enambelas; echo number_format($reject_enambelas);?></td>
                            <td class="text-right"><?php $reject_tujuhbelas = $q_reject->tujuhbelas; echo number_format($reject_tujuhbelas);?></td>
                            <td class="text-right"><?php $reject_delapanbelas = $q_reject->delapanbelas; echo number_format($reject_delapanbelas);?></td>
                            <td class="text-right"><?php $reject_sembilanbelas = $q_reject->sembilanbelas; echo number_format($reject_sembilanbelas);?></td>
                            <td class="text-right"><?php $reject_duapuluh = $q_reject->duapuluh; echo number_format($reject_duapuluh);?></td>
                            <td class="text-right"><?php $reject_duasatu = $q_reject->duasatu; echo number_format($reject_duasatu);?></td>
                            <td class="text-right"><?php $reject_duadua = $q_reject->duadua; echo number_format($reject_duadua);?></td>
                            <td class="text-right"><?php $reject_duatiga = $q_reject->duatiga; echo number_format($reject_duatiga);?></td>
                            <td class="text-right"><?php $reject_duaempat = $q_reject->duaempat; echo number_format($reject_duaempat);?></td>
                            <td class="text-right"><?php $reject_dualima = $q_reject->dualima; echo number_format($reject_dualima);?></td>
                            <td class="text-right"><?php $reject_duaenam = $q_reject->duaenam; echo number_format($reject_duaenam);?></td>
                            <td class="text-right"><?php $reject_duatujuh = $q_reject->duatujuh; echo number_format($reject_duatujuh);?></td>
                            <td class="text-right"><?php $reject_duadelapan = $q_reject->duadelapan; echo number_format($reject_duadelapan);?></td>
                            <td class="text-right"><?php $reject_duasembilan = $q_reject->duasembilan; echo number_format($reject_duasembilan);?></td>
                            <td class="text-right"><?php $reject_tigapuluh = $q_reject->tigapuluh; echo number_format($reject_tigapuluh);?></td>
                            <td class="text-right"><?php $reject_tigasatu = $q_reject->tigasatu; echo number_format($reject_tigasatu);?></td>
                            <?php $treject = $reject_satu + $reject_dua + $reject_tiga + $reject_empat + $reject_lima + $reject_enam + $reject_tujuh + $reject_delapan + $reject_sembilan + $reject_sepuluh + $reject_sebelas + $reject_duabelas + $reject_tigabelas + $reject_empatbelas + $reject_limabelas + $reject_enambelas + $reject_tujuhbelas + $reject_delapanbelas + $reject_sembilanbelas + $reject_duapuluh + $reject_duasatu + $reject_duadua + $reject_duatiga + $reject_duaempat + $reject_dualima + $reject_duaenam + $reject_duatujuh + $reject_duadelapan + $reject_duasembilan + $reject_tigapuluh + $reject_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($treject); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>PENDING</td>
                            <?php 
                                $tpending = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDailyAll('pending', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_pending = $this->Reportmodel->getStatusDaily($branch, 'pending', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $pending_satu = $q_pending->satu; echo number_format($pending_satu);?></td>
                            <td class="text-right"><?php $pending_dua = $q_pending->dua; echo number_format($pending_dua);?></td>
                            <td class="text-right"><?php $pending_tiga = $q_pending->tiga; echo number_format($pending_tiga);?></td>
                            <td class="text-right"><?php $pending_empat = $q_pending->empat; echo number_format($pending_empat);?></td>
                            <td class="text-right"><?php $pending_lima = $q_pending->lima; echo number_format($pending_lima);?></td>
                            <td class="text-right"><?php $pending_enam = $q_pending->enam; echo number_format($pending_enam);?></td>
                            <td class="text-right"><?php $pending_tujuh = $q_pending->tujuh; echo number_format($pending_tujuh);?></td>
                            <td class="text-right"><?php $pending_delapan = $q_pending->delapan; echo number_format($pending_delapan);?></td>
                            <td class="text-right"><?php $pending_sembilan = $q_pending->sembilan; echo number_format($pending_sembilan);?></td>
                            <td class="text-right"><?php $pending_sepuluh = $q_pending->sepuluh; echo number_format($pending_sepuluh);?></td>
                            <td class="text-right"><?php $pending_sebelas = $q_pending->sebelas; echo number_format($pending_sebelas);?></td>
                            <td class="text-right"><?php $pending_duabelas = $q_pending->duabelas; echo number_format($pending_duabelas);?></td>
                            <td class="text-right"><?php $pending_tigabelas = $q_pending->tigabelas; echo number_format($pending_tigabelas);?></td>
                            <td class="text-right"><?php $pending_empatbelas = $q_pending->empatbelas; echo number_format($pending_empatbelas);?></td>
                            <td class="text-right"><?php $pending_limabelas = $q_pending->limabelas; echo number_format($pending_limabelas);?></td>
                            <td class="text-right"><?php $pending_enambelas = $q_pending->enambelas; echo number_format($pending_enambelas);?></td>
                            <td class="text-right"><?php $pending_tujuhbelas = $q_pending->tujuhbelas; echo number_format($pending_tujuhbelas);?></td>
                            <td class="text-right"><?php $pending_delapanbelas = $q_pending->delapanbelas; echo number_format($pending_delapanbelas);?></td>
                            <td class="text-right"><?php $pending_sembilanbelas = $q_pending->sembilanbelas; echo number_format($pending_sembilanbelas);?></td>
                            <td class="text-right"><?php $pending_duapuluh = $q_pending->duapuluh; echo number_format($pending_duapuluh);?></td>
                            <td class="text-right"><?php $pending_duasatu = $q_pending->duasatu; echo number_format($pending_duasatu);?></td>
                            <td class="text-right"><?php $pending_duadua = $q_pending->duadua; echo number_format($pending_duadua);?></td>
                            <td class="text-right"><?php $pending_duatiga = $q_pending->duatiga; echo number_format($pending_duatiga);?></td>
                            <td class="text-right"><?php $pending_duaempat = $q_pending->duaempat; echo number_format($pending_duaempat);?></td>
                            <td class="text-right"><?php $pending_dualima = $q_pending->dualima; echo number_format($pending_dualima);?></td>
                            <td class="text-right"><?php $pending_duaenam = $q_pending->duaenam; echo number_format($pending_duaenam);?></td>
                            <td class="text-right"><?php $pending_duatujuh = $q_pending->duatujuh; echo number_format($pending_duatujuh);?></td>
                            <td class="text-right"><?php $pending_duadelapan = $q_pending->duadelapan; echo number_format($pending_duadelapan);?></td>
                            <td class="text-right"><?php $pending_duasembilan = $q_pending->duasembilan; echo number_format($pending_duasembilan);?></td>
                            <td class="text-right"><?php $pending_tigapuluh = $q_pending->tigapuluh; echo number_format($pending_tigapuluh);?></td>
                            <td class="text-right"><?php $pending_tigasatu = $q_pending->tigasatu; echo number_format($pending_tigasatu);?></td>
                            <?php $tpending = $pending_satu + $pending_dua + $pending_tiga + $pending_empat + $pending_lima + $pending_enam + $pending_tujuh + $pending_delapan + $pending_sembilan + $pending_sepuluh + $pending_sebelas + $pending_duabelas + $pending_tigabelas + $pending_empatbelas + $pending_limabelas + $pending_enambelas + $pending_tujuhbelas + $pending_delapanbelas + $pending_sembilanbelas + $pending_duapuluh + $pending_duasatu + $pending_duadua + $pending_duatiga + $pending_duaempat + $pending_dualima + $pending_duaenam + $pending_duatujuh + $pending_duadelapan + $pending_duasembilan + $pending_tigapuluh + $pending_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tpending); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>RETUR</td>
                            <?php 
                                $tretur = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDailyAll('retur', 'tanggal_validasi', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_retur = $this->Reportmodel->getStatusDaily($branch, 'retur', 'tanggal_validasi', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $retur_satu = $q_retur->satu; echo number_format($retur_satu);?></td>
                            <td class="text-right"><?php $retur_dua = $q_retur->dua; echo number_format($retur_dua);?></td>
                            <td class="text-right"><?php $retur_tiga = $q_retur->tiga; echo number_format($retur_tiga);?></td>
                            <td class="text-right"><?php $retur_empat = $q_retur->empat; echo number_format($retur_empat);?></td>
                            <td class="text-right"><?php $retur_lima = $q_retur->lima; echo number_format($retur_lima);?></td>
                            <td class="text-right"><?php $retur_enam = $q_retur->enam; echo number_format($retur_enam);?></td>
                            <td class="text-right"><?php $retur_tujuh = $q_retur->tujuh; echo number_format($retur_tujuh);?></td>
                            <td class="text-right"><?php $retur_delapan = $q_retur->delapan; echo number_format($retur_delapan);?></td>
                            <td class="text-right"><?php $retur_sembilan = $q_retur->sembilan; echo number_format($retur_sembilan);?></td>
                            <td class="text-right"><?php $retur_sepuluh = $q_retur->sepuluh; echo number_format($retur_sepuluh);?></td>
                            <td class="text-right"><?php $retur_sebelas = $q_retur->sebelas; echo number_format($retur_sebelas);?></td>
                            <td class="text-right"><?php $retur_duabelas = $q_retur->duabelas; echo number_format($retur_duabelas);?></td>
                            <td class="text-right"><?php $retur_tigabelas = $q_retur->tigabelas; echo number_format($retur_tigabelas);?></td>
                            <td class="text-right"><?php $retur_empatbelas = $q_retur->empatbelas; echo number_format($retur_empatbelas);?></td>
                            <td class="text-right"><?php $retur_limabelas = $q_retur->limabelas; echo number_format($retur_limabelas);?></td>
                            <td class="text-right"><?php $retur_enambelas = $q_retur->enambelas; echo number_format($retur_enambelas);?></td>
                            <td class="text-right"><?php $retur_tujuhbelas = $q_retur->tujuhbelas; echo number_format($retur_tujuhbelas);?></td>
                            <td class="text-right"><?php $retur_delapanbelas = $q_retur->delapanbelas; echo number_format($retur_delapanbelas);?></td>
                            <td class="text-right"><?php $retur_sembilanbelas = $q_retur->sembilanbelas; echo number_format($retur_sembilanbelas);?></td>
                            <td class="text-right"><?php $retur_duapuluh = $q_retur->duapuluh; echo number_format($retur_duapuluh);?></td>
                            <td class="text-right"><?php $retur_duasatu = $q_retur->duasatu; echo number_format($retur_duasatu);?></td>
                            <td class="text-right"><?php $retur_duadua = $q_retur->duadua; echo number_format($retur_duadua);?></td>
                            <td class="text-right"><?php $retur_duatiga = $q_retur->duatiga; echo number_format($retur_duatiga);?></td>
                            <td class="text-right"><?php $retur_duaempat = $q_retur->duaempat; echo number_format($retur_duaempat);?></td>
                            <td class="text-right"><?php $retur_dualima = $q_retur->dualima; echo number_format($retur_dualima);?></td>
                            <td class="text-right"><?php $retur_duaenam = $q_retur->duaenam; echo number_format($retur_duaenam);?></td>
                            <td class="text-right"><?php $retur_duatujuh = $q_retur->duatujuh; echo number_format($retur_duatujuh);?></td>
                            <td class="text-right"><?php $retur_duadelapan = $q_retur->duadelapan; echo number_format($retur_duadelapan);?></td>
                            <td class="text-right"><?php $retur_duasembilan = $q_retur->duasembilan; echo number_format($retur_duasembilan);?></td>
                            <td class="text-right"><?php $retur_tigapuluh = $q_retur->tigapuluh; echo number_format($retur_tigapuluh);?></td>
                            <td class="text-right"><?php $retur_tigasatu = $q_retur->tigasatu; echo number_format($retur_tigasatu);?></td>
                            <?php $tretur = $retur_satu + $retur_dua + $retur_tiga + $retur_empat + $retur_lima + $retur_enam + $retur_tujuh + $retur_delapan + $retur_sembilan + $retur_sepuluh + $retur_sebelas + $retur_duabelas + $retur_tigabelas + $retur_empatbelas + $retur_limabelas + $retur_enambelas + $retur_tujuhbelas + $retur_delapanbelas + $retur_sembilanbelas + $retur_duapuluh + $retur_duasatu + $retur_duadua + $retur_duatiga + $retur_duaempat + $retur_dualima + $retur_duaenam + $retur_duatujuh + $retur_duadelapan + $retur_duasembilan + $retur_tigapuluh + $retur_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tretur); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>BLACKLIST</td>
                            <?php 
                                $tblacklist = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDailyAll('blacklist', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_blacklist = $this->Reportmodel->getStatusDaily($branch, 'blacklist', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $blacklist_satu = $q_blacklist->satu; echo number_format($blacklist_satu);?></td>
                            <td class="text-right"><?php $blacklist_dua = $q_blacklist->dua; echo number_format($blacklist_dua);?></td>
                            <td class="text-right"><?php $blacklist_tiga = $q_blacklist->tiga; echo number_format($blacklist_tiga);?></td>
                            <td class="text-right"><?php $blacklist_empat = $q_blacklist->empat; echo number_format($blacklist_empat);?></td>
                            <td class="text-right"><?php $blacklist_lima = $q_blacklist->lima; echo number_format($blacklist_lima);?></td>
                            <td class="text-right"><?php $blacklist_enam = $q_blacklist->enam; echo number_format($blacklist_enam);?></td>
                            <td class="text-right"><?php $blacklist_tujuh = $q_blacklist->tujuh; echo number_format($blacklist_tujuh);?></td>
                            <td class="text-right"><?php $blacklist_delapan = $q_blacklist->delapan; echo number_format($blacklist_delapan);?></td>
                            <td class="text-right"><?php $blacklist_sembilan = $q_blacklist->sembilan; echo number_format($blacklist_sembilan);?></td>
                            <td class="text-right"><?php $blacklist_sepuluh = $q_blacklist->sepuluh; echo number_format($blacklist_sepuluh);?></td>
                            <td class="text-right"><?php $blacklist_sebelas = $q_blacklist->sebelas; echo number_format($blacklist_sebelas);?></td>
                            <td class="text-right"><?php $blacklist_duabelas = $q_blacklist->duabelas; echo number_format($blacklist_duabelas);?></td>
                            <td class="text-right"><?php $blacklist_tigabelas = $q_blacklist->tigabelas; echo number_format($blacklist_tigabelas);?></td>
                            <td class="text-right"><?php $blacklist_empatbelas = $q_blacklist->empatbelas; echo number_format($blacklist_empatbelas);?></td>
                            <td class="text-right"><?php $blacklist_limabelas = $q_blacklist->limabelas; echo number_format($blacklist_limabelas);?></td>
                            <td class="text-right"><?php $blacklist_enambelas = $q_blacklist->enambelas; echo number_format($blacklist_enambelas);?></td>
                            <td class="text-right"><?php $blacklist_tujuhbelas = $q_blacklist->tujuhbelas; echo number_format($blacklist_tujuhbelas);?></td>
                            <td class="text-right"><?php $blacklist_delapanbelas = $q_blacklist->delapanbelas; echo number_format($blacklist_delapanbelas);?></td>
                            <td class="text-right"><?php $blacklist_sembilanbelas = $q_blacklist->sembilanbelas; echo number_format($blacklist_sembilanbelas);?></td>
                            <td class="text-right"><?php $blacklist_duapuluh = $q_blacklist->duapuluh; echo number_format($blacklist_duapuluh);?></td>
                            <td class="text-right"><?php $blacklist_duasatu = $q_blacklist->duasatu; echo number_format($blacklist_duasatu);?></td>
                            <td class="text-right"><?php $blacklist_duadua = $q_blacklist->duadua; echo number_format($blacklist_duadua);?></td>
                            <td class="text-right"><?php $blacklist_duatiga = $q_blacklist->duatiga; echo number_format($blacklist_duatiga);?></td>
                            <td class="text-right"><?php $blacklist_duaempat = $q_blacklist->duaempat; echo number_format($blacklist_duaempat);?></td>
                            <td class="text-right"><?php $blacklist_dualima = $q_blacklist->dualima; echo number_format($blacklist_dualima);?></td>
                            <td class="text-right"><?php $blacklist_duaenam = $q_blacklist->duaenam; echo number_format($blacklist_duaenam);?></td>
                            <td class="text-right"><?php $blacklist_duatujuh = $q_blacklist->duatujuh; echo number_format($blacklist_duatujuh);?></td>
                            <td class="text-right"><?php $blacklist_duadelapan = $q_blacklist->duadelapan; echo number_format($blacklist_duadelapan);?></td>
                            <td class="text-right"><?php $blacklist_duasembilan = $q_blacklist->duasembilan; echo number_format($blacklist_duasembilan);?></td>
                            <td class="text-right"><?php $blacklist_tigapuluh = $q_blacklist->tigapuluh; echo number_format($blacklist_tigapuluh);?></td>
                            <td class="text-right"><?php $blacklist_tigasatu = $q_blacklist->tigasatu; echo number_format($blacklist_tigasatu);?></td>
                            <?php $tblacklist = $blacklist_satu + $blacklist_dua + $blacklist_tiga + $blacklist_empat + $blacklist_lima + $blacklist_enam + $blacklist_tujuh + $blacklist_delapan + $blacklist_sembilan + $blacklist_sepuluh + $blacklist_sebelas + $blacklist_duabelas + $blacklist_tigabelas + $blacklist_empatbelas + $blacklist_limabelas + $blacklist_enambelas + $blacklist_tujuhbelas + $blacklist_delapanbelas + $blacklist_sembilanbelas + $blacklist_duapuluh + $blacklist_duasatu + $blacklist_duadua + $blacklist_duatiga + $blacklist_duaempat + $blacklist_dualima + $blacklist_duaenam + $blacklist_duatujuh + $blacklist_duadelapan + $blacklist_duasembilan + $blacklist_tigapuluh + $blacklist_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tblacklist); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>BENTROK</td>
                            <?php 
                                $tbentrok = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDailyAll('bentrok', 'tanggal_aktif', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_bentrok = $this->Reportmodel->getStatusDaily($branch, 'bentrok', 'tanggal_aktif', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $bentrok_satu = $q_bentrok->satu; echo number_format($bentrok_satu);?></td>
                            <td class="text-right"><?php $bentrok_dua = $q_bentrok->dua; echo number_format($bentrok_dua);?></td>
                            <td class="text-right"><?php $bentrok_tiga = $q_bentrok->tiga; echo number_format($bentrok_tiga);?></td>
                            <td class="text-right"><?php $bentrok_empat = $q_bentrok->empat; echo number_format($bentrok_empat);?></td>
                            <td class="text-right"><?php $bentrok_lima = $q_bentrok->lima; echo number_format($bentrok_lima);?></td>
                            <td class="text-right"><?php $bentrok_enam = $q_bentrok->enam; echo number_format($bentrok_enam);?></td>
                            <td class="text-right"><?php $bentrok_tujuh = $q_bentrok->tujuh; echo number_format($bentrok_tujuh);?></td>
                            <td class="text-right"><?php $bentrok_delapan = $q_bentrok->delapan; echo number_format($bentrok_delapan);?></td>
                            <td class="text-right"><?php $bentrok_sembilan = $q_bentrok->sembilan; echo number_format($bentrok_sembilan);?></td>
                            <td class="text-right"><?php $bentrok_sepuluh = $q_bentrok->sepuluh; echo number_format($bentrok_sepuluh);?></td>
                            <td class="text-right"><?php $bentrok_sebelas = $q_bentrok->sebelas; echo number_format($bentrok_sebelas);?></td>
                            <td class="text-right"><?php $bentrok_duabelas = $q_bentrok->duabelas; echo number_format($bentrok_duabelas);?></td>
                            <td class="text-right"><?php $bentrok_tigabelas = $q_bentrok->tigabelas; echo number_format($bentrok_tigabelas);?></td>
                            <td class="text-right"><?php $bentrok_empatbelas = $q_bentrok->empatbelas; echo number_format($bentrok_empatbelas);?></td>
                            <td class="text-right"><?php $bentrok_limabelas = $q_bentrok->limabelas; echo number_format($bentrok_limabelas);?></td>
                            <td class="text-right"><?php $bentrok_enambelas = $q_bentrok->enambelas; echo number_format($bentrok_enambelas);?></td>
                            <td class="text-right"><?php $bentrok_tujuhbelas = $q_bentrok->tujuhbelas; echo number_format($bentrok_tujuhbelas);?></td>
                            <td class="text-right"><?php $bentrok_delapanbelas = $q_bentrok->delapanbelas; echo number_format($bentrok_delapanbelas);?></td>
                            <td class="text-right"><?php $bentrok_sembilanbelas = $q_bentrok->sembilanbelas; echo number_format($bentrok_sembilanbelas);?></td>
                            <td class="text-right"><?php $bentrok_duapuluh = $q_bentrok->duapuluh; echo number_format($bentrok_duapuluh);?></td>
                            <td class="text-right"><?php $bentrok_duasatu = $q_bentrok->duasatu; echo number_format($bentrok_duasatu);?></td>
                            <td class="text-right"><?php $bentrok_duadua = $q_bentrok->duadua; echo number_format($bentrok_duadua);?></td>
                            <td class="text-right"><?php $bentrok_duatiga = $q_bentrok->duatiga; echo number_format($bentrok_duatiga);?></td>
                            <td class="text-right"><?php $bentrok_duaempat = $q_bentrok->duaempat; echo number_format($bentrok_duaempat);?></td>
                            <td class="text-right"><?php $bentrok_dualima = $q_bentrok->dualima; echo number_format($bentrok_dualima);?></td>
                            <td class="text-right"><?php $bentrok_duaenam = $q_bentrok->duaenam; echo number_format($bentrok_duaenam);?></td>
                            <td class="text-right"><?php $bentrok_duatujuh = $q_bentrok->duatujuh; echo number_format($bentrok_duatujuh);?></td>
                            <td class="text-right"><?php $bentrok_duadelapan = $q_bentrok->duadelapan; echo number_format($bentrok_duadelapan);?></td>
                            <td class="text-right"><?php $bentrok_duasembilan = $q_bentrok->duasembilan; echo number_format($bentrok_duasembilan);?></td>
                            <td class="text-right"><?php $bentrok_tigapuluh = $q_bentrok->tigapuluh; echo number_format($bentrok_tigapuluh);?></td>
                            <td class="text-right"><?php $bentrok_tigasatu = $q_bentrok->tigasatu; echo number_format($bentrok_tigasatu);?></td>
                            <?php $tbentrok = $bentrok_satu + $bentrok_dua + $bentrok_tiga + $bentrok_empat + $bentrok_lima + $bentrok_enam + $bentrok_tujuh + $bentrok_delapan + $bentrok_sembilan + $bentrok_sepuluh + $bentrok_sebelas + $bentrok_duabelas + $bentrok_tigabelas + $bentrok_empatbelas + $bentrok_limabelas + $bentrok_enambelas + $bentrok_tujuhbelas + $bentrok_delapanbelas + $bentrok_sembilanbelas + $bentrok_duapuluh + $bentrok_duasatu + $bentrok_duadua + $bentrok_duatiga + $bentrok_duaempat + $bentrok_dualima + $bentrok_duaenam + $bentrok_duatujuh + $bentrok_duadelapan + $bentrok_duasembilan + $bentrok_tigapuluh + $bentrok_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tbentrok); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>MASUK</td>
                            <?php 
                                $tmasuk = 0;
                                if($branch == 17){
                                    // echo $st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDailyAll('masuk', 'tanggal_masuk', $tahun, $bulan);
                                }else{
                                    // echo $branch_id." - ".$st." - ".$tgl." - ".$tahun." - ".$bulan." - ".$i;
                                    $q_masuk = $this->Reportmodel->getStatusDaily($branch, 'masuk', 'tanggal_masuk', $tahun, $bulan);
                                } ?>
                            <td class="text-right"><?php $masuk_satu = $q_masuk->satu; echo number_format($masuk_satu);?></td>
                            <td class="text-right"><?php $masuk_dua = $q_masuk->dua; echo number_format($masuk_dua);?></td>
                            <td class="text-right"><?php $masuk_tiga = $q_masuk->tiga; echo number_format($masuk_tiga);?></td>
                            <td class="text-right"><?php $masuk_empat = $q_masuk->empat; echo number_format($masuk_empat);?></td>
                            <td class="text-right"><?php $masuk_lima = $q_masuk->lima; echo number_format($masuk_lima);?></td>
                            <td class="text-right"><?php $masuk_enam = $q_masuk->enam; echo number_format($masuk_enam);?></td>
                            <td class="text-right"><?php $masuk_tujuh = $q_masuk->tujuh; echo number_format($masuk_tujuh);?></td>
                            <td class="text-right"><?php $masuk_delapan = $q_masuk->delapan; echo number_format($masuk_delapan);?></td>
                            <td class="text-right"><?php $masuk_sembilan = $q_masuk->sembilan; echo number_format($masuk_sembilan);?></td>
                            <td class="text-right"><?php $masuk_sepuluh = $q_masuk->sepuluh; echo number_format($masuk_sepuluh);?></td>
                            <td class="text-right"><?php $masuk_sebelas = $q_masuk->sebelas; echo number_format($masuk_sebelas);?></td>
                            <td class="text-right"><?php $masuk_duabelas = $q_masuk->duabelas; echo number_format($masuk_duabelas);?></td>
                            <td class="text-right"><?php $masuk_tigabelas = $q_masuk->tigabelas; echo number_format($masuk_tigabelas);?></td>
                            <td class="text-right"><?php $masuk_empatbelas = $q_masuk->empatbelas; echo number_format($masuk_empatbelas);?></td>
                            <td class="text-right"><?php $masuk_limabelas = $q_masuk->limabelas; echo number_format($masuk_limabelas);?></td>
                            <td class="text-right"><?php $masuk_enambelas = $q_masuk->enambelas; echo number_format($masuk_enambelas);?></td>
                            <td class="text-right"><?php $masuk_tujuhbelas = $q_masuk->tujuhbelas; echo number_format($masuk_tujuhbelas);?></td>
                            <td class="text-right"><?php $masuk_delapanbelas = $q_masuk->delapanbelas; echo number_format($masuk_delapanbelas);?></td>
                            <td class="text-right"><?php $masuk_sembilanbelas = $q_masuk->sembilanbelas; echo number_format($masuk_sembilanbelas);?></td>
                            <td class="text-right"><?php $masuk_duapuluh = $q_masuk->duapuluh; echo number_format($masuk_duapuluh);?></td>
                            <td class="text-right"><?php $masuk_duasatu = $q_masuk->duasatu; echo number_format($masuk_duasatu);?></td>
                            <td class="text-right"><?php $masuk_duadua = $q_masuk->duadua; echo number_format($masuk_duadua);?></td>
                            <td class="text-right"><?php $masuk_duatiga = $q_masuk->duatiga; echo number_format($masuk_duatiga);?></td>
                            <td class="text-right"><?php $masuk_duaempat = $q_masuk->duaempat; echo number_format($masuk_duaempat);?></td>
                            <td class="text-right"><?php $masuk_dualima = $q_masuk->dualima; echo number_format($masuk_dualima);?></td>
                            <td class="text-right"><?php $masuk_duaenam = $q_masuk->duaenam; echo number_format($masuk_duaenam);?></td>
                            <td class="text-right"><?php $masuk_duatujuh = $q_masuk->duatujuh; echo number_format($masuk_duatujuh);?></td>
                            <td class="text-right"><?php $masuk_duadelapan = $q_masuk->duadelapan; echo number_format($masuk_duadelapan);?></td>
                            <td class="text-right"><?php $masuk_duasembilan = $q_masuk->duasembilan; echo number_format($masuk_duasembilan);?></td>
                            <td class="text-right"><?php $masuk_tigapuluh = $q_masuk->tigapuluh; echo number_format($masuk_tigapuluh);?></td>
                            <td class="text-right"><?php $masuk_tigasatu = $q_masuk->tigasatu; echo number_format($masuk_tigasatu);?></td>
                            <?php $tmasuk = $masuk_satu + $masuk_dua + $masuk_tiga + $masuk_empat + $masuk_lima + $masuk_enam + $masuk_tujuh + $masuk_delapan + $masuk_sembilan + $masuk_sepuluh + $masuk_sebelas + $masuk_duabelas + $masuk_tigabelas + $masuk_empatbelas + $masuk_limabelas + $masuk_enambelas + $masuk_tujuhbelas + $masuk_delapanbelas + $masuk_sembilanbelas + $masuk_duapuluh + $masuk_duasatu + $masuk_duadua + $masuk_duatiga + $masuk_duaempat + $masuk_dualima + $masuk_duaenam + $masuk_duatujuh + $masuk_duadelapan + $masuk_duasembilan + $masuk_tigapuluh + $masuk_tigasatu; ?>
                            <td class="text-right">
                                <b><?php echo number_format($tmasuk); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: whitesmoke;">GRAND TOTAL</td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsatu = $sukses_satu + $valid_satu + $cancel_satu + $reject_satu + $pending_satu + $retur_satu + $blacklist_satu + $bentrok_satu + $masuk_satu; echo number_format($tsatu);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdua = $sukses_dua + $valid_dua + $cancel_dua + $reject_dua + $pending_dua + $retur_dua + $blacklist_dua + $bentrok_dua + $masuk_dua; echo number_format($tdua);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttiga = $sukses_tiga + $valid_tiga + $cancel_tiga + $reject_tiga + $pending_tiga + $retur_tiga + $blacklist_tiga + $bentrok_tiga + $masuk_tiga; echo number_format($ttiga);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tempat = $sukses_empat + $valid_empat + $cancel_empat + $reject_empat + $pending_empat + $retur_empat + $blacklist_empat + $bentrok_empat + $masuk_empat; echo number_format($tempat);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tlima = $sukses_lima + $valid_lima + $cancel_lima + $reject_lima + $pending_lima + $retur_lima + $blacklist_lima + $bentrok_lima + $masuk_lima; echo number_format($tlima);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tenam = $sukses_enam + $valid_enam + $cancel_enam + $reject_enam + $pending_enam + $retur_enam + $blacklist_enam + $bentrok_enam + $masuk_enam; echo number_format($tenam);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttujuh = $sukses_tujuh + $valid_tujuh + $cancel_tujuh + $reject_tujuh + $pending_tujuh + $retur_tujuh + $blacklist_tujuh + $bentrok_tujuh + $masuk_tujuh; echo number_format($ttujuh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdelapan = $sukses_delapan + $valid_delapan + $cancel_delapan + $reject_delapan + $pending_delapan + $retur_delapan + $blacklist_delapan + $bentrok_delapan + $masuk_delapan; echo number_format($tdelapan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsembilan = $sukses_sembilan + $valid_sembilan + $cancel_sembilan + $reject_sembilan + $pending_sembilan + $retur_sembilan + $blacklist_sembilan + $bentrok_sembilan + $masuk_sembilan; echo number_format($tsembilan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsepuluh = $sukses_sepuluh + $valid_sepuluh + $cancel_sepuluh + $reject_sepuluh + $pending_sepuluh + $retur_sepuluh + $blacklist_sepuluh + $bentrok_sepuluh + $masuk_sepuluh; echo number_format($tsepuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsebelas = $sukses_sebelas + $valid_sebelas + $cancel_sebelas + $reject_sebelas + $pending_sebelas + $retur_sebelas + $blacklist_sebelas + $bentrok_sebelas + $masuk_sebelas; echo number_format($tsebelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduabelas = $sukses_duabelas + $valid_duabelas + $cancel_duabelas + $reject_duabelas + $pending_duabelas + $retur_duabelas + $blacklist_duabelas + $bentrok_duabelas + $masuk_duabelas; echo number_format($tduabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigabelas = $sukses_tigabelas + $valid_tigabelas + $cancel_tigabelas + $reject_tigabelas + $pending_tigabelas + $retur_tigabelas + $blacklist_tigabelas + $bentrok_tigabelas + $masuk_tigabelas; echo number_format($ttigabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tempatbelas = $sukses_empatbelas + $valid_empatbelas + $cancel_empatbelas + $reject_empatbelas + $pending_empatbelas + $retur_empatbelas + $blacklist_empatbelas + $bentrok_empatbelas + $masuk_empatbelas; echo number_format($tempatbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tlimabelas = $sukses_limabelas + $valid_limabelas + $cancel_limabelas + $reject_limabelas + $pending_limabelas + $retur_limabelas + $blacklist_limabelas + $bentrok_limabelas + $masuk_limabelas; echo number_format($tlimabelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tenambelas = $sukses_enambelas + $valid_enambelas + $cancel_enambelas + $reject_enambelas + $pending_enambelas + $retur_enambelas + $blacklist_enambelas + $bentrok_enambelas + $masuk_enambelas; echo number_format($tenambelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttujuhbelas = $sukses_tujuhbelas + $valid_tujuhbelas + $cancel_tujuhbelas + $reject_tujuhbelas + $pending_tujuhbelas + $retur_tujuhbelas + $blacklist_tujuhbelas + $bentrok_tujuhbelas + $masuk_tujuhbelas; echo number_format($ttujuhbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdelapanbelas = $sukses_delapanbelas + $valid_delapanbelas + $cancel_delapanbelas + $reject_delapanbelas + $pending_delapanbelas + $retur_delapanbelas + $blacklist_delapanbelas + $bentrok_delapanbelas + $masuk_delapanbelas; echo number_format($tdelapanbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tsembilanbelas = $sukses_sembilanbelas + $valid_sembilanbelas + $cancel_sembilanbelas + $reject_sembilanbelas + $pending_sembilanbelas + $retur_sembilanbelas + $blacklist_sembilanbelas + $bentrok_sembilanbelas + $masuk_sembilanbelas; echo number_format($tsembilanbelas);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduapuluh = $sukses_duapuluh + $valid_duapuluh + $cancel_duapuluh + $reject_duapuluh + $pending_duapuluh + $retur_duapuluh + $blacklist_duapuluh + $bentrok_duapuluh + $masuk_duapuluh; echo number_format($tduapuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduasatu = $sukses_duasatu + $valid_duasatu + $cancel_duasatu + $reject_duasatu + $pending_duasatu + $retur_duasatu + $blacklist_duasatu + $bentrok_duasatu + $masuk_duasatu; echo number_format($tduasatu);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduadua = $sukses_duadua + $valid_duadua + $cancel_duadua + $reject_duadua + $pending_duadua + $retur_duadua + $blacklist_duadua + $bentrok_duadua + $masuk_duadua; echo number_format($tduadua);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduatiga = $sukses_duatiga + $valid_duatiga + $cancel_duatiga + $reject_duatiga + $pending_duatiga + $retur_duatiga + $blacklist_duatiga + $bentrok_duatiga + $masuk_duatiga; echo number_format($tduatiga);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduaempat = $sukses_duaempat + $valid_duaempat + $cancel_duaempat + $reject_duaempat + $pending_duaempat + $retur_duaempat + $blacklist_duaempat + $bentrok_duaempat + $masuk_duaempat; echo number_format($tduaempat);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tdualima = $sukses_dualima + $valid_dualima + $cancel_dualima + $reject_dualima + $pending_dualima + $retur_dualima + $blacklist_dualima + $bentrok_dualima + $masuk_dualima; echo number_format($tdualima);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduaenam = $sukses_duaenam + $valid_duaenam + $cancel_duaenam + $reject_duaenam + $pending_duaenam + $retur_duaenam + $blacklist_duaenam + $bentrok_duaenam + $masuk_duaenam; echo number_format($tduaenam);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduatujuh = $sukses_duatujuh + $valid_duatujuh + $cancel_duatujuh + $reject_duatujuh + $pending_duatujuh + $retur_duatujuh + $blacklist_duatujuh + $bentrok_duatujuh + $masuk_duatujuh; echo number_format($tduatujuh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduadelapan = $sukses_duadelapan + $valid_duadelapan + $cancel_duadelapan + $reject_duadelapan + $pending_duadelapan + $retur_duadelapan + $blacklist_duadelapan + $bentrok_duadelapan + $masuk_duadelapan; echo number_format($tduadelapan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $tduasembilan = $sukses_duasembilan + $valid_duasembilan + $cancel_duasembilan + $reject_duasembilan + $pending_duasembilan + $retur_duasembilan + $blacklist_duasembilan + $bentrok_duasembilan + $masuk_duasembilan; echo number_format($tduasembilan);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigapuluh = $sukses_tigapuluh + $valid_tigapuluh + $cancel_tigapuluh + $reject_tigapuluh + $pending_tigapuluh + $retur_tigapuluh + $blacklist_tigapuluh + $bentrok_tigapuluh + $masuk_tigapuluh; echo number_format($ttigapuluh);?></td>
                            <td class="text-right" style="background-color: whitesmoke;"><?php $ttigasatu = $sukses_tigasatu + $valid_tigasatu + $cancel_tigasatu + $reject_tigasatu + $pending_tigasatu + $retur_tigasatu + $blacklist_tigasatu + $bentrok_tigasatu + $masuk_tigasatu; echo number_format($ttigasatu);?></td>
                            <?php $ttotal = $tsatu + $tdua + $ttiga + $tempat + $tlima + $tenam + $ttujuh + $tdelapan + $tsembilan + $tsepuluh + $tsebelas + $tduabelas + $ttigabelas + $tempatbelas + $tlimabelas + $tenambelas + $ttujuhbelas + $tdelapanbelas + $tsembilanbelas + $tduapuluh + $tduasatu + $tduadua + $tduatiga + $tduaempat + $tdualima + $tduaenam + $tduatujuh + $tduadelapan + $tduasembilan + $ttigapuluh + $ttigasatu; ?>
                            <td class="text-right" style="background-color: whitesmoke;">
                                <b><?php echo number_format($ttotal); ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: whitesmoke;"><b>% SUKSES</b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsatu==0){ $persensatu = 0; }else{ $persensatu = ($sukses_satu/$tsatu)*100; } echo number_format($persensatu)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdua==0){ $persendua = 0; }else{  $persendua = ($sukses_dua/$tdua)*100; } echo number_format($persendua)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttiga==0){ $pertiga = 0; }else{  $persentiga = ($sukses_tiga/$ttiga)*100; } echo number_format($persentiga)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tempat==0){ $persenempat = 0; }else{  $persenempat = ($sukses_empat/$tempat)*100; } echo number_format($persenempat)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tlima==0){ $persenlima = 0; }else{  $persenlima = ($sukses_lima/$tlima)*100; } echo number_format($persenlima)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tenam==0){ $persenenam = 0; }else{  $persenenam = ($sukses_enam/$tenam)*100; } echo number_format($persenenam)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttujuh==0){ $persentujuh = 0; }else{  $persentujuh = ($sukses_tujuh/$ttujuh)*100; } echo number_format($persentujuh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdelapan==0){ $persendelapan = 0; }else{  $persendelapan = ($sukses_delapan/$tdelapan)*100; } echo number_format($persendelapan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsembilan==0){ $persensembilan = 0; }else{  $persensembilan = ($sukses_sembilan/$tsembilan)*100; } echo number_format($persensembilan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsepuluh==0){ $persensepuluh = 0; }else{  $persensepuluh = ($sukses_sepuluh/$tsepuluh)*100; } echo number_format($persensepuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsebelas==0){ $persensebelas = 0; }else{  $persensebelas = ($sukses_sebelas/$tsebelas)*100; } echo number_format($persensebelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduabelas==0){ $persenduabelas = 0; }else{  $persenduabelas = ($sukses_duabelas/$tduabelas)*100; } echo number_format($persenduabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigabelas==0){ $persentigabelas = 0; }else{  $persentigabelas = ($sukses_tigabelas/$ttigabelas)*100; } echo number_format($persentigabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tempatbelas==0){ $persenempatbelas = 0; }else{  $persenempatbelas = ($sukses_empatbelas/$tempatbelas)*100; } echo number_format($persenempatbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tlimabelas==0){ $persenlimabelas = 0; }else{  $persenlimabelas = ($sukses_limabelas/$tlimabelas)*100; } echo number_format($persenlimabelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tenambelas==0){ $persenenambelas = 0; }else{  $persenenambelas = ($sukses_enambelas/$tenambelas)*100; } echo number_format($persenenambelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttujuhbelas==0){ $persentujuhbelas = 0; }else{  $persentujuhbelas = ($sukses_tujuhbelas/$ttujuhbelas)*100; } echo number_format($persentujuhbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdelapanbelas==0){ $persendelapanbelas = 0; }else{  $persendelapanbelas = ($sukses_delapanbelas/$tdelapanbelas)*100; } echo number_format($persendelapanbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tsembilanbelas==0){ $persensembilanbelas = 0; }else{  $persensembilanbelas = ($sukses_sembilanbelas/$tsembilanbelas)*100; } echo number_format($persensembilanbelas)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduapuluh==0){ $persenduapuluh = 0; }else{  $persenduapuluh = ($sukses_duapuluh/$tduapuluh)*100; } echo number_format($persenduapuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduasatu==0){ $persenduasatu = 0; }else{  $persenduasatu = ($sukses_duasatu/$tduasatu)*100; } echo number_format($persenduasatu)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduadua==0){ $persenduadua = 0; }else{  $persenduadua = ($sukses_duadua/$tduadua)*100; } echo number_format($persenduadua)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduatiga==0){ $persenduatiga = 0; }else{  $persenduatiga = ($sukses_duatiga/$tduatiga)*100; } echo number_format($persenduatiga)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduaempat==0){ $persenduaempat = 0; }else{  $persenduaempat = ($sukses_duaempat/$tduaempat)*100; } echo number_format($persenduaempat)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tdualima==0){ $persendualima = 0; }else{  $persendualima = ($sukses_dualima/$tdualima)*100; } echo number_format($persendualima)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduaenam==0){ $persenduaenam = 0; }else{  $persenduaenam = ($sukses_duaenam/$tduaenam)*100; } echo number_format($persenduaenam)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduatujuh==0){ $persenduatujuh = 0; }else{  $persenduatujuh = ($sukses_duatujuh/$tduatujuh)*100; } echo number_format($persenduatujuh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduadelapan==0){ $persenduadelapan = 0; }else{  $persenduadelapan = ($sukses_duadelapan/$tduadelapan)*100; } echo number_format($persenduadelapan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($tduasembilan==0){ $persenduasembilan = 0; }else{  $persenduasembilan = ($sukses_duasembilan/$tduasembilan)*100; } echo number_format($persenduasembilan)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigapuluh==0){ $persentigapuluh = 0; }else{  $persentigapuluh = ($sukses_tigapuluh/$ttigapuluh)*100; } echo number_format($persentigapuluh)." %";?></b></td>
                            <td style="background-color: whitesmoke;" class="text-right"><b><?php if($ttigasatu==0){ $persentigasatu = 0; }else{  $persentigasatu = ($sukses_tigasatu/$ttigasatu)*100; } echo number_format($persentigasatu)." %";?></b></td>
                            <?php if($ttotal==0){ $persentotal = 0; }else{ $persentotal = ($tsukses/$ttotal)*100; } ?>
                            <td style="background-color: whitesmoke;" class="text-right">
                                <b><?php echo number_format($persentotal)." %"; ?></b>
                            </td>
                        </tr>
                    <?php } ?>
                        
                    </tbody>
                </table><?php
        }

    }
    //end controller reports

    //controller sales
        public function sales_view($action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
    {   
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $max_tanggal = $tanggal->tgl_max;
        $data['bbranch_id'] = 17;
        $data['btgl'] = null;
        $data['bstatus'] = null;
        $data['bfrom_date'] = null;
        $data['bto_date'] = null;
        if($action=='asyn'){
            if($this->session->userdata('level')==4){
                $data['sales'] = $this->Salesmodel->get_all($max_tanggal);
                $data['branch'] = $this->Branchmodel->get_all();
            }else{
                $data['sales'] = $this->Salesmodel->get_all_branch($max_tanggal, $sess_branch);
                $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

            }
            $this->load->view('content/sales/list',$data);
        }else if($action==''){
            if($this->session->userdata('level')==4){
                $data['sales'] = $this->Salesmodel->get_all($max_tanggal);
                $data['branch'] = $this->Branchmodel->get_all();
            }else{
                $data['sales'] = $this->Salesmodel->get_all_branch($max_tanggal, $sess_branch);
                $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

            }
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/list',$data);
            $this->load->view('theme/include/footer');
        }else if($action == 'cari'){        
            // $reportData=$this->Reportmodel->getSalesCari($account,$from_date,$to_date,$trans_type);
            $data['bbranch_id'] = $branch_id;
            $data['btgl'] = $tgl;
            $data['bstatus'] = $status;
            $data['bfrom_date'] = $from_date;
            $data['bto_date'] = $to_date;

            if($this->session->userdata('level')==4){
                $data['branch'] = $this->Branchmodel->get_all();
            }else{
                $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

            }
            if($branch_id == 17){
                $data['sales'] = $cari = $this->Salesmodel->get_all_cari_all($branch_id,$tgl,$status,$from_date,$to_date);
            }else{

                $data['sales'] = $cari = $this->Salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);
            }

            $this->load->view('content/sales/list',$data);
        }else if($action == 'export'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "MSISDN", "NAMA_PELANGGAN", "BRANCH", "PAKET", "SLA", "TL", "SALES_PERSON", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "FA_ID", "ACCOUNT_ID", "ALAMAT", "ALAMAT2", "DISCOUNT (RB)", "PERIODE (BULAN)", "BILL CYCLE", "CHANNEL", "SUB SALES CHANNEL", "JENIS EVENT", "NAMA EVENT", "VALIDATOR", "AKTIVATOR", "STATUS", "KETERANGAN", "TANGGAL_INPUT (SYSTEM)", "TANGGAL_UPDATE (SYSTEM)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            if($branch_id == 17){
                $sales = $this->Salesmodel->get_all_cari_all($branch_id,$tgl,$status,$from_date,$to_date);
            }else{
                $sales = $this->Salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);
            }

            $excel_row = 2;

            foreach($sales as $row)
            {
                if(empty($row->sla)){ 
                    if(!empty($row->tanggal_aktif)){
                        $tgl_aktif = new DateTime($row->tanggal_aktif);
                    }else{
                        $tgl_aktif = new DateTime();
                    }
                    $tgl_masuk = new DateTime($row->tanggal_masuk);
                    $diff = $tgl_aktif->diff($tgl_masuk); 
                    $sla = $diff->d." Hari";
                }else{
                    $sla = strtoupper($row->sla)." Hari"; 
                }

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $sla);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->TL));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                // $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($diff->d));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($row->fa_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, strtoupper($row->account_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, strtoupper($row->alamat));
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, strtoupper($row->alamat2));
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, strtoupper($discount[$row->discount]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, strtoupper($periode[$row->periode]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, strtoupper($row->bill_cycle));
                $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, strtoupper($channel[$row->sales_channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, strtoupper($jenis_event[$row->jenis_event]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, strtoupper($row->nama_event));
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, strtoupper($row->validator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, strtoupper($row->aktivator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, strtoupper($row->deskripsi));
                $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, strtoupper($row->tanggal_input));
                $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "Sales-Exported-on-".date("Y-m-d-H-i-s").".xls";

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

    //Cek MSISDN// 
    public function sales_cek_msisdn($action='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('content/sales/cek_msisdn',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/cek_msisdn',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='view'){
            $cek    =$this->input->post('cek',true); 
            $this->form_validation->set_rules('cek', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            if (!$this->form_validation->run() == FALSE)
            {
                $msisdnData=$this->Salesmodel->getMSISDN($cek);
                if(empty($msisdnData)){
                    echo "false";
                }else{
                    $no=1 ;
                    foreach ($msisdnData as $row) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row->msisdn ?></td>
                            <td><?php echo $row->nama_pelanggan ?></td>
                            <td><?php echo $row->nama_branch ?></td>
                            <td><?php echo $row->tanggal_masuk ?></td>
                            <td><?php echo $row->tanggal_validasi ?></td>
                            <td><?php echo $row->tanggal_aktif ?></td>
                            <td><?php echo $row->status ?></td>
                            <td><a style="float: right;cursor: pointer;" id="click_to_load_modal_popup_msisdn_<?php echo $row->msisdn?>">View Detail</a></td>
                        </tr>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var $modal = $('#load_popup_modal_show_msisdn');
                                $('#click_to_load_modal_popup_msisdn_<?php echo $row->msisdn?>').on('click', function(){
                                    $modal.load('<?php echo base_url()?>Admin/sales_load_modal/',{'msisdn': "<?php echo $row->msisdn ?>",'id2':'2'},
                                    function(){
                                        $modal.modal('show');
                                    });

                                });
                            });
                            $(document).on('click','.sales-remove-btn',function(){  
                                var main=$(this);
                                swal({title: "Are you sure Want To Delete?",
                                text: "You will not be able to recover this Data!",
                                type: "warning",   showCancelButton: true,confirmButtonColor: "#DD6B55",   
                                confirmButtonText: "Yes, delete it!",closeOnConfirm: false,
                                showLoaderOnConfirm: true }, function(){ 
                                    ///////////////     
                                    var link=$(main).attr("href");    
                                    $.ajax({
                                        url : link,
                                        beforeSend : function(){
                                            $(".block-ui").css('display','block'); 
                                        },success : function(data){
                                            $(main).closest("tr").remove();    
                                            //sucessAlert("Remove Sucessfully"); 
                                            $(".system-alert-box").empty();
                                            document.location.href = '<?php echo base_url()?>Admin/sales_cek_msisdn';
                                        swal("Deleted!", "Remove Sucessfully", "success"); 
                                            $(".block-ui").css('display','none');
                                        }    
                                    });
                                }); 
                                return false;       
                            }); 

                        </script>
                    <?php 
                    
                    }  
                }
            }else{
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
        }

    }

    public function sales_load_modal()
    {
        $data['msisdn'] = $msisdn = $this->input->post('msisdn',true);

        $data['detail_msisdn']=$this->Salesmodel->getMSISDNDetail($msisdn);
        $this->load->view('content/sales/load_modal_msisdn',$data);
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

    public function sales_ajax_list()
    {
        $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
        $tanggal = $this->Reportmodel->getMaxDate();
        $data['max_tanggal'] = $tanggal = $tanggal->tgl_max;
        $list = $this->Salesmodel->get_datatables();
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
                        "recordsTotal" => $this->Salesmodel->count_all(),
                        "recordsFiltered" => $this->Salesmodel->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function sales_add($action='',$param1='')
    {
        $datax=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        
        
        if($action=='asyn'){
            $this->load->view('content/sales/add',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/add',$datax);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['tanggal_validasi']   =$tanggal_validasi = addslashes($this->input->post('stanggal_validasi',true));
            $data['tanggal_aktif']      =$tanggal_aktif = addslashes($this->input->post('stanggal_aktif',true));
            $data['paket_id']              =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['bill_cycle']         =addslashes($this->input->post('sbill_cycle',true));
            $data['fa_id']              = $fa_id = addslashes($this->input->post('sfa_id',true));
            $data['account_id']         = $account_id =addslashes($this->input->post('saccount_id',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             = $status = addslashes($this->input->post('sstatus',true));
            $data['deskripsi']          =addslashes($this->input->post('id_reason',true));

            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));
            $data['validasi_by']        =addslashes($this->input->post('svalidasi_by',true));

            $data['tanggal_input']      = date('Y-m-d h:i:s');   
            $data['username']           =addslashes($this->input->post('susername',true));   
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_validasi', 'Tanggal Validasi', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_aktif', 'Tanggal Aktif', 'trim|required|xss_clean');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbill_cycle', 'Bill Cycle', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sfa_id', 'FA ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('saccount_id', 'Account ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_reason', 'Keterangan', 'trim|required|xss_clean');

            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');
            $this->form_validation->set_rules('svalidasi_by', 'Validasi By', 'trim|required|xss_clean');

            $this->form_validation->set_rules('susername', 'Username', 'trim|required|xss_clean');

            if($do == "update"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $sno_bast1 = addslashes($this->input->post('sno_bast1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
                $this->form_validation->set_rules('sno_bast1', 'No BAST', 'trim|xss_clean|min_length[10]');
            }

            if (!$this->form_validation->run() == FALSE)
            {
                $account = $this->Salesmodel->get_sales_by_account($account_id); 
                $fa = $this->Salesmodel->get_sales_by_fa($fa_id); 
                if($do=='insert'){
                    
                    if($tanggal_aktif > date('Y-m-d')){

                        echo "Tanggal aktif tidak boleh lebih dari hari ini !!!!";

                    }else if($tanggal_masuk > $tanggal_validasi || $tanggal_masuk > $tanggal_aktif || $tanggal_validasi > $tanggal_aktif){

                        echo "Tanggal tidak sesuai. Periksa kembali !!!";

                    }else if(value_exists("new_psb","msisdn",$msisdn)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else if($account->jumlah>10){

                        echo "Account ID sudah digunakan untuk 10 MSISDN";

                    }else if($fa->jumlah>10){

                        echo "FA ID sudah digunakan untuk 10 MSISDN";

                    }else{
                        $user_tl = $this->usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $datastatus['status']             ="masuk";
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        $this->db->insert('new_psb',$data);
                        echo "true";
                    }
                     
                }else if($do=='update'){
                    
                    if($msisdn1 == "" || $msisdn1 == null){
                        $msisdn = $msisdn;
                        $data['msisdn'] = $msisdn;
                    }else{
                        $msisdn = $msisdn1;
                        $data['msisdn'] = $msisdn1;
                    }

                    if($tanggal_aktif > date('Y-m-d')){

                        echo "Tanggal aktif tidak boleh lebih dari hari ini !!!!";

                    }else if($tanggal_masuk > $tanggal_validasi || $tanggal_masuk > $tanggal_aktif || $tanggal_validasi > $tanggal_aktif){

                        echo "Tanggal tidak sesuai. Periksa kembali !!!";

                    }else if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else if($account->jumlah>10){

                        echo "Account ID sudah digunakan untuk 10 MSISDN";

                    }else if($fa->jumlah>10){

                        echo "FA ID sudah digunakan untuk 10 MSISDN";

                    }else{

                        $user_tl = $this->Usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        $datastatus['status']             =$status;
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

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
            $qpsb=$this->Salesmodel->get_sales_by_id($param1);  
            $msisdn = $qpsb->msisdn;

            $datastatus['status']             ="";
            $this->db->where('msisdn', $msisdn);
            $this->db->update('msisdn', $datastatus);

            $this->db->delete('new_psb', array('psb_id' => $param1));       
        }
    }

    /** Method For get sales information for sales Edit **/ 
    public function sales_edits($psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $data['reason']=$this->Reasonmodel->get_all(); 
        if($sess_level==4){
            $data['bast']=$this->Bastmodel->get_all_by('');
            $data['msisdn']=$this->Msisdnmodel->get_all();
            $data['branch']=$this->Branchmodel->get_all();
            $data['tl']=$this->Usersmodel->get_all_tl();
            $data['sub_channel']=$this->Sales_channelmodel->get_all();
            $data['sales_person']=$this->Salespersonmodel->get_all();
            $data['validasi']=$this->Usersmodel->get_all_validasi();

        }else{
            $data['bast']=$this->Bastmodel->get_all_by($sess_branch);
            $data['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $data['tl']=$this->Usersmodel->get_all_tl_by($sess_branch);;
            $data['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);;
            $data['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);;
            $data['validasi']=$this->Usersmodel->get_all_validasi_by($sess_branch);;
        }

        $data['edit_sales']=$edit_sales=$this->Salesmodel->get_sales_by_id($psb_id); 
        if(empty($edit_sales)){
            redirect('Admin/home');
        }
        $data['paket']=$this->Paketmodel->get_all();
        $data['psb_id'] = $psb_id;

        if($action=='asyn'){
            $this->load->view('content/sales/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function sales_export($action="",$branch_id='',$tgl='',$status='',$from_date='',$to_date=''){
        if($action=="asyn"){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "MSISDN", "NAMA_PELANGGAN", "BRANCH", "PAKET", "SLA", "TL", "SALES_PERSON", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "FA_ID", "ACCOUNT_ID", "ALAMAT", "ALAMAT2", "DISCOUNT (RB)", "PERIODE (BULAN)", "BILL CYCLE", "CHANNEL", "SUB SALES CHANNEL", "JENIS EVENT", "NAMA EVENT", "VALIDATOR", "AKTIVATOR", "STATUS", "KETERANGAN", "TANGGAL_INPUT (SYSTEM)", "TANGGAL_UPDATE (SYSTEM)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            if($branch_id == 17){
                $sales = $this->Salesmodel->get_all_cari_all($branch_id,$tgl,$status,$from_date,$to_date);
            }else{
                $sales = $this->Salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);
            }

            $excel_row = 2;

            foreach($sales as $row)
            {
                if(empty($row->sla)){ 
                    if(!empty($row->tanggal_aktif)){
                        $tgl_aktif = new DateTime($row->tanggal_aktif);
                    }else{
                        $tgl_aktif = new DateTime();
                    }
                    $tgl_masuk = new DateTime($row->tanggal_masuk);
                    $diff = $tgl_aktif->diff($tgl_masuk); 
                    $sla = $diff->d." Hari";
                }else{
                    $sla = strtoupper($row->sla)." Hari"; 
                }

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $sla);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->TL));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                // $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($diff->d));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($row->fa_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, strtoupper($row->account_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, strtoupper($row->alamat));
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, strtoupper($row->alamat2));
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, strtoupper($discount[$row->discount]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, strtoupper($periode[$row->periode]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, strtoupper($row->bill_cycle));
                $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, strtoupper($channel[$row->sales_channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, strtoupper($row->sub_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, strtoupper($jenis_event[$row->jenis_event]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, strtoupper($row->nama_event));
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, strtoupper($row->validator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, strtoupper($row->aktivator));
                $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, strtoupper($row->status));
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, strtoupper($row->deskripsi));
                $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, strtoupper($row->tanggal_input));
                $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, strtoupper($row->tanggal_update));
                $excel_row++;
            }

            $filename = "Sales-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller sales

    //controller sales_channel
        public function saleschannel_view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['sales_channel']=$this->Sales_channelmodel->get_all(); 
        }else{
            $data['sales_channel']=$this->Sales_channelmodel->get_all_by($sess_branch); 
        }
        
        if($action=='asyn'){
            $this->load->view('content/sales_channel/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_channel/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function saleschannel_add($action='',$param1='')
    {
        $data['branch']=$this->Branchmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/sales_channel/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_channel/add',$data);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                     =addslashes($this->input->post('action',true));     
            $data['sales_channel']  =addslashes($this->input->post('sales_channel',true)); 
            $data['branch_id']      =addslashes($this->input->post('branch_id',true)); 
            $data['sub_channel']    =addslashes($this->input->post('sub_channel',true)); 
            $data['username']       =addslashes($this->input->post('username',true));  
       
            //-----Validation-----//   
            $this->form_validation->set_rules('sales_channel', 'Nama Channel', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('sub_channel', 'Sub Channel', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');

            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('sales_channel',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=$this->input->post('id_channel',true);
                    
                    $this->db->where('id_channel', $id);
                    $this->db->update('sales_channel', $data);

                    echo "true";

                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('sales_channel', array('id_channel' => $param1));       
        }
    }

    /** Method For get sales_channel information for Branch Edit **/ 
    public function saleschannel_edits($sales_channel_id,$action='')
    {
        $data=array();
        $data['edit_sales_channel']=getOld("id_channel",$sales_channel_id,"sales_channel");
        $data['branch']=$this->Branchmodel->get_all();  
        if($action=='asyn'){
            $this->load->view('content/sales_channel/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_channel/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function saleschannel_export($action=""){
        if($action=="asyn"){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');

            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "CHANNEL", "BRANCH", "SUB_CHANNEL");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $sub_channel = $this->Sales_channelmodel->get_all();

            }else{
                $sub_channel = $this->Sales_channelmodel->get_all_by($sess_branch);
            }
            
            $excel_row = 2;

            foreach($sub_channel as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_channel));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($channel[$row->sales_channel]));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->nama_branch));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->sub_channel));
                $excel_row++;
            }

            $filename = "SubChannel-Exported-on-".date("Y-m-d-H-i-s").".xls";

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
    //end controller sales_channel

    //controller salesperson
        public function salesperson_view($action='')
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
    public function salesperson_add($action='',$param1='')
    {
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_all_by($branch_id);  
            $data['pilihid']=$this->Usersmodel->get_all_tl_branch($branch_id); 
        }else{
            $data['pilihbranch']=$this->Branchmodel->get_all();
            $data['pilihid']=$this->Usersmodel->get_all_tl();
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
           // $data['user_sales1']    = $user_sales1 = str_replace(" ", "_", addslashes($this->input->post('user_sales1',true)));     
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
                    $user_sales1      = addslashes($this->input->post('user_sales_change',true));
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
    public function salesperson_edits($id_sales,$action='')
    {
        $data=array();
        $data['edit_salesperson']=getOld("id_sales",$id_sales,"sales_person");
        $branch_id = $this->session->userdata('branch_id');
        $level = $this->session->userdata('branch_id');
        if($this->session->userdata('level')!=4){
            $data['pilihbranch']=$this->Branchmodel->get_all_by($branch_id);  
            $data['pilihid']=$this->Usersmodel->get_all_tl_branch($branch_id); 
        }else{
            $data['pilihbranch']=$this->Branchmodel->get_all();
            $data['pilihid']=$this->Usersmodel->get_all_tl();
        }
        if($action=='asyn'){
            $this->load->view('content/sales_person/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/sales_person/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }   

    public function salesperson_export($action=""){
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

            $sess_branch = $this->session->userdata('branch_id');
            if($this->session->userdata('level')==4){
                $salesperson = $this->Salespersonmodel->get_all();    
            }else{
                $salesperson = $this->Salespersonmodel->get_all_by($sess_branch);
            }
            

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
    //end controller salesperson

    //controller users
        public function users_varifyUser(){
        if($this->session->userdata('logged_in')==TRUE){
            redirect('Admin');    
        }
        $username   =addslashes($this->input->post('username',true));
        $password   =addslashes($this->input->post('password',true));
        
        $this->form_validation->set_rules('username','Username','trim|strip_tags|xss_clean|required|min_length[3]');
        $this->form_validation->set_rules('password','Password','trim|xss_clean|required');

        if(!$this->form_validation->run() == FALSE){
            if($this->Usermodel->login($username,md5($password))){
                echo "true";    
            }else{
                echo "False";
            }
            
        }else{
            echo "False";
        }

    }
       
    //Method for logout
    public function users_logout(){
        $this->Usermodel->logout();
        redirect('User');
    }
    //end controller users

    //controller user
    public function users_view($action='')
    {   

        $data=array();
        $data['users']=$this->Usersmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/users/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/list',$data);
            $this->load->view('theme/include/footer');
        }
    }
    
    /** Method For Add New Account and Account Page View **/    
    public function users_add($action='',$param1='')
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
            $data['keterangan']     =addslashes($this->input->post('uketerangan',true));

            $data['username'] = $username = str_replace(" ", "_", addslashes($this->input->post('uusername',true)));         
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('unama', 'Nama', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('uno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('ubranch_id', 'Branch ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('uchannel', 'Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ulevel', 'Level', 'trim|required|xss_clean');
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
    public function users_edits($users_id,$action='')
    {
        $data=array();
        $data['edit_users']=$this->Usersmodel->get_users_by_id($users_id); 
        $data['branch']=$this->Branchmodel->get_all();
        if($action=='asyn'){
            $this->load->view('content/users/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/users/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }
    
    public function users_export($action=""){
        if($action=="asyn"){
            $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'FOS ALR', 6=>'Validasi GraPARI', 7=>'FOS GraPARI', 8=>'Admin CTP');
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

            $users = $this->Usersmodel->get_all(); 

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
    //end controller user

    //controller upload
    public function upload_update()
    {
        $fileName = $this->input->post('file', TRUE);

        $config['upload_path'] = './assets/excel/'; 
        $config['file_name'] = $fileName;
        $config['allowed_types'] = '*';
        $config['encrypt_name']= TRUE;
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
  
        if (!$this->upload->do_upload('file')) {
            $error = $this->upload->display_errors();
                $this->session->set_flashdata('pesan',$error); 
                redirect(''); 
        }else {
            $media = $this->upload->data();
            $inputFileName = './assets/excel/'.$media['file_name'];
   
            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++){  
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);
                $data = array(
                    "order_id"=>$rowData[0][0],
                    "order_submit_date"=>str_replace("'", "", $rowData[0][1]),
                    "order_completed_date"=>str_replace("'", "", $rowData[0][2]),
                    "msisdn_ctp"=>$rowData[0][3],
                    "nama_pelanggan_ctp"=>$rowData[0][4],
                    "order_type_name"=>$rowData[0][5],
                    "user_id"=>$rowData[0][6],
                    "employee_name"=>$rowData[0][7],
                    "paket_id"=>$rowData[0][8],
                    "id_users"=>$this->input->post('id_users_up'),
                    "branch_id"=>$this->input->post('branch_id_up'),
                    "tgl_upload"=>date('Y-m-d h:i:s'));
                $msisdn_ctp = $rowData[0][3];
                $cek_msisdn =  $this->db->query("select count(msisdn_ctp) jml from ctp where msisdn_ctp='$msisdn_ctp'")->row();
                if($cek_msisdn->jml<1){
                    $insert = $this->db->insert("ctp",$data);
                }
            } 
            unlink($inputFileName); // hapus file temp
            $count = $highestRow;
            $this->session->set_flashdata('pesan','Upload berhasil, Total: <b>'.$count.'</b> data.'); 
            redirect('ctp/view');

            }
        }
        //controller upload

        //controller upload_msisdn
        public function uploadmsisdn_update()
        {
                $fileName = $this->input->post('file', TRUE);

                $config['upload_path'] = './assets/excel/'; 
                $config['file_name'] = $fileName;
                $config['allowed_types'] = '*';
                $config['encrypt_name']= TRUE;
                $config['max_size'] = 10000000;

                $this->load->library('upload', $config);
                $this->upload->initialize($config); 
                
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('pesan',$error); 
                    redirect(''); 
                } else {
                    $media = $this->upload->data();
                    $inputFileName = './assets/excel/'.$media['file_name'];

                    try {
                        $inputFileType = IOFactory::identify($inputFileName);
                        $objReader = IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFileName);
                    } catch(Exception $e) {
                        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $no=0;
                    for ($row = 2; $row <= $highestRow; $row++){  
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                    NULL,
                                                    TRUE,
                                                    FALSE);
                        $data = array(
                            "msisdn"=>$rowData[0][0],
                            "tipe"=>$rowData[0][1],
                            "id_users"=>$this->input->post('id_users_up'),
                            "branch_id"=>$this->input->post('branch_id_up'),
                            "tanggal"=>date('Y-m-d h:i:s'));
                        $msisdn     = $rowData[0][0];
                        $cek_msisdn =  $this->db->query("select count(msisdn) jml from msisdn where msisdn='$msisdn'")->row();
                        if($cek_msisdn->jml<1){
                            $insert = $this->db->insert("msisdn",$data);
                        }
                        $no = $no+1;
                    } 
                  unlink($inputFileName); // hapus file temp
                  $count = $no;
                  $this->session->set_flashdata('pesan','Upload Data Berhasil, Total: <b>'.$count.'</b> data.'); 
                  redirect('msisdn/view');
                }
              }
              //end upload_msisdn
}