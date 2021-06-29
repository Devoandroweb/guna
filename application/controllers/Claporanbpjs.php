<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Claporanbpjs extends CI_Controller {
    
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
        $qm = $this->db->query("SELECT departemen
            FROM vperiode_bpjs 
            where periode='$periode' group by departemen")->result_array();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Pemara Labels Indonesia');
        $pdf->SetTitle('Laporan BPJS');
        $pdf->SetSubject('Laporan BPJS');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        foreach ($qm as $bb){
            $qpro = $this->db->query("call vlist_bpjs_periode ($periode)");
            
            
        
        $html = '
            <table width="100%" border="0" cellpadding="1" cellspacing="1" style="font-size:12px;">
                <tr>
                    <td colspan="5" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" align="left">Laporan BPJS : '.$per.'</td>
                    <td colspan="3" align="right"></td>
                </tr>
                <tr>
                    <td colspan="5" align="left">&nbsp;</td>
                </tr>
                
            </table>
        ';
        $pdf->AddPage('P','A6');
        $left='
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;">
                <tr>
                    <td align="left"><b>Section (Perusahaan)</b></td>
                    <td align="left"><b>Cost Centre</b></td>
                    <td align="left"><b>Head Cost</b></td>
                    <td align="left"><b>Base</b></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
            ';
            
            
            
            $left=$left.'
            </table>
            ';

        
        $htmls = '
            <table width="100%" border="0" cellpadding="4" cellspacing="4" style="font-size:12px;">
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
            </table>
        ';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->writeHTMLCell(0, '', '', '', $left, 0, 0, 1, true, 'C', true);
        
        $pdf->writeHTML($htmls, true, false, true, false, '');
        }
        $pdf->lastPage();
        $pdf->Output('laporan-bpjs-all-'.$periode.'', 'I');
    }

}