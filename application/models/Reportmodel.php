<?php

class ReportModel extends CI_Model{

// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE); 
	}

	public function getMaxDate(){
		//get Max Date
		$max_query=$this->db->query("SELECT max(DATE_FORMAT(tanggal_aktif,'%Y-%m-%d')) as tgl_max FROM new_psb 
		WHERE status='sukses'");	
		$max_result = $max_query->row();

		return $max_result;
	}

	public function getCurMont($tgl=""){
		//Get Current Day PSB, Expense AND Current Month PSB
		date_default_timezone_set(get_current_setting('timezone'));	
		$date=$tgl;	
		//Current Day PSB
		$current_query=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d')='".$date."'");	
		$current_result=$current_query->row();

		//Current Month PSB
		$curmonth_query=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
		INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
		$curmonth_result=$curmonth_query->row();

		//Current Day Revenue
		$currev_query=$this->db->query("SELECT sum(b.harga_paket) as amount 
			FROM new_psb a
			JOIN paket b ON a.paket_id=b.paket_id 
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d')='".$date."'");	
		$currev_result=$currev_query->row();

		//Current Month PSB
		$monthrev_query=$this->db->query("SELECT sum(b.harga_paket) as amount 
			FROM new_psb a
			JOIN paket b ON a.paket_id=b.paket_id 
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
		INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
		$monthrev_result=$monthrev_query->row();

		$transaction=array(
			"current_day_psb"=>isset($current_result->amount) ? number_format($current_result->amount) : number_format(0),
			"current_month_psb"=> isset($curmonth_result->amount) ? number_format($curmonth_result->amount) : number_format(0),
			"current_day_revenue"=> isset($currev_result->amount) ? number_format($currev_result->amount) : number_format(0),
			"current_month_revenue"=> isset($monthrev_result->amount) ? number_format($monthrev_result->amount) : number_format(0),

		);	

		return $transaction;
	}

	public function dayByDaySales($tgl=""){
		$sukses=array();		
		$pending=array();

		$date=$tgl;	
		// $date=date("Y-m-d");	
		$date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		$date2=$this->db->query("SELECT LAST_DAY('".$date."') as d")->row()->d;

		$sukses_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$date."') as m_name FROM new_psb where
		status='sukses' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
		LAST_DAY('".$date."') GROUP BY tanggal_aktif")->result();

		// $pending_query=$this->db->query("SELECT DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') as tanggal_validasi ,count(psb_id) as amount,MONTHNAME('".$date."') as m_name FROM new_psb where
		// status='pending' AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') between 
		// ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
		// LAST_DAY('".$date."') GROUP BY tanggal_validasi")->result();

		//For sukses
		$maxsukses=count($sukses_query);
		$i=0;
		//For pending
		// $maxpending=count($pending_query);
		// $j=0;

		$day=1;
		while (strtotime($date1) <= strtotime($date2)) {
			//For sukses
			if($maxsukses>0){
				if($date1==$sukses_query[$i]->tanggal_aktif){	
					$sukses[$day]=array(
					"amount"=>$sukses_query[$i]->amount,
					"date"=>$date1,
					"m_name"=>$sukses_query[$i]->m_name
					);

					if($i<($maxsukses-1)){$i++;}
				}else{
					$sukses[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$sukses_query[$i]->m_name);;	
				}
			}
			//End sukses

			//For pending
			// if($maxpending>0){
			// 	if($date2==$pending_query[$j]->tanggal_validasi){	
			// 		$pending[$day]=array(
			// 			"amount"=>$pending_query[$j]->amount,
			// 			"date"=>$date2,
			// 			"m_name"=>$pending_query[$j]->m_name
			// 			);
			// 		if($j<($maxpending-1)){$j++;}
			// 	}else{
			// 		$pending[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$pending_query[$j]->m_name);;	
			// 	}
			// }
			$day++;
			$date1 = date ("Y-m-d", strtotime("+1 day", strtotime($date1)));
		}
		// return array($sukses,$pending);
		return array($sukses);
	}

	//get top branch information 
	public function getTopBranch($limit=0,$tgl='')
	{

		$sukses_query=$this->db->query("SELECT b.branch_id, b.nama_branch, MONTHNAME('".$tgl."') as m_name, count(a.psb_id) as amount 
			FROM new_psb a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND
				LAST_DAY('".$tgl."') GROUP BY b.nama_branch order by amount desc limit ".$limit." ")->result();

		$result=$sukses_query;
		return $result;

	}

	//get top paket information 
	public function getTopPaket($limit=0,$tgl='')
	{

		$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, MONTHNAME('".$tgl."') as m_name, count(a.psb_id) as amount 
			FROM new_psb a 
			JOIN paket b ON a.paket_id=b.paket_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND
				LAST_DAY('".$tgl."') GROUP BY b.nama_paket order by amount desc limit ".$limit." ")->result();

		$result=$paket_query;
		return $result;

	}

	//get top channel information 
	public function getTopChannel($limit=0,$tgl='')
	{

		$channel_query=$this->db->query("SELECT sales_channel, MONTHNAME('".$tgl."') as m_name, count(psb_id) as amount 
			FROM new_psb 
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND
				LAST_DAY('".$tgl."') GROUP BY sales_channel order by amount desc limit ".$limit." ")->result();

		$result=$channel_query;
		return $result;

	}

	//get top TL information 
	public function getTopTL($limit=0,$tgl='')
	{

		$tl_query=$this->db->query("SELECT b.id_users, b.nama, c.nama_branch, count(a.psb_id) as amount 
			FROM new_psb a
			JOIN app_users b on a.TL=b.username
			JOIN branch c on a.branch_id=c.branch_id
			WHERE a.status='sukses' AND b.level='3' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND
				LAST_DAY('".$tgl."') GROUP BY b.nama order by amount desc limit ".$limit." ")->result();

		$result=$tl_query;
		return $result;

	}

	public function getContrStatus($tgl = ''){
		$date=$tgl;		
		$sukses=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$reject=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='reject' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$retur=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='retur' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$pending=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='pending' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$cancel=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='cancel' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$bentrok=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='bentrok' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$masuk=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='masuk' AND DATE_FORMAT(tanggal_masuk,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$blacklist=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='blacklist' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		$valid=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='valid' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')")->row();

		return array(
			"sukses"=>$sukses->amount,
			"reject"=>$reject->amount,
			"retur"=>$retur->amount,
			"pending"=>$pending->amount,
			"cancel"=>$cancel->amount,
			"bentrok"=>$bentrok->amount,
			"masuk"=>$masuk->amount,
			"blacklist"=>$blacklist->amount,
			"valid"=>$valid->amount,
		);

	}


public function getIncomeExpense(){
//Get Current Day Income, Expense AND Current Month Income, Expense
date_default_timezone_set(get_current_setting('timezone'));	
$date=date("Y-m-d");
//Current Day Income
$income_query=$this->db2->query("SELECT sum(amount) as amount FROM transaction 
WHERE type='Income' AND trans_date='".$date."'");	
$income_result=$income_query->row();
//Current Day Expense
$expense_query=$this->db2->query("SELECT sum(amount) as amount FROM transaction 
WHERE type='Expense' AND trans_date='".$date."'");	
$expense_result=$expense_query->row();

//Current Month Income
$query1=$this->db2->query("SELECT sum(amount) as amount FROM `transaction` 
WHERE type='Income'And trans_date between ADDDATE(LAST_DAY(SUBDATE('".$date."',
INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
$curmonth_income_result=$query1->row();

//Current Month Expense
$query2=$this->db2->query("SELECT sum(amount) as amount FROM `transaction` 
WHERE type='Expense'And trans_date between ADDDATE(LAST_DAY(SUBDATE('".$date."',
INTERVAL 1 MONTH)), 1) AND LAST_DAY('".$date."')");	
$curmonth_expense_result=$query2->row();


$transaction=array(
"current_day_income"=>isset($income_result->amount) ? decimalPlace($income_result->amount) : decimalPlace(0),
"current_day_expense"=> isset($expense_result->amount) ? decimalPlace($expense_result->amount) : decimalPlace(0),
"current_month_income"=> isset($curmonth_income_result->amount) ? decimalPlace($curmonth_income_result->amount) : decimalPlace(0),
"current_month_expense"=> isset($curmonth_expense_result->amount) ? decimalPlace($curmonth_expense_result->amount) : decimalPlace(0)

);	

return $transaction;
}	

public function dayByDayIncomeExpense(){
$income=array();		
$expense=array();

$date=date("Y-m-d");	
$date1=$this->db2->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
$date2=$this->db2->query("SELECT LAST_DAY('".$date."') as d")->row()->d;

$income_query=$this->db2->query("SELECT trans_date,sum(amount) as amount,MONTHNAME('".$date."') as m_name FROM transaction where
type='Income' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."') GROUP BY trans_date")->result();

$expense_query=$this->db2->query("SELECT trans_date,sum(amount) as amount,MONTHNAME('".$date."') as m_name FROM transaction where
type='Expense' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."') GROUP BY trans_date")->result();

//For Income
$maxIncome=count($income_query);
$i=0;
//For Expense
$maxExpense=count($expense_query);
$j=0;

$day=1;
while (strtotime($date1) <= strtotime($date2)) {
//For Income
if($maxIncome>0){
if($date1==$income_query[$i]->trans_date){	
$income[$day]=array(
"amount"=>$income_query[$i]->amount,
"date"=>$date1,
"m_name"=>$income_query[$i]->m_name
);

if($i<($maxIncome-1)){$i++;}
}else{
$income[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$income_query[$i]->m_name);;	
}
}
//End Income

//For Expense
if($maxExpense>0){
if($date1==$expense_query[$j]->trans_date){	
$expense[$day]=array(
	"amount"=>$expense_query[$j]->amount,
	"date"=>$date1,
	"m_name"=>$expense_query[$j]->m_name
	);
if($j<($maxExpense-1)){$j++;}
}else{
$expense[$day]=array("amount"=>0,"date"=>$date1,"m_name"=>$expense_query[$j]->m_name);;	
}
}
$day++;
$date1 = date ("Y-m-d", strtotime("+1 day", strtotime($date1)));
}
return array($income,$expense);
}

//
public function sumOfIncomeExpense(){
$date=date("Y-m-d");	
$income=$this->db2->query("SELECT sum(amount) as amount from transaction
WHERE type='Income' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."')")->row();

$expense=$this->db2->query("SELECT sum(amount) as amount from transaction
WHERE type='Expense' AND trans_date between 
ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
LAST_DAY('".$date."')")->row();

return array("income"=>$income->amount,"expense"=>$expense->amount);

}

public function financialBalance(){
$query=$this->db2->query("SELECT a.accounts_name as account,(SELECT bal FROM transaction
WHERE accounts_name=a.accounts_name ORDER BY trans_id DESC LIMIT 1) 
as balance FROM accounts as a");
$result=$query->result();
return $result;	
}

//Start Report Page
public function getAccountStatement($account,$from_date,$to_date,$trans_type){
if($trans_type=='All'){	
return $this->db2->query("SELECT trans_date,note,dr,cr,bal FROM
 transaction WHERE accounts_name='".$account."' AND trans_date between
 '".$from_date."' AND '".$to_date."'")->result();	
}else{
return $this->db2->query("SELECT trans_date,note,dr,cr,bal FROM 
transaction WHERE accounts_name='".$account."' AND $trans_type>0 AND trans_date
 between '".$from_date."' AND '".$to_date."'")->result();
}

}

//Day wise and date wise Income Report

public function getIncomeReport($from_date,$to_date,$group=''){
if($group=="group"){
return $this->db2->query("SELECT trans_date,sum(amount) as amount FROM
	transaction WHERE type='Income' AND trans_date between '".$from_date."'
	AND '".$to_date."' GROUP BY trans_date")->result();	
}else{
return $this->db2->query("SELECT * FROM transaction WHERE type='Income'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}
}

//Day wise and date wise Income Report

public function getExpenseReport($from_date,$to_date,$group=''){
if($group=="group"){
return $this->db2->query("SELECT trans_date,sum(amount) as amount FROM
	transaction WHERE type='Expense' AND trans_date between '".$from_date."'
	AND '".$to_date."' GROUP BY trans_date")->result();	
}else{
return $this->db2->query("SELECT * FROM transaction WHERE type='Expense'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}
}

//Transfer Report

public function getTransferReport($from_date,$to_date){
return $this->db2->query("SELECT * FROM transaction WHERE type='Transfer'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		

}

//Category Report
public function getCategoryReport($from_date,$to_date,$cat){
return $this->db2->query("SELECT * FROM transaction WHERE category='".$cat."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}

//Payer Report
public function getPayerReport($from_date,$to_date,$payer){
return $this->db2->query("SELECT * FROM transaction WHERE payer='".$payer."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}

//Payee Report
public function getPayeeReport($from_date,$to_date,$payee){
return $this->db2->query("SELECT * FROM transaction WHERE payee='".$payee."'
AND trans_date between '".$from_date."' AND '".$to_date."'")->result();		
}


}