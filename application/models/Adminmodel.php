<?php

class AdminModel extends CI_Model{
 
 	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
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


//get all Chart Of Accounts  
public function getAllChartOfAccounts(){
$this->db2->select('*');
$this->db2->from('chart_of_accounts');  
$this->db2->order_by("chart_id", "desc");    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

} 

//get Chart Of Accounts by type 
public function getChartOfAccountByType($type){
$this->db2->select('*');
$this->db2->from('chart_of_accounts');  
$this->db2->where('accounts_type',$type);  
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

}
//get all payer and payee    
public function getAllPayeryAndPayee(){
$this->db2->select('*');
$this->db2->from('payee_payers');  
$this->db2->order_by("trace_id", "desc");    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

} 

//get payer and payee by type   
public function getPayeryAndPayeeByType($type){
$this->db2->select('*');
$this->db2->from('payee_payers');  
$this->db2->where("type", $type);    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

} 


//get all payment method   
public function getAllPaymentmethod(){
$this->db2->select('*');
$this->db2->from('payment_method');  
$this->db2->order_by("p_method_id", "desc");    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

}   
 
//get all user     
public function getAllUsers(){
$this->db2->select('*');
$this->db2->from('user');  
$this->db2->order_by("user_id", "desc");    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

}    

//get all Personal/Bank Account 
public function getAllAccounts(){
$this->db2->select('*');
$this->db2->from('accounts');    
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

}  


//get account by id  
public function getAccount($accounts_id){
$this->db2->select('*');
$this->db2->from('accounts');
$this->db2->where('accounts_id',$accounts_id);    
$query_result=$this->db2->get();
$result=$query_result->row();
return $result;
} 


//method for calculating balance
public function getBalance($account,$amount,$action)
{
if($action=='add'){
//get last balance
$query=$this->db2->query("SELECT bal FROM transaction 
WHERE accounts_name='".$account."' ORDER BY
trans_id DESC LIMIT 1");
$result=$query->row();
return $result->bal+$amount;
}else if($action=='sub'){
//get last balance
$query=$this->db2->query("SELECT bal FROM transaction 
WHERE accounts_name='".$account."' ORDER BY
trans_id DESC LIMIT 1");
$result=$query->row();
return $result->bal-$amount;

}
}


//get transaction information 
public function getTransaction($limit='',$type='')
{
	
$this->db2->select('*');
$this->db2->from('transaction');
if($type!=''){
$this->db2->where('type',$type);	
}
$this->db2->order_by("trans_id", "desc");
if($limit!='all'){ 
$this->db2->limit($limit);
}    

$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

} 


//get single transaction
public function getSingleTransaction($trans_id){
$this->db2->select('*');
$this->db2->from('transaction');
$this->db2->where('trans_id',$trans_id);
$query_result=$this->db2->get();
$result=$query_result->row();
return $result;

}


//get repeat transaction 
public function getRepeatTransaction($type,$status,$status2='')
{
	
$this->db2->select('*');
$this->db2->from('repeat_transaction');
$this->db2->where('type',$type);
if($status2!=''){
$this->db2->where_in('status',array($status,$status2));	
}else{
$this->db2->where('status',$status);
}
$query_result=$this->db2->get();
$result=$query_result->result();
return $result;

} 

//get single repeat transaction
public function getSingleRepeatTransaction($trans_id){
$this->db2->select('*');
$this->db2->from('repeat_transaction');
$this->db2->where('trans_id',$trans_id);
$query_result=$this->db2->get();
$result=$query_result->row();
return $result;

}

//Process Repeating Transaction
public function processRepeatTransaction($trans_id,$status)
{

$data['status']=$status;	
$data['pdate']=date("Y-m-d");

$this->db2->trans_begin();
$this->db2->where('trans_id',$trans_id);
$this->db2->update('repeat_transaction',$data);

$trans=$this->getSingleRepeatTransaction($trans_id);
if($trans->type=="Income"){
//insert Income
$this->insertIncome($trans->account,$trans->category,$trans->amount,$trans->payer,$trans->p_method,$trans->ref,$trans->description);

}else if($trans->type=="Expense"){
//insert Expense
$this->insertExpense($trans->account,$trans->category,$trans->amount,$trans->payee,$trans->p_method,$trans->ref,$trans->description);
}

//end transaction
if ($this->db2->trans_status() === FALSE)
{
    $this->db2->trans_rollback();
}
else
{
    $this->db2->trans_commit();
    return true;
}


}


public function insertIncome($account,$cat,$amount,$payer,$p_method,$ref,$note)
{
$data=array();	
$data['accounts_name']=$account; 
$data['trans_date']=date("Y-m-d");; 
$data['type']='Income'; 
$data['category']=$cat; 
$data['amount']=$amount; 
$data['payer']=$payer; 
$data['payee']=''; 
$data['p_method']=$p_method; 
$data['ref']=$ref; 
$data['note']=$note;  
$data['dr']=0;  
$data['cr']=$amount;
$data['bal']=$this->getBalance($account,$amount,"add");  
$this->db2->insert('transaction',$data);	
}

public function insertExpense($account,$cat,$amount,$payee,$p_method,$ref,$note)
{
$data=array();	
$data['accounts_name']=$account; 
$data['trans_date']=date("Y-m-d");; 
$data['type']='Expense'; 
$data['category']=$cat; 
$data['amount']=$amount; 
$data['payer']=''; 
$data['payee']=$payee; 
$data['p_method']=$p_method; 
$data['ref']=$ref; 
$data['note']=$note;  
$data['dr']=$amount;  
$data['cr']=0;
$data['bal']=$this->getBalance($account,$amount,"sub");  
$this->db2->insert('transaction',$data);	
}



    
}