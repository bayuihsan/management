<?php

class usersModel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all users  
	public function get_all(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');  
		$this->db->join('branch b', 'b.branch_id = a.branch_id', 'LEFT');  
		$this->db->order_by("a.id_users", "desc");  
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	}


	public function get_all_tl(){  
		$query_result=$this->db->query("Select * from app_users where level='3' and keterangan='Aktif'");
		$result=$query_result->result();
		return $result;

	}

	//get users by id  
	public function get_users_by_id($users_id){
		$this->db->select('*');
		$this->db->from('app_users');
		$this->db->where('id_users',$users_id);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}