<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Claporanpph21 extends CI_Controller {
  
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->library("Pdf");
    }
  
    public function tahunan($nik,$periode) {
        $ot = $this->session->userdata('periode');
        $o = substr($ot, 0, -2);
        $t = substr($ot, 4, 2);
        $per =  $t.'-'.$o ;
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Pemara Labels Indonesia');
        $pdf->SetTitle('Laporan Gaji');
        $pdf->SetSubject('Laporan Gaji');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $qpbpjs = $this->db->query("SELECT potongan_ketidakhadiran,tunjangan_jamsostek FROM trans_periode_pph21 WHERE periode='$periode' and nik='$nik'")->row();
        $qjabatan = $this->db->query("SELECT sakit,terlambat,over_time,pinjaman_perusahaan,insentive_kehadiran,
            biaya_jabatan,pph21_gaji_sebulan,tambahan_non_npwp,pph21_nett FROM trans_periode_pph21 
            WHERE periode='$periode' and nik='$nik'")->row();
        

        $q = $this->db->query("SELECT trans_periode_gaji_karyawan.kode_gaji, trans_periode_gaji_karyawan.keterangan_gaji, 
            trans_periode_gaji_karyawan.nilai, master_karyawan.nik, master_karyawan.nama, master_karyawan.jabatan, 
            master_karyawan.departemen, master_karyawan.npwp, master_karyawan.pph21_metode, master_karyawan.bank, master_karyawan.status_perkawinan, 
            master_karyawan.no_rekening
        FROM trans_periode_gaji_karyawan INNER JOIN master_karyawan ON trans_periode_gaji_karyawan.nik = master_karyawan.nik
        WHERE trans_periode_gaji_karyawan.kode_gaji <> 401 and master_karyawan.nik='$nik' and trans_periode_gaji_karyawan.periode='$periode'");
            
        $qq = $this->db->query("SELECT * FROM trans_periode_jamsostek WHERE periode='$periode' and nik='$nik'");
        $qs = $this->db->query("SELECT trans_periode_jamsostek.periode, trans_periode_jamsostek.nik, Sum(trans_periode_jamsostek.nilai_perusahaan) AS nilai_perusahaan
FROM trans_periode_jamsostek
GROUP BY trans_periode_jamsostek.periode, trans_periode_jamsostek.nik
HAVING (((trans_periode_jamsostek.periode)='$periode') AND ((trans_periode_jamsostek.nik)='$nik'))")->row();
        $qpph21 = $this->db->query("select pph21_metode from master_karyawan where nik='$nik'")->row();
        $q2 = $this->db->query("SELECT trans_periode_gaji_karyawan.kode_gaji, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.periode, Sum(trans_periode_gaji_karyawan.nilai) AS total_gross
            FROM trans_periode_gaji_karyawan
            where (trans_periode_gaji_karyawan.kode_gaji <> 401 and trans_periode_gaji_karyawan.nik='$nik' and trans_periode_gaji_karyawan.periode='$periode')")->row();

        $q3 = $this->db->query("SELECT sum(jumlah_jam) as jj from vspl where nik='$nik' AND periode=$periode")->row();
        $qt = $this->db->query("SELECT count(enroll) as hadir from master_mesin_clear where status_aktual='Hadir' and nik='$nik' AND periode=$periode")->row();
        $qb = $this->db->query("SELECT master_karyawan.nik, trans_periode_jamsostek.periode, trans_periode_jamsostek.nama_program, trans_periode_jamsostek.nilai_perusahaan, trans_periode_jamsostek.nilai_karyawan
FROM master_karyawan INNER JOIN trans_periode_jamsostek ON master_karyawan.nik = trans_periode_jamsostek.nik
WHERE (((master_karyawan.nik)='$nik') AND ((trans_periode_jamsostek.periode)='$periode'))")->result_array();

        $qb2 = $this->db->query("SELECT trans_periode_jamsostek.nik, trans_periode_jamsostek.periode, Sum(trans_periode_jamsostek.nilai_karyawan) AS nilai_karyawan, Sum(trans_periode_jamsostek.nilai_perusahaan) AS nilai_perusahaan
FROM trans_periode_jamsostek
GROUP BY trans_periode_jamsostek.nik, trans_periode_jamsostek.periode
HAVING (((trans_periode_jamsostek.nik)='$nik') AND ((trans_periode_jamsostek.periode)='$periode'))")->row();

        $qlogo = $this->db->query("SELECT * FROM master_perusahaan WHERE status='aktif'")->row();
        $qb3 = $this->db->query("SELECT periode, nik, pph21_gaji_sebulan FROM trans_periode_pph21 
            where periode='$periode' and nik='$nik'")->row();    
            
        $nik = $q->row()->nik;
        $nama = $q->row()->nama;
        $npwp = $q->row()->npwp;
        $pph21_met = $q->row()->pph21_metode;
        $jabatan = $q->row()->jabatan;
        $departemen = $q->row()->departemen;
        $bank = $q->row()->bank;
        $no_rekening = $q->row()->no_rekening;
        $status_perkawinan = $q->row()->status_perkawinan;
        
        $html = '
            <table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-size:8px;">
            <tr>
                <td colspan="2" align="left">Salary Slip : '.$per.'</td>
                <td colspan="3" align="right"><img src="logo/'.$qlogo->logo_perusahaan.'" /></td>
            </tr>
            <tr>
                <td colspan="5" align="left">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5" align="left">Name : '.$nama.'/'.$nik.'</td>
            </tr>
            <tr>
                <td colspan="5" align="left">NPWP : '.$npwp.'</td>
            </tr>
            <tr>
                <td colspan="5" align="left">PPH21 Metode : '.$pph21_met.'</td>
            </tr>
            <tr>
                <td colspan="3" align="left">Position : '.$jabatan.'</td>
                <td colspan="2" align="left">Bank : '.$bank.'/'.$no_rekening.'</td>
            </tr>
            <tr>
                <td colspan="3" align="left">Department : '.$departemen.'</td>
                <td colspan="2" align="left">Status Perkawinan : '.$status_perkawinan.'</td>
            </tr>
            
            </table>
        ';
        $pdf->AddPage('L','A5');
        $left='
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px;">
                <tr>
                    <td width="80px" align="left"><b>Uraian</b></td>
                    <td width="50px" align="left"><b>Januari</b></td>
                    <td width="50px" align="left"><b>Februari</b></td>
                    <td width="50px" align="left"><b>Maret</b></td>
                    <td width="50px" align="left"><b>April</b></td>
                    <td width="50px" align="left"><b>Mei</b></td>
                    <td width="50px" align="left"><b>Juni</b></td>
                    <td width="50px" align="left"><b>Juli</b></td>
                    <td width="50px" align="left"><b>Agustus</b></td>
                    <td width="50px" align="left"><b>September</b></td>
                    <td width="50px" align="left"><b>Oktober</b></td>
                    <td width="50px" align="left"><b>November</b></td>
                    <td width="50px" align="left"><b>Desember</b></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="80px" align="left">Gaji</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                    <td width="50px" align="right">15.000.000</td>
                </tr>
            ';
            
            
            
            
            $left=$left.'
            </table>
            ';

        
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(43, '', '', '', $left, 0, 0, 1, true, 'C', true);
        $pdf->SetFillColor(255, 255, 255);


        
        $pdf->lastPage();
        $pdf->Output('laporan-gaji-'.$nik.'-'.$periode.'', 'I');
    }

}