<?php

class usersModel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	}

	//get all users  
	public function get_all(){
		$this->db2->select('a.*, b.nama_branch');
		$this->db2->from('app_users a');  
		$this->db2->join('branch b', 'b.branch_id = a.branch_id', 'LEFT');  
		$this->db2->order_by("a.id_users", "desc");  
		$query_result=$this->db2->get();
		$result=$query_result->result();
		return $result;

	} 

	//get users by id  
	public function get_users_by_id($users_id){
		$this->db2->select('*');
		$this->db2->from('app_users');
		$this->db2->where('id_users',$users_id);    
		$query_result=$this->db2->get();
		$result=$query_result->row();
		return $result;
	} 
}