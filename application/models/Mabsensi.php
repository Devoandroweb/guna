<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsensi extends CI_Model {
	var $table = 'content_ketidakhadiran';

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

	public function delete_by_id($nilai_ketidakhadiran){
		$this->db->where('nilai_ketidakhadiran', $nilai_ketidakhadiran);
		$this->db->delete($this->table);
	}
}
