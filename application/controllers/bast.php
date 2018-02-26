<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bast extends CI_Controller {

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
        redirect('bast/view');
        
    }

    public function view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast']=$this->bastmodel->get_all_by('');
        }else{
            $data['bast']=$this->bastmodel->get_all_by($sess_branch);
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
    public function create($action='', $param1='')
    {
        $data=array();  
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->bastmodel->get_all_bydate($sess_branch);
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
                        if($this->db->insert('bast_header',$data)){
                            $last_id=$this->db->insert_id();    
                            echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
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
    public function add($no_bast='',$action='',$param1='')
    {
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);;
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);;
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);;
            $datax['validasi']=$this->usersmodel->get_all_validasi_by($sess_branch);;
        }
        
        $datax['paket']=$this->Paketmodel->get_all();
        $qbast = $this->bastmodel->get_all_by_no_bast($no_bast);
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
                        $user_tl = $this->usersmodel->get_users_by_id($tl);
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

                        $user_tl = $this->usersmodel->get_users_by_id($tl);
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
            $this->db->delete('new_psb', array('psb_id' => $param1));       
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
        $data['no_bast'] = $no_bast = $this->input->post('no_bast',true);

        $data['detail_bast']=$this->salesmodel->getBASTDetail($no_bast);
        $this->load->view('content/bast/load_modal_bast',$data);
    }

    /** Method For get sales information for sales Edit **/ 
    public function edit($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);;
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);;
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);;
            $datax['validasi']=$this->usersmodel->get_all_validasi_by($sess_branch);;
        }

        $datax['edit_sales']=$this->salesmodel->get_sales_by_id($psb_id); 
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
    public function edits($id_header,$action='')
    {
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->bastmodel->get_all_bydate($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
        }

        $data['edit_bast']=$this->bastmodel->get_all_by_id($id_header); 

        if($action=='asyn'){
            $this->load->view('content/bast/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add',$data);
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

            $table_columns = array("ID", "MSISDN", "NAMA_PELANGGAN", "BRANCH", "PAKET", "TL", "SALES_PERSON", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "SERVICE (HARI)", "FA_ID", "ACCOUNT_ID", "ALAMAT", "ALAMAT2", "DISCOUNT (RB)", "PERIODE (BULAN)", "BILL CYCLE", "CHANNEL", "SUB SALES CHANNEL", "JENIS EVENT", "NAMA EVENT", "VALIDATOR", "AKTIVATOR", "STATUS", "KETERANGAN", "TANGGAL_INPUT (SYSTEM)", "TANGGAL_UPDATE (SYSTEM)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $sales = $this->salesmodel->get_all_cari($branch_id,$tgl,$status,$from_date,$to_date);

            $excel_row = 2;

            foreach($sales as $row)
            {
                if(!empty($row->tanggal_aktif)){
                    $tgl_aktif = new DateTime($row->tanggal_aktif);
                }else{
                    $tgl_aktif = new DateTime();
                }
                $tgl_masuk = new DateTime($row->tanggal_masuk);
                $diff = $tgl_aktif->diff($tgl_masuk); 

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
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper($diff->d));
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
    
   
}
