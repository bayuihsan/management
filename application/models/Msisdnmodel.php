<?php

class Msisdnmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all   
	public function get_all(){
		$query = $this->db->query("select a.*, b.nama_branch, c.nama
			FROM msisdn a 
			LEFT JOIN branch b ON a.branch_id=b.branch_id
			LEFT JOIN app_users c ON a.id_users=c.id_users
			ORDER BY a.tanggal desc
			");
		$query_result=$query;
		$result=$query_result->result();
		return $result;
	} 

	//get all Branch  
	public function get_all_by($branch_id){
		$id_tl = $this->session->userdata('id_users');
		$level = $this->session->userdata('level');
		if ($level == 5){ // FOS
			$query =	$this->db->query("select a.*, b.nama_branch, c.nama
				FROM msisdn a 
				LEFT JOIN branch b ON a.branch_id=b.branch_id
				LEFT JOIN app_users c ON a.id_users=c.id_users
				WHERE  a.branch_id='".$branch_id."' 
				ORDER BY a.tanggal desc
				");
		} else {
			$query = $this->db->query("select a.*, b.nama_branch, c.nama
				FROM msisdn a 
				LEFT JOIN branch b ON a.branch_id=b.branch_id
				LEFT JOIN app_users c ON a.id_users=c.id_users
				WHERE  a.branch_id='".$branch_id."' and c.id_users='".$id_tl."'
				ORDER BY a.tanggal desc
				");
		}
		$query_result=$query;
		$result=$query_result->result();
		return $result;

	}

	//get all Branch  
	public function get_all_by_status($branch_id){
		$query = $this->db->query("select a.*, b.nama_branch, c.nama
			FROM msisdn a 
			LEFT JOIN branch b ON a.branch_id=b.branch_id
			LEFT JOIN app_users c ON a.id_users=c.id_users
			WHERE  a.branch_id='".$branch_id."' and a.status in('')
			ORDER BY a.tanggal desc
			");
		$query_result=$query;
		$result=$query_result->result();
		return $result;
	}

	//get branch by id  
	public function get_msisdn_by_id($id_haloinstan){
		$this->db->select('*');
		$this->db->from('msisdn');
		$this->db->where('id_haloinstan',$id_haloinstan);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}