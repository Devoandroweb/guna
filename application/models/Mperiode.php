<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mperiode extends CI_Model {
	var $table = 'trans_periode';

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

    public function delete_by_id($periode,$periode_penggajian,$segmen){
        $this->db->where('periode', $periode);
        $this->db->where('periode_penggajian', $periode_penggajian);
        $this->db->where('segmen', $segmen);
        $this->db->delete($this->table);
    }

    public function dele($periode){
        $this->db->where('periode', $periode);
        $this->db->delete('master_gaji_karyawan_periode');
    }
}
