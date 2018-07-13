<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sales_channel extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        // $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('sales_channelmodel','Branchmodel'));
    }
    
    public function index(){
        redirect('sales_channel/view');
    }
	public function view($action='')
	{   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        if($this->session->userdata('level')==4){
            $data['sales_channel']=$this->sales_channelmodel->get_all(); 
        }else{
            $data['sales_channel']=$this->sales_channelmodel->get_all_by($sess_branch); 
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
    public function add($action='',$param1='')
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
    public function edit($sales_channel_id,$action='')
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

    public function export($action=""){
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
                $sub_channel = $this->sales_channelmodel->get_all();

            }else{
                $sub_channel = $this->sales_channelmodel->get_all_by($sess_branch);
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
}