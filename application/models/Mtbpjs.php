<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtbpjs extends CI_Model {
	var $table = 'vlist_bpjs_periode';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $sql = "CALL vlist_bpjs_periode";
        $parameters = array();
        $query = $this->db->query($sql, $parameters);
        return $query->result();
    }
}
