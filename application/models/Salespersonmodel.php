<?php

class Salespersonmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db2->select('*');
		$this->db2->from('sales_person');  
		$this->db2->order_by("id_sales", "desc");    
		$query_result=$this->db2->get();
		$result=$query_result->result();
		return $result;
	}

	public function get_databranch(){
		$this->db2->select('*');
		$this->db2->from('branch');     
		$query_result=$this->db2->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_sales_by_id($id_sales){
		$this->db2->select('*');
		$this->db2->from('sales_person');
		$this->db2->where('id_sales',$id_sales);    
		$query_result=$this->db2->get();
		$result=$query_result->row();
		return $result;
	} 
}