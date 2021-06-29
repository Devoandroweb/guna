<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Claporan extends CI_Controller {
  
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->library("Pdf");
    }
  
    public function gaji($nik,$periode) {
        $ot = $this->session->userdata('periode');
        $o = substr($ot, 0, -2);
        $t = substr($ot, 4, 2);
        $per =  $t.'-'.$o ;
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Payroll System');
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
        $qlaporan = $this->db->query("SELECT * FROM trans_periode_pph21 WHERE periode='$periode' and nik='$nik'")->row();
        $q = $this->db->query("SELECT trans_periode_gaji_karyawan.kode_gaji, trans_periode_gaji_karyawan.keterangan_gaji, 
            trans_periode_gaji_karyawan.nilai, master_karyawan.nik, master_karyawan.nama, master_karyawan.jabatan, 
            master_karyawan.departemen, master_karyawan.npwp, master_karyawan.pph21_metode, master_karyawan.bank, master_karyawan.status_perkawinan, 
            master_karyawan.no_rekening
        FROM trans_periode_gaji_karyawan INNER JOIN master_karyawan ON trans_periode_gaji_karyawan.nik = master_karyawan.nik
        WHERE trans_periode_gaji_karyawan.kode_gaji <> 101 and trans_periode_gaji_karyawan.kode_gaji <> 307 and trans_periode_gaji_karyawan.kode_gaji <> 309 and trans_periode_gaji_karyawan.kode_gaji <> 403 and trans_periode_gaji_karyawan.kode_gaji <> 401 and master_karyawan.nik='$nik' and trans_periode_gaji_karyawan.periode='$periode'");
            
        $qq = $this->db->query("SELECT * FROM trans_periode_jamsostek WHERE periode='$periode' and nik='$nik'");
        $qb = $this->db->query("SELECT master_karyawan.nik, trans_periode_jamsostek.periode, trans_periode_jamsostek.nama_program, trans_periode_jamsostek.nilai_perusahaan, trans_periode_jamsostek.nilai_karyawan
FROM master_karyawan INNER JOIN trans_periode_jamsostek ON master_karyawan.nik = trans_periode_jamsostek.nik
WHERE (((master_karyawan.nik)='$nik') AND ((trans_periode_jamsostek.periode)='$periode'))")->result_array();

        $qb2 = $this->db->query("SELECT trans_periode_jamsostek.nik, trans_periode_jamsostek.periode, Sum(trans_periode_jamsostek.nilai_karyawan) AS nilai_karyawan, Sum(trans_periode_jamsostek.nilai_perusahaan) AS nilai_perusahaan
FROM trans_periode_jamsostek
GROUP BY trans_periode_jamsostek.nik, trans_periode_jamsostek.periode
HAVING (((trans_periode_jamsostek.nik)='$nik') AND ((trans_periode_jamsostek.periode)='$periode'))")->row();

        $qlogo = $this->db->query("SELECT * FROM master_perusahaan WHERE status='aktif'")->row();
        $nik                = $qlaporan->nik;
        $nama               = $qlaporan->nama;
        $npwp               = $qlaporan->npwp;
        $pph21_met          = $qlaporan->metode_pph21;
        $jabatan            = $qlaporan->jabatan;
        $departemen         = $qlaporan->departemen;
        $bank               = $qlaporan->bank;
        $no_rekening        = $qlaporan->no_rekening;
        $status_perkawinan  = $qlaporan->status_perkawinan;
        
        $html = '
            <table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-size:8px;">
            <tr>
                <td colspan="2" align="left">Salary Slip : '.$per.'</td>
                <td colspan="3" align="center"><img src="logo/'.$qlogo->logo_perusahaan.'" /></td>
            </tr>
            
            <tr>
                <td colspan="5" align="left">Name : '.$nama.'/'.$nik.'</td>
            </tr>
            <tr>
                <td colspan="3" align="left">NPWP : '.$npwp.'</td>
                <td colspan="2" align="left">PPH21 Metode : '.$pph21_met.'</td>
            </tr>
            
            <tr>
                <td colspan="3" align="left">Position : '.$jabatan.'</td>
                <td colspan="2" align="left">Bank : '.$bank.'/'.$no_rekening.'</td>
            </tr>
            <tr>
                <td colspan="3" align="left">Department : '.$departemen.'</td>
                <td colspan="2" align="left">Status Perkawinan : '.$status_perkawinan.'</td>
            </tr>
            <tr>
                <td colspan="1" align="left">&nbsp;</td>
                <td colspan="2" align="left">Kehadiran : '.number_format($qlaporan->kehadiran,0,',','.').' Hari</td>
                <td colspan="2" align="left">OT Hours : '.number_format($qlaporan->over_time_index,2,',','.').'</td>
            </tr>

            </table>
        ';
        $pdf->AddPage('P','A6');
        $left='
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px;">
                
                <tr>
                    <td width="90px" align="left"><b>Penerimaan</b></td>
                    <td width="55px" align="right"><b>Nominal</b></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left">Gaji Pokok</td>
                    <td align="right">'.number_format($qlaporan->base,0,',','.').'</td>
                </tr>
            ';
            foreach ($q->result_array() as $b){
                $keterangan_gaji = $b['keterangan_gaji'];
                $nilai = $b['nilai'];
                $nilai_gaji = round($nilai);
                if ($nilai != "0"){
                $left=$left.'
                    <tr>
                        <td align="left">'.$keterangan_gaji.'</td>
                        <td align="right">'.number_format($nilai_gaji,0,',','.').'</td>
                    </tr>
                ';
                } 
            }
            foreach ($qq->result_array() as $bb){
                $nama_program = $bb['nama_program'];
                $nilai_perusahaan = $bb['nilai_perusahaan'];
                $nilainp = round($nilai_perusahaan);
                $left = $left.'
                    <tr>
                        <td align="left">Tunjangan '.$nama_program.'</td>
                        <td align="right">'.number_format($nilainp,0,',','.').'</td>
                    </tr>
                ';
            }
            $nilai_ot=$qlaporan->over_time;
            $rnilai_ot = round($nilai_ot);
            //$rqjabatan = round($qjabatan->insentive_kehadiran);
            $left = $left.'
            <tr>
                <td align="left">Overtime</td>
                <td align="right">'.number_format($rnilai_ot,0,',','.').'</td>
            </tr>
            
            ';
            
                if ($qlaporan->metode_pph21 == 'NET'){
                    //$qpphperiode = 'NET';
                    $rqjabatan = round($qlaporan->pph21_nett);
                    $left = $left.'
                        <tr>
                            <td align="left">Tunjangan PPH21 Gaji</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                    ';
                    $qpppm  = $qlaporan->pph21_nett;
                    $tot    = $qlaporan->penghasilan_kotor + $qlaporan->pph21_nett;
                } else if ($qlaporan->metode_pph21 == 'GROSS') {
                    $rqjabatan = round($qlaporan->pph21_nett);
                    $left = $left.'
                        <tr>
                            <td align="left">Tunjangan PPH21 Gaji</td>
                            <td align="right">0</td>
                        </tr>
                    ';
                    $qpppm  = $qlaporan->pph21_nett;
                    $tot    = $qlaporan->penghasilan_kotor;
                }

            $rtotal_gross = round($tot);
            $left = $left.'
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><b>(a) Total Gross</b></td>
                    <td align="right"><strong>'.number_format($rtotal_gross,0,',','.').'</strong></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
            ';
            $left=$left.'
            </table>
            ';

        $right='

            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px;">
                <tr>
                    <td width="90px" align="left"><b>Potongan</b></td>
                    <td width="55px" align="right"><b>Nominal</b></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
            ';
            foreach ($qb as $bb){
                $nama_program = $bb['nama_program'];
                $nilai_karyawan = $bb['nilai_karyawan'];
                $nil = $nilai_karyawan;
                $rnilai_total = round($nil);
                //$nilai_total = number_format($nil,2,',','.');
                $right=$right.'
                    <tr>
                        <td align="left">'.$nama_program.'</td>
                        <td align="right">'.$nilai_total = number_format($rnilai_total,0,',','.').'</td>
                    </tr>
                ';
            }
        
            $qpppm = $qlaporan->pph21_nett;
            
            $tpph21 = number_format($qlaporan->pph21_nett,2,',','.');
            $td = $qb2->nilai_karyawan;
            $nilai_pph = number_format($td,2,',','.');
            $tjabat = ($qlaporan->potongan_ketidakhadiran + $td + $qlaporan->potongan_koperasi + $qlaporan->tunjangan_jamsostek + $qpppm + $qlaporan->tambahan_non_npwp) + $qlaporan->adjustment_minus;
            $nr = number_format($tot - $td,2,',','.');
            $netremitance = $tot - $tjabat;
            $rqjabatan = round($qlaporan->tambahan_non_npwp);
            $rqpbpjs=round($qlaporan->tunjangan_jamsostek);
            $rpot=round($qlaporan->potongan_ketidakhadiran);
            
            $right = $right.'
                <tr>
                    <td align="left">Potongan Koperasi</td>
                    <td align="right">'.number_format($qlaporan->potongan_koperasi,0,',','.').'</td>
                </tr>
                <tr>
                    <td align="left">Tambahan Non NPWP</td>
                    <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                </tr>
                <tr>
                    <td align="left">BPJS ditanggung perusahaan</td>
                    <td align="right">'.number_format($rqpbpjs,0,',','.').'</td>
                </tr>
                <tr>
                    <td align="left">Potongan Mangkir</td>
                    <td align="right">'.number_format($rpot,0,',','.').'</td>
                </tr>
            ';
            if($qlaporan->adjustment_minus != 0){
                $right = $right.'
                    <tr>
                    <td align="left">Adjustment Minus</td>
                    <td align="right">'.number_format($qlaporan->adjustment_minus,0,',','.').'</td>
                </tr>
                ';
            }
            
                if ($qlaporan->metode_pph21 == 'GROSS'){
                    $rqjabatan=round($qlaporan->pph21_nett);
                    $right = $right.'
                        <tr>
                            <td align="left">Tax PPH21 Gaji</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                        
                    ';
                    $qpppm = $qlaporan->pph21_nett;
                } else if ($qlaporan->metode_pph21 == 'NET'){
                    $rqjabatan=round($qlaporan->pph21_nett);
                    $right = $right.'
                        <tr>
                            <td align="left">Tax PPH21 Gaji</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                        
                    ';
                }
            
            $rtjabat=round($tjabat);
            $right = $right.'
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><b>(b) Total Deductions</b></td>
                    <td align="right"><strong>'.number_format($rtjabat,0,',','.').'</strong></td>
                </tr>
                
            ';
            $right=$right.'
            </table>
            ';
            $pem = round($netremitance,-2);
        $htmls = '
            <table width="100%" border="0" cellpadding="4" cellspacing="4" style="font-size:9px;">
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right"><b>(a-b) NET Remmitance</b></td>
                    <td align="left"><b>'.number_format($pem,0,',','.').'</b></td>
                </tr>
            </table>
        ';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(43, '', '', '', $left, 0, 0, 1, true, 'C', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(90, '', '', '', $right, 0, 1, 1, true, 'C', true);
        $pdf->writeHTML($htmls, true, false, true, false, '');
        
        $pdf->lastPage();
        $pdf->Output('laporan-gaji-'.$nik.'-'.$periode.'.pdf', 'I');
    }

}