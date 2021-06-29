<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgajikaryawan extends CI_Model {
	var $table = 'v_master_gaji_karyawan';
    var $tables = 'master_gaji_karyawan';

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

    function add_record($data,$ot) {
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $this->db->insert($this->tables, $data);
        $this->db->query("INSERT INTO master_gaji_karyawan_periode VALUES ('$data[nik]','$data[kode_gaji]','$ot','$data[nilai_gaji]','$last','$nm')");
        return;
    }

    function add_header(){
        $string_query = "SELECT master_gaji_karyawan.nik, master_karyawan.nama, master_gaji.keterangan, master_gaji_karyawan.nilai_gaji
FROM (master_gaji INNER JOIN master_gaji_karyawan ON master_gaji.kode_gaji = master_gaji_karyawan.kode_gaji) INNER JOIN master_karyawan ON master_gaji_karyawan.nik = master_karyawan.nik";
        $query = $this->db->query($string_query);
        return $query->result();
    }

    function get_peri($nik){
        $query = "SELECT master_gaji_karyawan.nik, master_karyawan.nama, master_gaji.keterangan, master_gaji_karyawan.nilai_gaji
FROM (master_gaji INNER JOIN master_gaji_karyawan ON master_gaji.kode_gaji = master_gaji_karyawan.kode_gaji) INNER JOIN master_karyawan ON master_gaji_karyawan.nik = master_karyawan.nik
where master_gaji_karyawan.nik='$nik'";
        //$query = $this->db->query($string_query);
        return;
    }

    public function delete_by_id($nik){
        $this->db->where('nik', $nik);
        $this->db->delete($this->tables);
    }

    function insert_csv($in) {
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        //$this->db->query("INSERT INTO master_gaji_karyawan VALUES ('$in[nik]','$in[kode_gaji]','$in[nilai_gaji]','$last','$nm')");

        $this->db->query("INSERT INTO master_gaji_karyawan VALUES ('$in[nik]','$in[kode_gaji]','$in[nilai_gaji]','$last','$nm') 
            ON DUPLICATE KEY UPDATE nilai_gaji='$in[nilai_gaji]',lastupdate='$last',user_id='$nm'");
            //return;
        return;
    }

}
