<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgshift extends CI_Model {
	var $table = 'master_absen_grup_shift';

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

    public function delete_by_id($grup,$shift){
        $this->db->where('grup', $grup);
        $this->db->where('shift', $shift);
        $this->db->delete($this->table);
    }

}
