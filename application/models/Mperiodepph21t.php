<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperiodepph21t extends CI_Model {
	var $table = 'trans_periode_pph21_tarif';

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

	public function delete_by_id($periode,$periode_penggajian,$segmen,$jenis,$nik,$kode_tarif){
		$this->db->where('periode', $periode);
		$this->db->where('periode_penggajian', $periode_penggajian);
		$this->db->where('segmen', $segmen);
		$this->db->where('jenis', $jenis);
		$this->db->where('nik', $nik);
		$this->db->where('kode_tarif', $kode_tarif);
		$this->db->delete($this->table);
	}
}
