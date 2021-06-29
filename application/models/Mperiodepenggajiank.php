<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperiodepenggajiank extends CI_Model {
	var $table = 'master_periode_penggajian_komponen';

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

	public function delete_by_id($periode_penggajian,$segmen,$kode_gaji){
		$this->db->where('periode_penggajian', $periode_penggajian);
		$this->db->where('segmen', $segmen);
		$this->db->where('kode_gaji', $kode_gaji);
		$this->db->delete($this->table);
	}
}
