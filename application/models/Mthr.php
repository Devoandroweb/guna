<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mthr extends CI_Model {
	var $table = 'vthr';
    var $tables = 'master_gaji_karyawan_periode';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $sql = "CALL vlist_gaji_karyawan";
        $parameters = array();
        $query = $this->db->query($sql, $parameters);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->tables, $data);
        return;
    }

    function add_header(){
        $string_query = "SELECT master_gaji_karyawan.nik, master_karyawan.nama, master_gaji.keterangan, master_gaji_karyawan.nilai_gaji
FROM (master_gaji INNER JOIN master_gaji_karyawan ON master_gaji.kode_gaji = master_gaji_karyawan.kode_gaji) INNER JOIN master_karyawan ON master_gaji_karyawan.nik = master_karyawan.nik";
        $query = $this->db->query($string_query);
        return $query->result();
    }

    function get_peri($nik,$periode){
        $query = "SELECT master_gaji_karyawan.nik, master_karyawan.nama, master_gaji.keterangan, master_gaji_karyawan.nilai_gaji
FROM (master_gaji INNER JOIN master_gaji_karyawan ON master_gaji.kode_gaji = master_gaji_karyawan.kode_gaji) INNER JOIN master_karyawan ON master_gaji_karyawan.nik = master_karyawan.nik
where master_gaji_karyawan.nik='$nik' and master_gaji_karyawan.periode='$periode'";
        //$query = $this->db->query($string_query);
        return;
    }

    public function delete_by_id($nik){
        $this->db->where('nik', $nik);
        $this->db->delete($this->tables);
    }

}
