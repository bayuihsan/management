<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin_model model
 *
 * This class handles admin_model management related functionality
 *
 * @package		Admin
 * @subpackage	admin_model
 * @author		propertyjar
 * @link		#
 */

class Custom_fieldsmodel extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('hvc',TRUE);
	}
	
	function get_all()
	{
			$this->db->select('a.*, b.nama');
			$this->db->join('app_users b', 'b.id_users = a.id_users');
			return $this->db->get('custom_fields a')->result();
	}
	
}