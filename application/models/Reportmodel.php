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
		$sess_level = $this->session->userdata('level');
		$sess_branch = $this->session->userdata('branch_id');
		if($this->session->userdata('level')==4){
			$max_query=$this->db->query("SELECT max(DATE_FORMAT(tanggal_masuk,'%Y-%m-%d')) as tgl_max FROM new_psb");
		}else{
			$max_query=$this->db->query("SELECT max(DATE_FORMAT(tanggal_masuk,'%Y-%m-%d')) as tgl_max FROM new_psb where branch_id='".$sess_branch."'");
		}
			
		$max_result = $max_query->row();

		return $max_result;
	}

	public function get_tanggal(){
		$q_tanggal = $this->db->query("select * from t_tanggal")->result();
		return $q_tanggal;
	}

	public function getCurMont($tgl=""){
		//Get Current Day PSB, Expense AND Current Month PSB
		date_default_timezone_set(get_current_setting('timezone'));	
		$date=$tgl;	
		// $date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		//Current Day PSB
		$current_query=$this->db->query("SELECT count(a.psb_id) as amount, sum(b.harga_paket) as revenue  
			FROM new_psb a
			JOIN paket b ON a.paket_id=b.paket_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d')='".$date."'");	
		$current_result=$current_query->row();

		//Current Month PSB
		$curmonth_query=$this->db->query("SELECT count(a.psb_id) as amount, sum(b.harga_paket) as revenue 
			FROM new_psb a 
			JOIN paket b ON a.paket_id=b.paket_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'");	
		$curmonth_result=$curmonth_query->row();

		$transaction=array(
			"current_day_psb"=>isset($current_result->amount) ? number_format($current_result->amount) : number_format(0),
			"current_day_revenue"=> isset($current_result->revenue) ? number_format($current_result->revenue) : number_format(0),
			"current_month_psb"=> isset($curmonth_result->amount) ? number_format($curmonth_result->amount) : number_format(0),
			"current_month_revenue"=> isset($curmonth_result->revenue) ? number_format($curmonth_result->revenue) : number_format(0),

		);	

		return $transaction;
	}

	public function dayByDaySales($tgl=""){
		$sukses=array();		
		$pending=array();

		$date=$tgl;	
		// $date=date("Y-m-d");	
		$date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		// $date2=$this->db->query("SELECT LAST_DAY('".$date."') as d")->row()->d;

		$sukses_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$date."') as m_name FROM new_psb where
		status='sukses' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
		'".$date."' GROUP BY tanggal_aktif")->result();

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
		while (strtotime($date1) <= strtotime($date)) {
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

	public function dayByDaySalesBranch($awal,$akhir){
		$central_jakpusel =array();  // 5		
		$cirebon =array(); //6
		$bogor =array(); //8
		$karawang =array(); //9
		$banten =array(); //11
		$tasikmalaya =array(); //12
		$bandung =array(); //14
		$jakarta_barat =array(); //16
		$central_jakutim =array(); //18
		$soreang =array(); //19

		$central_jakpusel_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='5' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$cirebon_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='6' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$bogor_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='8' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$karawang_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='9' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$banten_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='11' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$tasikmalaya_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='12' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$bandung_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='14' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$jakarta_barat_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='16' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$central_jakutim_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='18' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		$soreang_query=$this->db->query("SELECT DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') as tanggal_aktif, count(psb_id) as amount, MONTHNAME('".$akhir."') as m_name FROM new_psb where
		status='sukses' AND branch_id='19' AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') between 
		'".$awal."' AND '".$akhir."' 
		GROUP BY tanggal_aktif")->result();

		//For central_jakpusel
		$maxcentral_jakpusel=count($central_jakpusel_query);
		$i=0;
		//For cirebon
		$maxcirebon=count($cirebon_query);
		$j=0;
		//For bogor
		$maxbogor=count($bogor_query);
		$k=0;
		//For karawang
		$maxkarawang=count($karawang_query);
		$l=0;
		//For banten
		$maxbanten=count($banten_query);
		$m=0;
		//For tasikmalaya
		$maxtasikmalaya=count($tasikmalaya_query);
		$n=0;
		//For bandung
		$maxbandung=count($bandung_query);
		$o=0;
		//For jakarta_barat
		$maxjakarta_barat=count($jakarta_barat_query);
		$p=0;
		//For central_jakutim
		$maxcentral_jakutim=count($central_jakutim_query);
		$q=0;
		//For soreang
		$maxsoreang=count($soreang_query);
		$r=0;

		$day=1;
		while (strtotime($awal) <= strtotime($akhir)) {
			//For central_jakpusel
			if($maxcentral_jakpusel>0){
				if($awal==$central_jakpusel_query[$i]->tanggal_aktif){	
					$central_jakpusel[$day]=array(
					"amount"=>$central_jakpusel_query[$i]->amount,
					"date"=>$awal,
					"m_name"=>$central_jakpusel_query[$i]->m_name
					);

					if($i<($maxcentral_jakpusel-1)){$i++;}
				}else{
					$central_jakpusel[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$central_jakpusel_query[$i]->m_name);;	
				}
			}
			//End central_jakpusel

			//For cirebon
			if($maxcirebon>0){
				if($awal==$cirebon_query[$j]->tanggal_aktif){	
					$cirebon[$day]=array(
						"amount"=>$cirebon_query[$j]->amount,
						"date"=>$awal,
						"m_name"=>$cirebon_query[$j]->m_name
						);
					if($j<($maxcirebon-1)){$j++;}
				}else{
					$cirebon[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$cirebon_query[$j]->m_name);;	
				}
			}
			//End cirebon

			//For bogor
			if($maxbogor>0){
				if($awal==$bogor_query[$k]->tanggal_aktif){	
					$bogor[$day]=array(
						"amount"=>$bogor_query[$k]->amount,
						"date"=>$awal,
						"m_name"=>$bogor_query[$k]->m_name
						);
					if($k<($maxbogor-1)){$k++;}
				}else{
					$bogor[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$bogor_query[$k]->m_name);;	
				}
			}
			//End bogor

			//For karawang
			if($maxkarawang>0){
				if($awal==$karawang_query[$l]->tanggal_aktif){	
					$karawang[$day]=array(
						"amount"=>$karawang_query[$l]->amount,
						"date"=>$awal,
						"m_name"=>$karawang_query[$l]->m_name
						);
					if($l<($maxkarawang-1)){$l++;}
				}else{
					$karawang[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$karawang_query[$l]->m_name);;	
				}
			}
			//End karawang

			//For banten
			if($maxbanten>0){
				if($awal==$banten_query[$m]->tanggal_aktif){	
					$banten[$day]=array(
						"amount"=>$banten_query[$m]->amount,
						"date"=>$awal,
						"m_name"=>$banten_query[$m]->m_name
						);
					if($m<($maxbanten-1)){$m++;}
				}else{
					$banten[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$banten_query[$m]->m_name);;	
				}
			}
			//End banten

			//For tasikmalaya
			if($maxtasikmalaya>0){
				if($awal==$tasikmalaya_query[$n]->tanggal_aktif){	
					$tasikmalaya[$day]=array(
						"amount"=>$tasikmalaya_query[$n]->amount,
						"date"=>$awal,
						"m_name"=>$tasikmalaya_query[$n]->m_name
						);
					if($n<($maxtasikmalaya-1)){$n++;}
				}else{
					$tasikmalaya[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$tasikmalaya_query[$n]->m_name);;	
				}
			}
			//End tasikmalaya

			//For bandung
			if($maxbandung>0){
				if($awal==$bandung_query[$o]->tanggal_aktif){	
					$bandung[$day]=array(
						"amount"=>$bandung_query[$o]->amount,
						"date"=>$awal,
						"m_name"=>$bandung_query[$o]->m_name
						);
					if($o<($maxbandung-1)){$o++;}
				}else{
					$bandung[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$bandung_query[$o]->m_name);;	
				}
			}
			//End bandung

			//For jakarta_barat
			if($maxjakarta_barat>0){
				if($awal==$jakarta_barat_query[$p]->tanggal_aktif){	
					$jakarta_barat[$day]=array(
						"amount"=>$jakarta_barat_query[$p]->amount,
						"date"=>$awal,
						"m_name"=>$jakarta_barat_query[$p]->m_name
						);
					if($p<($maxjakarta_barat-1)){$p++;}
				}else{
					$jakarta_barat[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$jakarta_barat_query[$p]->m_name);;	
				}
			}
			//End jakarta_barat

			//For central_jakutim
			if($maxcentral_jakutim>0){
				if($awal==$central_jakutim_query[$q]->tanggal_aktif){	
					$central_jakutim[$day]=array(
						"amount"=>$central_jakutim_query[$q]->amount,
						"date"=>$awal,
						"m_name"=>$central_jakutim_query[$q]->m_name
						);
					if($q<($maxcentral_jakutim-1)){$q++;}
				}else{
					$central_jakutim[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$central_jakutim_query[$q]->m_name);;	
				}
			}
			//End central_jakutim

			//For soreang
			if($maxsoreang>0){
				if($awal==$soreang_query[$r]->tanggal_aktif){	
					$soreang[$day]=array(
						"amount"=>$soreang_query[$r]->amount,
						"date"=>$awal,
						"m_name"=>$soreang_query[$r]->m_name
						);
					if($r<($maxsoreang-1)){$r++;}
				}else{
					$soreang[$day]=array("amount"=>0,"date"=>$awal,"m_name"=>$soreang_query[$r]->m_name);;	
				}
			}
			//End soreang

			$day++;
			$awal = date ("Y-m-d", strtotime("+1 day", strtotime($awal)));
		}
		// return array($sukses,$pending);
		return array($central_jakpusel, $cirebon, $bogor, $karawang, $banten, $tasikmalaya, $bandung, $jakarta_barat, $central_jakutim, $soreang);
	}

	//get top branch information 
	public function getTopBranch($limit=0, $lm='', $tgl='')
	{
		$date = $tgl;
		// $last_date=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		// $date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		$sukses_query=$this->db->query("SELECT b.branch_id, b.nama_branch, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND branch_id=b.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			COUNT(a.psb_id) AS amount 
			FROM new_psb a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$tgl."' GROUP BY b.nama_branch ORDER BY amount DESC LIMIT ".$limit."
			")->result();

		$result=$sukses_query;
		return $result;
	}

	//get top paket information 
	public function getTopPaket($limit=0, $lm='', $tgl='')
	{
		$date = $tgl;
		// $last_date=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		// $date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND paket_id=b.paket_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			count(a.psb_id) as amount 
			FROM new_psb a 
			JOIN paket b ON a.paket_id=b.paket_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
				'".$tgl."' GROUP BY b.nama_paket order by amount desc limit ".$limit." ")->result();

		$result=$paket_query;
		return $result;

	}

	//get top TL information 
	public function getTopTL($limit=0, $lm='', $tgl='')
	{
		$date = $tgl;
		// $last_date=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		// $date1=$this->db->query("SELECT ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) as d")->row()->d;
		$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND TL=b.username AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			count(a.psb_id) as amount 
			FROM new_psb a
			JOIN app_users b on a.TL=b.username
			JOIN branch c on a.branch_id=c.branch_id
			WHERE a.status='sukses' AND b.level='3' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
				'".$tgl."' GROUP BY b.nama order by amount desc limit ".$limit." ")->result();

		$result=$tl_query;
		return $result;

	}

	//get top channel information 
	public function getTopChannel($limit=0, $lm='', $tgl='')
	{

		$channel_query=$this->db->query("SELECT a.sales_channel, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND sales_channel=a.sales_channel AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			count(a.psb_id) as amount 
			FROM new_psb a
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$tgl."',INTERVAL 1 MONTH)), 1) AND
				'".$tgl."' GROUP BY a.sales_channel order by amount desc limit ".$limit." ")->result();

		$result=$channel_query;
		return $result;

	}

	//get report branch information 
	public function getReportBranch($tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		$branch_query=$this->db->query("SELECT b.branch_id, b.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
			IFNULL((SELECT COUNT(branch_id) 
				FROM new_psb 
				WHERE STATUS='".$status."' AND branch_id=b.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			COUNT(a.branch_id) AS this_month 
			FROM new_psb a 
			right JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.nama_branch ORDER BY this_month DESC
			")->result();

		$result=$branch_query;
		return $result;

	}

	//get report paket information 
	public function getReportPaket($branch_id,$tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.paket_id) as this_month 
				FROM new_psb a 
				right JOIN paket b ON a.paket_id=b.paket_id
				JOIN branch c ON c.branch_id=a.branch_id
				WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') between 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
					'".$date."' GROUP BY a.paket_id, c.branch_id order by this_month desc ")->result();
		}else{
			$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.paket_id) as this_month 
				FROM new_psb a 
				right JOIN paket b ON a.paket_id=b.paket_id
				JOIN branch c ON c.branch_id=a.branch_id
				WHERE c.branch_id='".$branch_id."' and a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') between 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
					'".$date."' GROUP BY a.paket_id, c.branch_id order by this_month desc ")->result();
		}
		

		$result=$paket_query;
		return $result;

	}

	//get report TL information 
	public function getReportTL($branch_id,$tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.psb_id) as this_month 
				FROM new_psb a 
				right JOIN app_users b on a.TL=b.username
				JOIN branch c on a.branch_id=c.branch_id
				WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.username, c.branch_id ORDER BY this_month DESC
				")->result();
		}else{
			$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.psb_id) as this_month 
				FROM new_psb a 
				right JOIN app_users b on a.TL=b.username
				JOIN branch c on a.branch_id=c.branch_id
				WHERE c.branch_id='".$branch_id."' and a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.username, c.branch_id ORDER BY this_month DESC
				")->result();
		}
		$result=$tl_query;
		return $result;

	}

	//get report Sub Channel information 
	public function getReportSubChannel($branch_id,$tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$tl_query=$this->db->query("SELECT a.sales_channel, b.sub_channel, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.psb_id) as this_month 
				FROM new_psb a 
				left JOIN sales_channel b on a.sub_sales_channel=b.id_channel
				JOIN branch c on a.branch_id=c.branch_id
				WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.sub_channel, a.sales_channel, c.nama_branch ORDER BY this_month DESC
				")->result();
		}else{
			$tl_query=$this->db->query("SELECT a.sales_channel, b.sub_channel, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				count(a.psb_id) as this_month 
				FROM new_psb a 
				left JOIN sales_channel b on a.sub_sales_channel=b.id_channel
				JOIN branch c on a.branch_id=c.branch_id
				WHERE c.branch_id='".$branch_id."' and a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.sub_channel, a.sales_channel, c.nama_branch ORDER BY this_month DESC
				")->result();
		}

		$result=$tl_query;
		return $result;

	}

	//get report sales person information 
	public function getReportSalesPerson($branch_id,$tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$tl_query=$this->db->query("SELECT b.nama_sales, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				count(a.psb_id) as this_month
				FROM new_psb a 
				right JOIN sales_person b on a.sales_person=b.nama_sales
				JOIN branch c on a.branch_id=c.branch_id
				WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY c.nama_branch, b.nama_sales ORDER BY this_month DESC
				")->result();
		}else{
			$tl_query=$this->db->query("SELECT b.nama_sales, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				count(a.psb_id) as this_month
				FROM new_psb a 
				right JOIN sales_person b on a.sales_person=b.nama_sales
				JOIN branch c on a.branch_id=c.branch_id
				WHERE c.branch_id='".$branch_id."' and a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY c.nama_branch, b.nama_sales ORDER BY this_month DESC
				")->result();
		}
		$result=$tl_query;
		return $result;

	}

	//get report sales person information 
	public function getReportServiceLevel($tanggal,$status,$to_date)
	{
		$date = $to_date;
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		$service_query=$this->db->query("SELECT c.nama_branch, count(a.psb_id) jumlah, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as this_month
			FROM new_psb a 
			JOIN branch c on a.branch_id=c.branch_id
			WHERE a.status='".$status."' AND DATE_FORMAT(a.".$tanggal.", '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY c.nama_branch ORDER BY this_month DESC
			")->result();

		$result=$service_query;
		return $result;

	}

	public function getContrStatus($tgl = ''){
		$date=$tgl;		
		$sukses=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='sukses' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$reject=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='reject' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$retur=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='retur' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$pending=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='pending' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$cancel=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='cancel' AND DATE_FORMAT(tanggal_validasi,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$bentrok=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='bentrok' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$masuk=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='masuk' AND DATE_FORMAT(tanggal_masuk,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$blacklist=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='blacklist' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

		$valid=$this->db->query("SELECT count(psb_id) as amount 
			FROM new_psb
			WHERE status='valid' AND DATE_FORMAT(tanggal_aktif,'%Y-%m-%d') between 
			ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'")->row();

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
INTERVAL 1 MONTH)), 1) AND '".$date."'");	
$curmonth_income_result=$query1->row();

//Current Month Expense
$query2=$this->db2->query("SELECT sum(amount) as amount FROM `transaction` 
WHERE type='Expense'And trans_date between ADDDATE(LAST_DAY(SUBDATE('".$date."',
INTERVAL 1 MONTH)), 1) AND '".$date."'");	
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