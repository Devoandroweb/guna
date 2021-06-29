<?php
class Data extends CI_Model {
    //put your code here
    function __construct(){
        parent::__construct();
    }
    
    function select_data() {
        $query = $this->db->get('master_karyawan');
        return $query->result();
    }

    function select_gaji($nik) {
    	$query = $this->db->query("SELECT master_gaji.keterangan, trans_periode_gaji_karyawan.nilai, master_karyawan.nik
        FROM (master_karyawan INNER JOIN trans_periode_gaji_karyawan ON master_karyawan.nik = trans_periode_gaji_karyawan.nik) INNER JOIN master_gaji ON trans_periode_gaji_karyawan.kode_gaji = master_gaji.kode_gaji
        WHERE (((master_karyawan.nik)='$nik'))");
        return $query;
    }
}
