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
        $this->load->model(array('bastmodel','salesmodel','Branchmodel','Reportmodel','Sales_channelmodel','Paketmodel','usersmodel','Salespersonmodel','Sales_channelmodel','Msisdnmodel'));
    }
    
    public function index(){
        redirect('sales/view');
    }

	public function view($action='',$branch_id='',$tgl='',$status='',$from_date='',$to_date='')
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
                $data['sales'] = $this->salesmodel->get_all($max_tanggal);
                $data['branch'] = $this->Branchmodel->get_all();
            }else{
                $data['sales'] = $this->salesmodel->get_all_branch($max_tanggal, $sess_branch);
                $data['branch'] = $this->Branchmodel->get_all_by($sess_branch);

            }
            $this->load->view('content/sales/list',$data);
        }else if($action==''){
            if($this->session->userdata('level')==4){
                $data['sales'] = $this->salesmodel->get_all($max_tanggal);
                $data['branch'] = $this->Branchmodel->get_all();
            }else{
                $data['sales'] = $this->salesmodel->get_all_branch($max_tanggal, $sess_branch);
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
                $data['sales'] = $cari = $this->salesmodel->get_all_cari_all($branch_id,$tgl,$status,$from_date,$to_date);
            }else{

                $data['sales'] = $cari = $this->salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);
            }

            $this->load->view('content/sales/list',$data);
        }
    }

    //Cek MSISDN// 
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
                $msisdnData=$this->salesmodel->getMSISDN($cek);
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

    function load_modal()
    {
        $data['msisdn'] = $msisdn = $this->input->post('msisdn',true);

        $data['detail_msisdn']=$this->salesmodel->getMSISDNDetail($msisdn);
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
            $data['deskripsi']          =addslashes($this->input->post('sdekripsi',true));

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
            $this->form_validation->set_rules('sdekripsi', 'Keterangan', 'trim|required|xss_clean|min_length[5]');

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
                $account = $this->salesmodel->get_sales_by_account($account_id); 
                $fa = $this->salesmodel->get_sales_by_fa($fa_id); 
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

                        $user_tl = $this->usersmodel->get_users_by_id($tl);
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
            $qpsb=$this->salesmodel->get_sales_by_id($param1);  
            $msisdn = $qpsb->msisdn;

            $datastatus['status']             ="";
            $this->db->where('msisdn', $msisdn);
            $this->db->update('msisdn', $datastatus);

            $this->db->delete('new_psb', array('psb_id' => $param1));       
        }
	}

    /** Method For get sales information for sales Edit **/ 
    public function edit($psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $data['bast']=$this->bastmodel->get_all_by('');
            $data['msisdn']=$this->Msisdnmodel->get_all();
            $data['branch']=$this->Branchmodel->get_all();
            $data['tl']=$this->usersmodel->get_all_tl();
            $data['sub_channel']=$this->Sales_channelmodel->get_all();
            $data['sales_person']=$this->Salespersonmodel->get_all();
            $data['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $data['bast']=$this->bastmodel->get_all_by($sess_branch);
            $data['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $data['tl']=$this->usersmodel->get_all_tl_by($sess_branch);;
            $data['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);;
            $data['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);;
            $data['validasi']=$this->usersmodel->get_all_validasi_by($sess_branch);;
        }

        $data['edit_sales']=$this->salesmodel->get_sales_by_id($psb_id); 
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

    public function export($action="",$branch_id='',$tgl='',$status='',$from_date='',$to_date=''){
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

            if($branch_id == 17){
                $sales = $this->salesmodel->get_all_cari_all($branch_id,$tgl,$status,$from_date,$to_date);
            }else{
                $sales = $this->salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);
            }

            $excel_row = 2;

            foreach($sales as $row)
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
    
   
}
