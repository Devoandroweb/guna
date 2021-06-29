<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsensp extends CI_Model {
	var $table = 'master_absen_grup_shift_pola';

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

    public function delete_by_id($grup,$urutan){
        $this->db->where('grup', $grup);
        $this->db->where('urutan', $urutan);
        $this->db->delete($this->table);
    }

}
