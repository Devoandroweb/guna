<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtpgajikaryawan extends CI_Model {
	var $table = 'vtrans_periode_gaji_karyawan';
    var $tables = 'master_gaji_karyawan';
    var $tab = 'trans_periode_gaji_karyawan';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $sql = "CALL vtrans_periode_gaji_karyawan";
        $parameters = array();
        $query = $this->db->query($sql, $parameters);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->tables, $data);
        return;
    }

    function delete($nik,$periode){
        $this->db->where('nik', $nik);
        $this->db->where('periode', $periode);
        $this->db->delete($this->tab);
    }

}
