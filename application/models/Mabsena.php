<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsena extends CI_Model {
    var $table = 'master_absen_aktual';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_spl($nik,$tanggal){
        $query = $this->db->query("select `a`.`nik` AS `nik`,`b`.`nama` AS `nama`,`b`.`departemen` AS `departemen`,
            `b`.`grup` AS `grup`,`b`.`jabatan` AS `jabatan`,`c`.`jumlah_jam` AS `jumlah_jam`,`a`.`tanggal` AS `tanggal`,
            `a`.`status_aktual` AS `status_aktual`,`a`.`masuk` AS `masuk`,`a`.`pulang` AS `pulang`,
            `c`.`approve` AS `approve`,`c`.`approve2` AS `approve2`,`a`.`lastupdate` AS `lastupdate`,
            `a`.`user_id` AS `user_id` from ((`master_absen_aktual` `a` join `master_karyawan` `b` 
            on((`a`.`nik` = `b`.`nik`))) left join `trans_spl` `c` on((`a`.`tanggal` = `c`.`tanggal`) and 
            (`a`.`nik` = `c`.`nik`)))
            WHERE a.nik='$nik' AND a.tanggal='$tanggal'");
        return $query->result();
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->table, $data);
        return;
    }

    function getkodeunik($table) {
        $tgl = date('Ym');
        $q = $this->db->query("SELECT MAX(RIGHT(id_spl,4)) AS idmax FROM trans_spl");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->idmax)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        $kar = "SPL-".$tgl."-";
        return $kar.$kd;
   }

    public function delete_by_id($nik,$tanggal){
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete($this->table);
    }

    function update_spl($id_spl,$jumlah_jam,$nik,$tanggal,$masuk,$pulang,$jumlah_jam,$last){
        $this->db->where('nik',$nik);
        $this->db->where('tanggal',$tanggal);
        $q = $this->db->get('trans_spl');
            if ($q->num_rows() > 0){
                $this->db->query("update trans_spl set mulai='$masuk', selesai='$pulang', jumlah_jam='$jumlah_jam' where nik='$nik' and tanggal='$tanggal'");
            } else {
                $this->db->query("INSERT INTO trans_spl VALUES ('$id_spl','$nik','$tanggal','$masuk','$pulang','$jumlah_jam','','','','','','$last','') ON DUPLICATE KEY UPDATE nik='$nik' and tanggal='$tanggal'");
            }
    }

    public function update_absen_aktual($tanggalu,$shift,$status_aktual,$masuk,$pulang,$nik,$tanggal){
        $this->db->query("update master_absen_aktual set status_aktual='$status_aktual',masuk='$masuk',pulang='$pulang',shift='$shift' where nik='$nik' and tanggal='$tanggal'");
    }

    public function delete_spl($nik,$tanggal){
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete('trans_spl');
    }

    function insert_csv($in,$tw,$tgl1,$tgl2,$periode) {
        if (!empty($tw->row())) {
            $ie = $tw->row()->shift;
            //INSERT INTO users_partners (uid,pid) VALUES (1,1) ON DUPLICATE KEY UPDATE uid=uid
            $this->db->query("INSERT INTO master_absen_aktual VALUES ('$in[nik]','$tgl2','$periode','Hadir','','$in[masuk]','$in[pulang]','$ie','','') ON DUPLICATE KEY UPDATE nik='$in[nik]'");
            return;
        } else {
            $this->db->query("INSERT INTO master_absen_aktual VALUES ('$in[nik]','$tgl1','$periode','Hadir','','$in[masuk]','$in[pulang]','','','') ON DUPLICATE KEY UPDATE nik='$in[nik]'");
            return;
        }
    }
}
