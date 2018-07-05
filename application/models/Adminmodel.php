<?php

class Adminmodel extends CI_Model{
 
 	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}
	
	//get all settings  
	public function getAllSettings(){
		$this->db->select('*');
		$this->db->from('settings');    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	}  

	public function getLastLogin($limit = 0){
		//get Max Date
		$date = date('Y-m-d');
		$last_query=$this->db->query("SELECT a.username, b.nama_branch, a.last_login 
			FROM app_users a
			JOIN branch b ON a.branch_id=b.branch_id 
			WHERE DATE_FORMAT(a.last_login,'%Y-%m-%d')='".$date."'
			ORDER BY last_login desc
			LIMIT ".$limit." ");
		$last_result = $last_query->result();

		return $last_result;
	}    
}