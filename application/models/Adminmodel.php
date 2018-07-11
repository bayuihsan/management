<?php

class Adminmodel extends CI_Model{
 
 	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}
	
	//get all settings  
	public function getAllSettings(){
		$this->db->select('*');
		$this->db->from('settings');    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	}  

	public function getLastLogin($limit = 0){
		//get Max Date
		$date = date('Y-m-d');
		$last_query=$this->db->query("SELECT a.username, b.nama_branch, a.last_login 
			FROM app_users a
			JOIN branch b ON a.branch_id=b.branch_id 
			WHERE DATE_FORMAT(a.last_login,'%Y-%m-%d')='".$date."'
			ORDER BY last_login desc
			LIMIT ".$limit." ");
		$last_result = $last_query->result();

		return $last_result;
	}    

	public function getCurMont($ly='', $lm='', $tgl=''){
		//Get Current Day PSB, Expense AND Current Month PSB
		date_default_timezone_set(get_current_setting('timezone'));	
		$lm=date('Y-m-d', strtotime($lm));	
		$date=date('Y-m-d', strtotime($tgl));	

		//Current Month Sales
		$sales_query=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'");	
		$sales_result=$sales_query->row();

		//Last Month Sales
		$sales_lm_query=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'");	
		$sales_lm_result=$sales_lm_query->row();

		//Last Year Sales
		$sales_ly_query=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'");	
		$sales_ly_result=$sales_ly_query->row();

		//Current Month Churn
		$churn_query=$this->db->query("SELECT sum(nilai_churn) as amount 
			FROM churn 
			WHERE DATE_FORMAT(tanggal_churn,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'");	
		$churn_result=$churn_query->row();

		//Last Month Churn
		$churn_lm_query=$this->db->query("SELECT sum(nilai_churn) as amount 
			FROM churn 
			WHERE DATE_FORMAT(tanggal_churn,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'");	
		$churn_lm_result=$churn_lm_query->row();

		//Last Year Churn
		$churn_ly_query=$this->db->query("SELECT sum(nilai_churn) as amount 
			FROM churn 
			WHERE DATE_FORMAT(tanggal_churn,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'");	
		$churn_ly_result=$churn_ly_query->row();

		$transaction=array(
			"sales"=> isset($sales_result->amount) ? $sales_result->amount : 0,
			"sales_lm"=> isset($sales_lm_result->amount) ? $sales_lm_result->amount : 0,
			"sales_ly"=> isset($sales_ly_result->amount) ? $sales_ly_result->amount : 0,
			"churn"=> isset($churn_result->amount) ? $churn_result->amount : 0,
			"churn_lm"=> isset($churn_lm_result->amount) ? $churn_lm_result->amount : 0,
			"churn_ly"=> isset($churn_ly_result->amount) ? $churn_ly_result->amount : 0,
		);	

		return $transaction;
	}
}