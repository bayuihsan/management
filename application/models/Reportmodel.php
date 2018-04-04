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
		$date=date('Y-m-d', strtotime($tgl));	
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

		$date=date('Y-m-d', strtotime($tgl));
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
		$awal=date('Y-m-d', strtotime($awal));
		$akhir=date('Y-m-d', strtotime($akhir));
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
	public function getTopBranch($limit=0, $ly='', $lm='', $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$ly=date('Y-m-d', strtotime($ly));
		$lm=date('Y-m-d', strtotime($lm));
		$sukses_query=$this->db->query("SELECT b.branch_id, b.nama_branch, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND branch_id=b.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND branch_id=b.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			COUNT(a.psb_id) AS amount 
			FROM new_psb a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' GROUP BY b.nama_branch ORDER BY amount DESC LIMIT ".$limit."
			")->result();

		$result=$sukses_query;
		return $result;
	}

	//get top paket information 
	public function getTopPaket($limit=0, $ly='', $lm='', $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$ly=date('Y-m-d', strtotime($ly));
		$lm=date('Y-m-d', strtotime($lm));
		$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND paket_id=b.paket_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND paket_id=b.paket_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			count(a.psb_id) as amount 
			FROM new_psb a 
			JOIN paket b ON a.paket_id=b.paket_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
				'".$date."' GROUP BY b.nama_paket order by amount desc limit ".$limit." ")->result();

		$result=$paket_query;
		return $result;

	}

	//get top TL information 
	public function getTopTL($limit=0, $ly='', $lm='', $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$ly=date('Y-m-d', strtotime($ly));
		$lm=date('Y-m-d', strtotime($lm));
		$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND TL=b.username AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
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
				'".$date."' GROUP BY b.nama order by amount desc limit ".$limit." ")->result();

		$result=$tl_query;
		return $result;

	}

	//get top tsa 30 information 
	public function getSumTSA($limit=0, $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$sukses_query=$this->db->query("SELECT b.branch_id, b.nama_branch, a.sales_person, count(a.psb_id) jumlah
			FROM new_psb a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' 
			GROUP BY b.nama_branch, a.sales_person ORDER BY jumlah DESC LIMIT ".$limit."
			")->result();

		$result=$sukses_query;
		return $result;
	}

	//get top tl 200 information 
	public function getSumTL($limit=0, $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$sukses_query=$this->db->query("SELECT b.branch_id, b.nama_branch, a.tl, count(a.psb_id) jumlah
			FROM new_psb a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."' 
			GROUP BY b.nama_branch, a.tl ORDER BY jumlah DESC LIMIT ".$limit."
			")->result();

		$result=$sukses_query;
		return $result;
	}

	//get top channel information 
	public function getTopChannel($limit=0, $ly='', $lm='', $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		$ly=date('Y-m-d', strtotime($ly));
		$lm=date('Y-m-d', strtotime($lm));
		$channel_query=$this->db->query("SELECT a.sales_channel, 
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND sales_channel=a.sales_channel AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
			IFNULL((SELECT COUNT(psb_id) 
				FROM new_psb 
				WHERE STATUS='sukses' AND sales_channel=a.sales_channel AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			count(a.psb_id) as amount 
			FROM new_psb a
			WHERE a.status='sukses' AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') between 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND
				'".$date."' GROUP BY a.sales_channel order by amount desc limit ".$limit." ")->result();

		$result=$channel_query;
		return $result;

	}

	//get report fee sales TSAinformation 
	public function getFeeSales($branch_id, $to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		// $lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));	
		if($branch_id=='17'){ //ALL
			$fee_query=$this->db->query("SELECT a.*, b.nama_branch, c.nama,
				IFNULL((SELECT COUNT(x.psb_id) 
					FROM new_psb x 
					LEFT JOIN paket y ON x.paket_id=y.paket_id 
					WHERE x.STATUS='sukses' AND x.sales_person=a.nama_sales AND y.id_kategori='2' AND DATE_FORMAT(x.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketkurang',
				IFNULL((SELECT COUNT(p.psb_id) 
					FROM new_psb p 
					LEFT JOIN paket q ON p.paket_id=q.paket_id 
					WHERE p.STATUS='sukses' AND p.sales_person=a.nama_sales AND q.id_kategori='1' AND DATE_FORMAT(p.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketlebih'
				FROM sales_person a
				LEFT JOIN branch b on b.branch_id=a.branch_id
				LEFT JOIN app_users c ON a.id_users=c.id_users
				WHERE a.status='Aktif'
				ORDER BY b.nama_branch, c.nama, a.nama_sales
				")->result();
		}else{
			$fee_query=$this->db->query("SELECT a.*, b.nama_branch, c.nama,
				IFNULL((SELECT COUNT(x.psb_id) 
					FROM new_psb x 
					LEFT JOIN paket y ON x.paket_id=y.paket_id 
					WHERE x.STATUS='sukses' AND x.sales_person=a.nama_sales AND y.id_kategori='2' AND DATE_FORMAT(x.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketkurang',
				IFNULL((SELECT COUNT(p.psb_id) 
					FROM new_psb p 
					LEFT JOIN paket q ON p.paket_id=q.paket_id 
					WHERE p.STATUS='sukses' AND p.sales_person=a.nama_sales AND q.id_kategori='1' AND DATE_FORMAT(p.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketlebih'
				FROM sales_person a
				LEFT JOIN branch b on b.branch_id=a.branch_id
				LEFT JOIN app_users c ON a.id_users=c.id_users
				WHERE a.status='Aktif' and b.branch_id='".$branch_id."'
				ORDER BY b.nama_branch, c.nama, a.nama_sales
				")->result();
		}
		$result=$fee_query;
		return $result;

	}

	//get report fee sales TL information 
	public function getFeeSalesTL($branch_id, $to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		// $lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));	
		if($branch_id=='17'){ //ALL
			$fee_query=$this->db->query("SELECT a.id_users, a.channel, a.nama, b.nama_branch,
				IFNULL((SELECT COUNT(x.psb_id) 
					FROM new_psb x 
					LEFT JOIN paket y ON x.paket_id=y.paket_id 
					WHERE x.STATUS='sukses' AND x.TL=a.username AND y.id_kategori='2' AND DATE_FORMAT(x.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketkurang',
				IFNULL((SELECT COUNT(p.psb_id) 
					FROM new_psb p 
					LEFT JOIN paket q ON p.paket_id=q.paket_id 
					WHERE p.STATUS='sukses' AND p.TL=a.username AND q.id_kategori='1' AND DATE_FORMAT(p.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketlebih'
				FROM app_users a
				LEFT JOIN branch b on b.branch_id=a.branch_id
				WHERE a.keterangan='Aktif' and a.level='3'
				ORDER BY b.nama_branch, a.channel, a.nama
				")->result();
		}else{
			$fee_query=$this->db->query("SELECT a.id_users, a.channel, a.nama, b.nama_branch,
				IFNULL((SELECT COUNT(x.psb_id) 
					FROM new_psb x 
					LEFT JOIN paket y ON x.paket_id=y.paket_id 
					WHERE x.STATUS='sukses' AND x.TL=a.username AND y.id_kategori='2' AND DATE_FORMAT(x.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketkurang',
				IFNULL((SELECT COUNT(p.psb_id) 
					FROM new_psb p 
					LEFT JOIN paket q ON p.paket_id=q.paket_id 
					WHERE p.STATUS='sukses' AND p.TL=a.username AND q.id_kategori='1' AND DATE_FORMAT(p.tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'jml_paketlebih'
				FROM app_users a
				LEFT JOIN branch b on b.branch_id=a.branch_id
				WHERE a.keterangan='Aktif' and a.level='3' AND b.branch_id='".$branch_id."'
				ORDER BY b.nama_branch, a.channel, a.nama
				")->result();
		}
		$result=$fee_query;
		return $result;

	}

	//get report branch information 
	public function getReportBranch($tanggal,$status,$ly,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		$branch_query=$this->db->query("SELECT b.branch_id, b.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
			IFNULL((SELECT COUNT(branch_id) 
				FROM new_psb 
				WHERE STATUS='".$status."' AND branch_id=b.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
			IFNULL((SELECT COUNT(branch_id) 
				FROM new_psb 
				WHERE STATUS='".$status."' AND branch_id=b.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
			IFNULL((SELECT COUNT(branch_id) 
				FROM new_psb 
				WHERE STATUS='".$status."' AND branch_id=b.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
					ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
			FROM new_psb a 
			RIGHT JOIN branch b ON a.branch_id=b.branch_id
			WHERE b.branch_id not in('17')
			GROUP BY b.nama_branch ORDER BY this_month DESC
			")->result();

		$result=$branch_query;
		return $result;

	}

	//sla global for hvc and grapari
	public function getReportSLA($tanggal,$status,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		//$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		$branch_query=$this->db->query("SELECT z.branch_id, z.nama_branch,  
			IFNULL((SELECT COUNT(a.psb_id) 
				FROM new_psb a 
				RIGHT JOIN bast_header c ON a.no_bast = c.no_bast 
				WHERE a.STATUS='sukses' AND a.branch_id=z.branch_id AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'
				),0) AS 'jumlah_grapari',
			IFNULL((SELECT SUM(DATEDIFF(a.tanggal_aktif, c.tanggal_terima)) 
				FROM new_psb a 
				RIGHT JOIN bast_header c ON a.no_bast = c.no_bast 
				WHERE a.STATUS='sukses' AND a.branch_id=z.branch_id AND DATE_FORMAT(a.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'
				),0) AS 'sla_grapari',
			IFNULL((SELECT COUNT(b.psb_id) 
				FROM new_psb b 
				RIGHT JOIN bast_header d ON b.no_bast = d.no_bast 
				WHERE b.STATUS='masuk' AND b.branch_id=z.branch_id  AND DATE_FORMAT(b.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'
				),0) AS 'jumlah_hvc',
			IFNULL((SELECT SUM(DATEDIFF(d.tanggal_terima, b.tanggal_masuk)) 
				FROM new_psb b 
				RIGHT JOIN bast_header d ON b.no_bast = d.no_bast 
				WHERE b.STATUS='masuk' AND b.branch_id=z.branch_id  AND DATE_FORMAT(b.tanggal_aktif, '%Y-%m-%d') BETWEEN 
				ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'
				),0) AS 'sla_hvc'
			FROM branch z
			WHERE z.branch_id NOT IN('17')")->result();
		$result=$branch_query;
		return $result;
	}

	//get report paket information 
	public function getReportPaket($branch_id,$tanggal,$status,$ly,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		$ly=date('Y-m-d', strtotime($ly));
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
				FROM new_psb a 
				right JOIN paket b ON a.paket_id=b.paket_id
				JOIN branch c ON c.branch_id=a.branch_id
				GROUP BY a.paket_id, c.branch_id order by this_month desc ")->result();
		}else{
			$paket_query=$this->db->query("SELECT b.paket_id, b.nama_paket, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(paket_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND paket_id=b.paket_id AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
				FROM new_psb a 
				right JOIN paket b ON a.paket_id=b.paket_id
				JOIN branch c ON c.branch_id=a.branch_id
				WHERE c.branch_id='".$branch_id."' 
				GROUP BY a.paket_id, c.branch_id order by this_month desc ")->result();
		}
		

		$result=$paket_query;
		return $result;

	}

	//get report TL information 
	public function getReportTL($branch_id,$tanggal,$status,$ly,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		$ly=date('Y-m-d', strtotime($ly));
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
				FROM new_psb a 
				right JOIN app_users b on a.TL=b.username
				JOIN branch c on a.branch_id=c.branch_id
				GROUP BY b.username, c.branch_id ORDER BY this_month DESC
				")->result();
		}else{
			$tl_query=$this->db->query("SELECT b.id_users, b.username, b.nama, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND TL=b.username AND branch_id = c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
				FROM new_psb a 
				right JOIN app_users b on a.TL=b.username
				JOIN branch c on a.branch_id=c.branch_id
				WHERE c.branch_id='".$branch_id."' 
				GROUP BY b.username, c.branch_id ORDER BY this_month DESC
				")->result();
		}
		$result=$tl_query;
		return $result;

	}

	//get report Sub Channel information 
	public function getReportSubChannel($branch_id,$tanggal,$status,$ly,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
		$ly=date('Y-m-d', strtotime($ly));
		$lm = date('Y-m-d', strtotime('-1 month', strtotime( $date )));
		if($branch_id=='17'){ //ALL
			$tl_query=$this->db->query("SELECT a.sales_channel, b.sub_channel, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'last_month'
				FROM new_psb a 
				left JOIN sales_channel b on a.sub_sales_channel=b.id_channel
				JOIN branch c on a.branch_id=c.branch_id
				GROUP BY b.sub_channel, a.sales_channel, c.nama_branch ORDER BY this_month DESC
				")->result();
		}else{
			$tl_query=$this->db->query("SELECT a.sales_channel, b.sub_channel, c.nama_branch, SUM(DATEDIFF(a.".$tanggal.", a.tanggal_masuk)) as sla,
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$ly."',INTERVAL 1 MONTH)), 1) AND '".$ly."'),0) AS 'last_year',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$lm."',INTERVAL 1 MONTH)), 1) AND '".$lm."'),0) AS 'last_month',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='".$status."' AND sub_sales_channel=a.sub_sales_channel AND sales_channel=a.sales_channel AND branch_id=c.branch_id AND DATE_FORMAT(".$tanggal.", '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'this_month'
				FROM new_psb a 
				left JOIN sales_channel b on a.sub_sales_channel=b.id_channel
				JOIN branch c on a.branch_id=c.branch_id
				WHERE c.branch_id='".$branch_id."' 
				GROUP BY b.sub_channel, a.sales_channel, c.nama_branch ORDER BY this_month DESC
				")->result();
		}

		$result=$tl_query;
		return $result;

	}

	//get report sales person information 
	public function getReportSalesPerson($branch_id,$tanggal,$status,$to_date)
	{
		$date=date('Y-m-d', strtotime($to_date));
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

	//get report status daily branch information 
	public function getStatusDaily($branch_id, $st, $tgl, $tahun, $bulan)
	{
		if($bulan<10){
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}
		$status_query = $this->db->query("select 
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-01'),0) AS 'satu', 
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-02'),0) AS 'dua',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-03'),0) AS 'tiga',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-04'),0) AS 'empat',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-05'),0) AS 'lima',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-06'),0) AS 'enam',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-07'),0) AS 'tujuh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-08'),0) AS 'delapan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-09'),0) AS 'sembilan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-10'),0) AS 'sepuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-11'),0) AS 'sebelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-12'),0) AS 'duabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-13'),0) AS 'tigabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-14'),0) AS 'empatbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-15'),0) AS 'limabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-16'),0) AS 'enambelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-17'),0) AS 'tujuhbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-18'),0) AS 'delapanbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-19'),0) AS 'sembilanbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-20'),0) AS 'duapuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-21'),0) AS 'duasatu',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-22'),0) AS 'duadua',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-23'),0) AS 'duatiga',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-24'),0) AS 'duaempat',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-25'),0) AS 'dualima',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-26'),0) AS 'duaenam',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-27'),0) AS 'duatujuh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-28'),0) AS 'duadelapan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-29'),0) AS 'duasembilan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-30'),0) AS 'tigapuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND branch_id=a.branch_id AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-31'),0) AS 'tigasatu'    
                from branch a
                where branch_id='".$branch_id."'
            ")->row();
		$result=$status_query;
		return $result;

	}

	//get report status daily branch information 
	public function getStatusDailyAll($st, $tgl, $tahun, $bulan)
	{
		if($bulan<10){
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
		}
		$status_query = $this->db->query("select 
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-01'),0) AS 'satu', 
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-02'),0) AS 'dua',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-03'),0) AS 'tiga',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-04'),0) AS 'empat',
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-05'),0) AS 'lima',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-06'),0) AS 'enam',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-07'),0) AS 'tujuh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-08'),0) AS 'delapan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-09'),0) AS 'sembilan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-10'),0) AS 'sepuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-11'),0) AS 'sebelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-12'),0) AS 'duabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-13'),0) AS 'tigabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-14'),0) AS 'empatbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-15'),0) AS 'limabelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-16'),0) AS 'enambelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-17'),0) AS 'tujuhbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-18'),0) AS 'delapanbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-19'),0) AS 'sembilanbelas',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-20'),0) AS 'duapuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-21'),0) AS 'duasatu',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-22'),0) AS 'duadua',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-23'),0) AS 'duatiga',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-24'),0) AS 'duaempat',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-25'),0) AS 'dualima',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-26'),0) AS 'duaenam',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-27'),0) AS 'duatujuh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-28'),0) AS 'duadelapan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-29'),0) AS 'duasembilan',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-30'),0) AS 'tigapuluh',    
			IFNULL((SELECT COUNT(psb_id) FROM new_psb WHERE STATUS='".$st."' AND DATE_FORMAT($tgl,'%Y-%m-%d')='".$tahun."-".$bulan."-31'),0) AS 'tigasatu'    
                from branch limit 1
            ")->row();
		$result=$status_query;
		return $result;

	}

	public function getContrStatus($tgl = ''){
		$date=date('Y-m-d', strtotime($tgl));
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

	//get top branch information 
	public function getStatusBranch($opsi='', $tgl='')
	{
		$date=date('Y-m-d', strtotime($tgl));
		if($opsi == "opsi1"){
			$sukses_query=$this->db->query("SELECT a.branch_id, a.nama_branch, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='sukses' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'sukses',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='valid' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'valid',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='cancel' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'cancel',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='reject' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'reject',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='pending' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'pending',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='retur' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'retur',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='bentrok' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'bentrok',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='blacklist' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'blacklist',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='masuk' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'masuk'
				FROM branch a 
				where branch_id not in('17')
				ORDER BY sukses desc")->result();
		}else if($opsi == "opsi2"){
			$sukses_query=$this->db->query("SELECT a.branch_id, a.nama_branch, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='sukses' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'sukses',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='sukses' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'sukses_masuk',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='valid' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'valid',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='cancel' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'cancel',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='reject' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'reject',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='pending' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'pending',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='retur' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'retur',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='bentrok' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'bentrok',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='blacklist' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'blacklist',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='masuk' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'masuk'
				FROM branch a 
				where branch_id not in('17')
				ORDER BY sukses desc")->result();
		}else{
			$sukses_query=$this->db->query("SELECT a.branch_id, a.nama_branch, 
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='sukses' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'sukses',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='valid' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'valid',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='cancel' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'cancel',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='reject' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'reject',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='pending' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'pending',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='retur' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_validasi, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'retur',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='bentrok' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'bentrok',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='blacklist' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_aktif, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'blacklist',
				IFNULL((SELECT COUNT(psb_id) 
					FROM new_psb 
					WHERE STATUS='masuk' AND branch_id=a.branch_id AND DATE_FORMAT(tanggal_masuk, '%Y-%m-%d') BETWEEN 
						ADDDATE(LAST_DAY(SUBDATE('".$date."',INTERVAL 1 MONTH)), 1) AND '".$date."'),0) AS 'masuk'
				FROM branch a 
				where branch_id not in('17')
				ORDER BY sukses desc")->result();
		}

		$result=$sukses_query;
		return $result;
	}

}