<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 10000);
ini_set('memory_limit','2048M');
class Claporanallthr extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->library("Pdf");
    }
    
    public function thr($periode) {
        $ot = $this->session->userdata('periode');
        $o = substr($ot, 0, -2);
        $t = substr($ot, 4, 2);
        $per =  $t.'-'.$o ;
        //$qm = $this->db->query("SELECT * FROM trans_periode_pph21 WHERE periode='$periode'")->row();
        $qm = $this->db->query("SELECT trans_periode_pph21_thr.periode, 
            trans_periode_pph21_thr.nik, master_karyawan.nama, master_karyawan.npwp, master_karyawan.pph21_metode, 
            master_karyawan.departemen, master_karyawan.jabatan, master_karyawan.status_perkawinan, 
            master_karyawan.bank, master_karyawan.no_rekening
            FROM master_karyawan INNER JOIN trans_periode_pph21_thr ON master_karyawan.nik = trans_periode_pph21_thr.nik 
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
            $qlaporan = $this->db->query("SELECT * FROM trans_periode_pph21_thr WHERE periode='$periode' and nik='$bb[nik]'")->row();
            $qlogo = $this->db->query("SELECT * FROM master_perusahaan WHERE status='aktif'")->row();
            
        $html = '
            <table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-size:8px;">
                <tr>
                    <td colspan="2" align="left">THR Slip : '.$per.'</td>
                    <td colspan="3" align="center"><img width="159" height="45" src="logo/'.$qlogo->logo_perusahaan.'" /></td>
                </tr>
                <tr>
                    <td colspan="5" align="left">Name : '.$bb['nama'].'/'.$bb['nik'].'</td>
                </tr>
                <tr>
                    <td colspan="3" align="left">NPWP : '.$bb['npwp'].'</td>
                    <td colspan="2" align="left">PPH21 Metode : '.$bb['pph21_metode'].'</td>
                </tr>
                <tr>
                    <td colspan="3" align="left">Position : '.$bb['jabatan'].'</td>
                    <td colspan="2" align="left">Bank : '.$bb['bank'].'/'.$bb['no_rekening'].'</td>
                </tr>
                <tr>
                    <td colspan="3" align="left">Department : '.$bb['departemen'].'</td>
                    <td colspan="2" align="left">Status Perkawinan : '.$bb['status_perkawinan'].'</td>
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
            ';
            
            $left=$left.'
                    <tr>
                        <td align="left">THR</td>
                        <td align="right">'.number_format($qlaporan->thr,0,',','.').'</td>
                    </tr>
                ';

                if ($qlaporan->metode_pph21 == 'NET'){
                    $rqjabatan = round($qlaporan->pph21_thr);
                    $left = $left.'
                        <tr>
                            <td align="left">Tunjangan PPH21 THR</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                    ';
                    $tot    = $qlaporan->thr + $qlaporan->pph21_thr;
                } else if ($qlaporan->metode_pph21 == 'GROSS') {
                    $rqjabatan = round($qlaporan->pph21_thr);
                    $left = $left.'
                        <tr>
                            <td align="left">Tunjangan PPH21 THR</td>
                            <td align="right">0</td>
                        </tr>
                    ';
                    $tot    = $qlaporan->thr;
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
            

                if ($qlaporan->metode_pph21 == 'GROSS'){
                    $rqjabatan=round($qlaporan->pph21_thr);
                    $right = $right.'
                        <tr>
                            <td align="left">Tax PPH21 THR</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                        
                    ';
                    $qpppm = $qlaporan->pph21_thr;
                } else if ($qlaporan->metode_pph21 == 'NET'){
                    $rqjabatan=round($qlaporan->pph21_thr);
                    $right = $right.'
                        <tr>
                            <td align="left">Tax PPH21 THR</td>
                            <td align="right">'.number_format($rqjabatan,0,',','.').'</td>
                        </tr>
                        
                    ';
                }
                $rtjabat=round($rqjabatan);
            
            $right = $right.'
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
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
            $to = round($qlaporan->thp,-2);
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