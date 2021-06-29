<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mstatusaktual extends CI_Model {
	var $table = 'content_status_aktual';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->table, $data);
        return;
    }

	public function delete_by_id($status_aktual){
		$this->db->where('status_aktual', $status_aktual);
		$this->db->delete($this->table);
	}
}
