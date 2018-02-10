SELECT a.branch_id, b.nama_branch,
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='01') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Januari',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='02') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Februari',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='03') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Maret',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='04') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'April',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='05') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Mei',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='06') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Juni',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='07') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Juli',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='08') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Agustus',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='09') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'September',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='10') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Oktober',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='11') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'November',
IFNULL((SELECT COUNT(psb_id) FROM (new_psb) WHERE ((MONTH(tanggal_aktif)='12') AND (YEAR(tanggal_aktif)='2017')) AND branch_id=a.branch_id AND STATUS='sukses'),0) AS 'Desember'
FROM new_psb a
JOIN branch b ON a.`branch_id`=b.`branch_id`
GROUP BY a.`branch_id`