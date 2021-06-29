<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperiodepenggajian extends CI_Model {
	var $table = 'content_periode_penggajian';

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

	public function delete_by_id($periode_penggajian){
		$this->db->where('periode_penggajian', $periode_penggajian);
		$this->db->delete($this->table);
	}
}
