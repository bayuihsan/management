<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

 public function __construct() {
        parent::__construct();
		if($this->config->item('install')=="money_TRUE"){
		redirect('User');
		}
		$this->load->helper('file');
		$this->load->library('form_validation');
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1993 05:00:00 GMT");
    }
	
	public function index(){
	$this->load->view("install/install_1");
	}
	public function finalInstall(){
	$data=array();
	$data['timezone']=timezone_list();
	$this->load->view("install/install_2",$data);
	}
	
	public function checkStep1(){
	if($this->checkConnection()){
	// Replace the database settings//////////
	$data = read_file('./application/config/database.php');
	$data = str_replace('money_DB', $this->input->post('database'), $data);
	$data = str_replace('money_UN', $this->input->post('user'), $data);
	$data = str_replace('money_PW', $this->input->post('password'), $data);
	$data = str_replace('money_HS', $this->input->post('hostname'), $data);
	write_file('./application/config/database.php', $data);
	$this->finalInstall();
	}else{
	echo "false";
	}
	}
	
		
	/** INSTALL METHOD **/
	function processInstall()
	{
		//-----Validation-----//   
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('cur-symbol', 'Currency Code', 'trim|required|min_length[1]');
		$this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');

		
		if(!$this->form_validation->run() == FALSE)
		{
			
			// Replace new default routing controller/////
			$data2 = read_file('./application/config/routes.php');
			$data2 = str_replace('Install', 'User', $data2);
			write_file('./application/config/routes.php', $data2);
			
			// Replace new default routing controller/////
			$data3 = read_file('./application/config/config.php');
			$data3 = str_replace('money_FALSE', 'money_TRUE', $data3);
			write_file('./application/config//config.php', $data3);
			
			
			// Run the installer sql DB			
			$this->load->database();
			
			$schema = read_file('./uploads/money.sql');
			
			$query      = rtrim(trim($schema), "\n;");
			$query_list = explode(";", $query);
			
			foreach ($query_list as $query)
				$this->db->query($query);
			
			// Insert User Details
			$data=array();
			$data['user_name']=$this->input->post('username',true);
			$data['fullname']="";  
			$data['email']=$this->input->post('email',true);  
			$data['user_type']='Admin';  
			$data['password']=md5($this->input->post('password',true));  
			$data['creation_date']=date("Y-m-d H:i:s");
			$this->db->insert('user',$data);
			
			// Update Company Name	
			$this->db->where('settings', 'company_name');
			$this->db->update('settings', array(
				'value' => $this->input->post('company-name')
			));
			
			// Update Currency Code
			$this->db->where('settings', 'currency_code');
			$this->db->update('settings', array(
				'value' => $this->input->post('cur-symbol')
			));
			
			// Update Timezone
			$this->db->where('settings', 'timezone');
			$this->db->update('settings', array(
				'value' => $this->input->post('timezone')
			));			
			$this->finish();	

		} else {
			//$this->session->set_flashdata('installation_result', 'failed');
			//redirect(base_url(), 'refresh');
			echo "false";
		}
	}
	
	public function finish(){
	$this->load->view('install/finish');
	}
	
	
	// -------------------------------------------------------------------------------------------------
	
	/* 
	 * Database validation check from user input settings
	 */
	function checkConnection()
	{
		$link = @mysql_connect($this->input->post('hostname'), 
		$this->input->post('user'),$this->input->post('password'));
		if (!$link) {
			@mysql_close($link);
			return false;
		}
		
		$db_selected = mysql_select_db($this->input->post('database'), $link);
		if (!$db_selected) {
			@mysql_close($link);
			return false;
		}
		
		@mysql_close($link);
		return true;
	}
		

}