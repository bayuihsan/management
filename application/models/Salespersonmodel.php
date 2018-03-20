<?php

class Salespersonmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	// public function get_all(){
	// 	$this->db->select('*');
	// 	$this->db->from('sales_person');  
	// 	$this->db->order_by("id_sales", "desc");    
	// 	$query_result=$this->db->get();
	// 	$result=$query_result->result();
	// 	return $result;
	// }

	public function get_all(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('sales_person a');  
		$this->db->join('branch b', 'b.branch_id = a.branch_id');    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get branch by id  
	public function get_sales_by_id($id_sales){
		$this->db->select('*');
		$this->db->from('sales_person');
		$this->db->where('id_sales',$id_sales);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}