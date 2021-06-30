<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 10000);
ini_set('memory_limit','2048M');
class Claporanall extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->library("Pdf");
    }
    
    public function gaji($periode) {
        $ot = $this->session->userdata('periode');
        $o = substr($ot, 0, -2);
        $t = substr($ot, 4, 2);
        $per =  $t.'-'.$o ;
        //$qm = $this->db->query("SELECT * FROM trans_periode_pph21 WHERE periode='$periode'")->row();
        $qm = $this->db->query("SELECT trans_periode_pph21.terlambat, trans_periode_pph21.periode, 
            trans_periode_pph21.nik, trans_periode_pph21.ketidakhadiran, trans_periode_pph21.sakit,
            trans_periode_pph21.over_time, master_karyawan.nama, master_karyawan.npwp, master_karyawan.pph21_metode, 
            master_karyawan.departemen, master_karyawan.jabatan, master_karyawan.status_perkawinan, 
            master_karyawan.bank, master_karyawan.no_rekening
            FROM master_karyawan INNER JOIN trans_periode_pph21 ON master_karyawan.nik = trans_periode_pph21.nik 
            where periode='$periode'")->result_array();

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
        foreach ($qm as $bb){
            $q = $this->db->query("SELECT trans_periode_gaji_karyawan.kode_gaji, trans_periode_gaji_karyawan.keterangan_gaji, trans_periode_gaji_karyawan.nilai, master_karyawan.nik, master_karyawan.nama, master_karyawan.jabatan, master_karyawan.departemen, master_karyawan.npwp, master_karyawan.bank, master_karyawan.no_rekening, trans_periode_pph21.over_time
            FROM (trans_periode_gaji_karyawan INNER JOIN master_karyawan ON trans_periode_gaji_karyawan.nik = master_karyawan.nik) INNER JOIN trans_periode_pph21 ON (trans_periode_gaji_karyawan.periode = trans_periode_pph21.periode) AND (trans_periode_gaji_karyawan.nik = trans_periode_pph21.nik)
            WHERE trans_periode_gaji_karyawan.kode_gaji <> 101 and trans_periode_gaji_karyawan.kode_gaji <> 307 and trans_periode_gaji_karyawan.kode_gaji <> 309 and trans_periode_gaji_karyawan.kode_gaji <> 403 and trans_periode_gaji_karyawan.kode_gaji <> 401 and master_karyawan.nik='$bb[nik]' and trans_periode_gaji_karyawan.periode='$periode'");
            
            $qlaporan = $this->db->query("SELECT * FROM trans_periode_pph21 WHERE periode='$periode' and nik='$bb[nik]'")->row();
            $qs = $this->db->query("SELECT trans_periode_jamsostek.periode, trans_periode_jamsostek.nik, Sum(trans_periode_jamsostek.nilai_perusahaan) AS nilai_perusahaan, Sum(trans_periode_jamsostek.nilai_karyawan) AS nilai_karyawan
            FROM trans_periode_jamsostek
            GROUP BY trans_periode_jamsostek.periode, trans_periode_jamsostek.nik
            HAVING (((trans_periode_jamsostek.periode)='$periode') AND ((trans_periode_jamsostek.nik)='$bb[nik]'))")->row();
            $qq = $this->db->query("SELECT * FROM trans_periode_jamsostek WHERE periode='$periode' and nik='$bb[nik]'");
            $qb = $this->db->query("SELECT master_karyawan.nik, trans_periode_jamsostek.periode, trans_periode_jamsostek.nama_program, trans_periode_jamsostek.nilai_perusahaan, trans_periode_jamsostek.nilai_karyawan
            FROM master_karyawan INNER JOIN trans_periode_jamsostek ON master_karyawan.nik = trans_periode_jamsostek.nik
            WHERE (((master_karyawan.nik)='$bb[nik]') AND ((trans_periode_jamsostek.periode)='$periode'))")->result_array();
            $qlogo = $this->db->query("SELECT * FROM master_perusahaan WHERE status='aktif'")->row();
            // <td colspan="2" align="left">Salary Slip : '.$per.'</td>
            $html = '
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px;">
                <tr>
                    <td colspan="3" align="left"><img width="100px" src="logo/'.$qlogo->logo_perusahaan.'" /><br></td>
                    <td colspan="2" align="right"><h1>PAYSLIP</td></td>
                </tr>
                
                <tr>
                    <td width="50%">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px;">
                            <tr>
                                <td colspan="5" align="left">Name : '.$bb['nama'].'/'.$bb['nik'].'</td>
                            </tr>
                            <tr>
                                <td colspan="3" align="left">NPWP : '.$bb['npwp'].'</td>
                                
                            </tr>
                            <tr>
                                <td colspan="3" align="left">Pos. : '.$bb['jabatan'].'</td>
                             
                            </tr>
                            <tr>
                                <td colspan="3" align="left">Department : '.$bb['departemen'].'</td>
                                
                            </tr>
                            
                        </table>
                    </td>
                    <td width="50%" >
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:8px; margin-top:5px;">
                            <tr>
                                <td align="center" bgcolor="#cecbcb">Salary Slip</td>
                                <td align="center" bgcolor="#cecbcb">BANK</td>
                                <td align="center" bgcolor="#cecbcb">PPH21</td>
                            </tr>
                            <tr>
                                <td align="center">'.$per.'</td>
                                <td align="center">'.$bb['bank'].'/'.$bb['no_rekening'].'</td>
                                <td align="center">'.$bb['pph21_metode'].'</td>
                            </tr>
                            <tr>
                                <td align="center" bgcolor="#cecbcb">Perkawinan</td>
                                <td align="center" bgcolor="#cecbcb">Kehadiran</td>
                                <td align="center" bgcolor="#cecbcb">OT Hours</td>
                            </tr>
                            <tr>
                                <td align="center">'.$bb['status_perkawinan'].'</td>
                                <td align="center">'.number_format($qlaporan->kehadiran,0,',','.').' Hari</td>
                                <td align="center">'.number_format($qlaporan->over_time_index,2,',','.').'</td>
                            </tr>
                        </table>
                    </td>
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
            $nilai_ot=round($qlaporan->over_time);
            //$nilai_j=round($qlaporan->insentive_kehadiran);
            $left = $left.'
            <tr>
                <td align="left">Overtime</td>
                <td align="right">'.number_format($nilai_ot,0,',','.').'</td>
            </tr>
            
            ';
            if ($qlaporan->metode_pph21 == 'NET'){
                $gs=round($qlaporan->pph21_gaji_sebulan);
                $left = $left.'
                <tr>
                    <td align="left">Tunjangan PPH21</td>
                    <td align="right">'.number_format($gs,0,',','.').'</td>
                </tr>
                ';
                
                $qpppm = $qlaporan->pph21_gaji_sebulan;
                $tot = $qpppm+$qlaporan->penghasilan_kotor;
            } else {
                $qpppm = 0;
                $tot = $qpppm+$qlaporan->penghasilan_kotor;
            }
            
            $total_gross = round($tot);
            $left = $left.'
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><b>(a) Total Gross</b></td>
                    <td align="right"><strong>'.number_format($total_gross,0,',','.').'</strong></td>
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
                $nilai_total = round($nil);
                $right=$right.'
                    <tr>
                        <td align="left">'.$nama_program.'</td>
                        <td align="right">'.number_format($nilai_total,0,',','.').'</td>
                    </tr>
                ';
            }
            if ($qlaporan->metode_pph21 == 'GROSS'){
                $qpppm = $qlaporan->pph21_gaji_sebulan;
            } else if ($qlaporan->metode_pph21 == 'NET'){
                $qpppm = $qlaporan->pph21_gaji_sebulan;
            } 
            $tpph21 = number_format($qlaporan->pph21_gaji_sebulan,0,',','.');
            $td = $qs->nilai_karyawan;
            $nilai_pph = number_format($td,0,',','.');
            $tjabat = $qlaporan->potongan_ketidakhadiran + $qlaporan->tunjangan_jamsostek + 
            $td + $qlaporan->potongan_koperasi + 
            $qpppm + $qlaporan->tambahan_non_npwp + $qlaporan->adjustment_minus;
            $nr = number_format($tot - $td,0,',','.');
            $netremitance = $tot - $tjabat;
            $pp=round($qlaporan->potongan_koperasi);
            $tnp=round($qlaporan->tambahan_non_npwp);
            $right = $right.'
                <tr>
                    <td align="left">Potongan Koperasi</td>
                    <td align="right">'.number_format($pp,0,',','.').'</td>
                </tr>
                <tr>
                    <td align="left">Tambahan Non NPWP</td>
                    <td align="right">'.number_format($tnp,0,',','.').'</td>
                </tr>
            ';
            if ($qlaporan->metode_pph21 == 'GROSS'){

                    $right = $right.'
                    <tr>
                        <td align="left">Tax PPH21 Gaji</td>
                        <td align="right">'.number_format($qlaporan->pph21_gaji_sebulan,0,',','.').'</td>
                    </tr>
                    ';
                    
                    $qpppm = $qlaporan->pph21_gaji_sebulan;
                } else if ($qlaporan->metode_pph21 == 'NET'){
                    $pph=round($qlaporan->pph21_gaji_sebulan);
                    $right = $right.'
                        <tr>
                            <td align="left">Tax PPH21 Gaji</td>
                            <td align="right">'.number_format($qlaporan->pph21_gaji_sebulan,0,',','.').'</td>
                        </tr>
                    ';
            }
            $tj=round($qlaporan->tunjangan_jamsostek);
            $pk=round($qlaporan->potongan_ketidakhadiran);
            $ttjabat=round($tjabat);
            if($qlaporan->adjustment_minus != 0){
                $right = $right.'
                    <tr>
                    <td align="left">Adjustment Minus</td>
                    <td align="right">'.number_format($qlaporan->adjustment_minus,0,',','.').'</td>
                </tr>
                ';
            }
            $right = $right.'
                <tr>
                    <td align="left">BPJS ditanggung perusahaan</td>
                    <td align="right">'.number_format($tj,0,',','.').'</td>
                </tr>
                <tr>
                    <td align="left">Potongan Mangkir</td>
                    <td align="right">'.number_format($pk,0,',','.').'</td>
                </tr>
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><b>(b) Total Deductions</b></td>
                    <td align="right"><strong>'.number_format($ttjabat,0,',','.').'</strong></td>
                </tr>
            ';
            $right=$right.'
            </table>
            ';
            $to=round($netremitance,-2);
        $htmls = '
            <table width="100%" border="0" cellpadding="4" cellspacing="4" style="font-size:9px;">
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right"><b>(a-b) NET Remmitance</b></td>
                    <td align="left"><b>'.number_format($to,0,',','.').'</b></td>
                </tr>
            </table>
        ';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(43, '', '', '', $left, 0, 0, 1, true, 'C', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(90, '', '', '', $right, 0, 1, 1, true, 'C', true);
        $pdf->writeHTML($htmls, true, false, true, false, '');
        }
        $pdf->lastPage();
        $pdf->Output('laporan-gaji-all-'.$periode.'.pdf', 'I');
    }


}