<?php

class Churnmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all sales churn  
	public function get_all(){
		$this->db->select('a.*, b.nama_branch, c.nama');
		$this->db->join('branch b','b.branch_id=a.branch_id');
		$this->db->join('app_users c','c.id_users=a.updated_by');
		$this->db->order_by("b.nama_branch, a.tanggal_churn", "asc");    
		$query_result=$this->db->get('churn a');
		$result=$query_result->result();
		return $result;
	}

	//get sales churn by id  
	public function get_churn_by_id($id_churn){
		$this->db->select('*');
		$this->db->from('churn');
		$this->db->where('id_churn',$id_churn);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get sales churn by id  
	public function get_all_by($branch_id){
		$this->db->select('a.*, b.nama_branch, c.nama');
		$this->db->join('branch b','b.branch_id=a.branch_id');
		$this->db->join('app_users c','c.id_users=a.updated_by');
		$this->db->where('a.branch_id',$branch_id); 
		$this->db->order_by("b.nama_branch, a.tanggal_churn", "asc");    
		$query_result=$this->db->get('churn a');
		$result=$query_result->result();
		return $result;
		 
	} 
}