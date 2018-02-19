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
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');  
		$this->db->join('branch b', 'b.branch_id = a.branch_id', 'LEFT');  
		$this->db->order_by("a.nama", "asc");  
		$query_result=$this->db->get();
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

	//get users by username 
	public function get_users_by_username($username){
		$this->db->select('*');
		$this->db->from('app_users');
		$this->db->where('username',$username);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get users TL 
	public function get_all_tl(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');  
		$this->db->where('a.level','3');
		$this->db->where('a.keterangan','Aktif');
		$this->db->order_by("a.nama", "asc"); 
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	//get users TL by branch
	public function get_all_tl_by($branch_id){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');  
		$this->db->where('a.level','3');
		$this->db->where('a.branch_id',$branch_id);
		$this->db->where('a.keterangan','Aktif');
		$this->db->order_by("a.nama", "asc"); 
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	//get user validasi by branch
	public function get_all_validasi_by($branch_id){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');  
		$this->db->where('a.level','2');
		$this->db->where('a.branch_id',$branch_id);
		$this->db->where('a.keterangan','Aktif');
		$this->db->order_by("a.nama", "asc"); 
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	//get users validasi
	public function get_all_validasi(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');  
		$this->db->where('a.level','2');
		$this->db->where('a.keterangan','Aktif');
		$this->db->order_by("a.nama", "asc"); 
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	public function get_all_ctp(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');  
		$this->db->where('a.level','6');
		$this->db->where('a.keterangan','Aktif');
		$this->db->order_by("a.nama", "asc"); 
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	public function get_all_tl_branch($branch_id, $level){  
		$query_result=$this->db->query("Select * from app_users where level='".$level."' and branch_id='".$branch_id."' and keterangan='Aktif'");
		$result=$query_result->result();
		return $result;

	}
}