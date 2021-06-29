<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsenjk extends CI_Model {
	var $table = 'master_absen_jadwal_karyawan';

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

    public function delete_by_id($nik,$tanggal){
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete($this->table);
    }

}
