<?php

class Feedbacksmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db->select('a.*, b.nama');
		$this->db->from('feedback a');  
		$this->db->join('app_users b', 'b.id_users = a.id_users');
		$this->db->order_by("a.nama_pelanggan", "asc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	public function get_datafeedback(){
		$this->db->select('*');
		$this->db->from('feedback');     
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_feedback_by_id($id_feedback){
		$this->db->select('*');
		$this->db->from('feedback');
		$this->db->where('id_feedback',$id_feedback);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}