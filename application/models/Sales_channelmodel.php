<?php

class Sales_channelmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all sales channel  
	public function get_all(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->order_by("a.sub_channel", "asc");    
		$query_result=$this->db->get('sales_channel a');
		$result=$query_result->result();
		return $result;
	}

	//get sales channel by id  
	public function get_sales_channel_by_id($id_channel){
		$this->db->select('*');
		$this->db->from('sales_channel');
		$this->db->where('id_channel',$id_channel);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get sales channel by id  
	public function get_all_by($branch_id){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('sales_channel a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->where('a.branch_id',$branch_id);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 
}