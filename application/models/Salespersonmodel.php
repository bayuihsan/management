<?php

class Salespersonmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}
	
	public function get_all(){
		$this->db->select('a.*, b.nama_branch, c.nama');
		$this->db->from('sales_person a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->join('app_users c', 'c.id_users = a.id_users');
		$this->db->order_by("a.nama_sales", "asc");
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get sales person by branch  
	public function get_all_by($branch_id){
		$this->db->select('*');
		$this->db->from('sales_person');
		$this->db->where('branch_id',$branch_id);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get sales_person by id  
	public function get_salesperson_by_id($id_sales){
		$this->db->select('*');
		$this->db->from('sales_person');
		$this->db->where('id_sales',$id_sales);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get users by username 
	public function get_users_by_username($username){
		$this->db->select('*');
		$this->db->from('sales_person');
		$this->db->where('user_sales',$username);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}