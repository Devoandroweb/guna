<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpphp extends CI_Model {
	var $table = 'master_pph21_ptkp';

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

	public function delete_by_id($status_perkawinan){
		$this->db->where('status_perkawinan', $status_perkawinan);
		$this->db->delete($this->table);
	}
}
