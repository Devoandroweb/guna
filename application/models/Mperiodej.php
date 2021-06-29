<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperiodej extends CI_Model {
	var $table = 'trans_periode_jamsostek';

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

	public function delete_by_id($periode,$periode_penggajian,$segmen,$nik,$nama_program){
		$this->db->where('periode', $periode);
		$this->db->where('periode_penggajian', $periode_penggajian);
		$this->db->where('segmen', $segmen);
		$this->db->where('nik', $nik);
		$this->db->where('nama_program', $nama_program);
		$this->db->delete($this->table);
	}
	public function select_by_nik($nik){
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('nik', $nik);
		$this->db->limit(1);
		return $this->db->get();
	}
}
