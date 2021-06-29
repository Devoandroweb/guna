<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Claporanthr extends CI_Controller {
  
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->library("Pdf");
    }
  
    public function thr($nik,$periode) {
        $ot = $this->session->userdata('periode');
        $o = substr($ot, 0, -2);
        $t = substr($ot, 4, 2);
        $per =  $t.'-'.$o ;
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Payroll System');
        $pdf->SetTitle('Laporan THR');
        $pdf->SetSubject('Laporan THR');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $qlaporan = $this->db->query("SELECT * FROM trans_periode_pph21_thr WHERE periode='$periode' and nik='$nik'")->row();
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
                    <td colspan="2" align="left">THR Slip : '.$per.'</td>
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

            $rtotal_gross = round($tot);
            $left = $left.'
                <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                </tr>
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

            $pem = round($qlaporan->thp,-2);
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