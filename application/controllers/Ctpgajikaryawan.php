<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctpgajikaryawan extends CI_Controller {
    private $sTable = 'vtrans_periode_gaji_karyawan';
    private $sTables = 'master_gaji_karyawan';
    private $sTabless = 'master_gaji';
    
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtpgajikaryawan','tpgajikaryawan');
	}

	public function index(){
        $data['query'] = $this->db->get($this->sTabless);
        $this->load->helper('url');
        $this->header();
        $this->load->view('tpgajikaryawan_view', $data);
	}

    function calculate() {
        $sql = "CALL hitunggaji";
        $sql2 = "CALL hitung_pph21";
        $sql3 = "CALL hitung_pph21_thr";
        $sql4 = "CALL hitung_pph21_bonus";
        $sql5 = "CALL hitung_jamsostek";

        $XPeriode = $this->session->userdata('periode');
        $XPeriode_Penggajian = 'BULANAN';
        $XSegmen = '1';

        $this->db->query("$sql ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        $this->db->query("$sql2 ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        $this->db->query("$sql3 ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        $this->db->query("$sql4 ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        $this->db->query("$sql5 ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        redirect('ctpgajikaryawan');
    }

    function list_tpgajikaryawan() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL vtrans_periode_gaji_karyawans ($ot)";
        $aColumns = array();
        $parameters = array();
        $query = $this->db->query($sql, $aColumns);
        $sIndexColumn = 'nik';
        $input =& $_POST;
            
            $aOrderingRules = array();
            if ( isset( $input['iSortCol_0'] ) ) {
                    $iSortingCols = intval( $input['iSortingCols'] );
                    for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
                            if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
                                    $aOrderingRules[] =
                    "`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
                    .($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
                            }
                    }
            }

            if (!empty($aOrderingRules)) {
                    $sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
                    } else {
                    $sOrder = "";
            }
            
            $iColumnCount = count($aColumns);
    
            if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
                $aFilteringRules = array();
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
                        $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string( $input['sSearch'] )."%'";
                        }
                    }
                    if (!empty($aFilteringRules)) {
                        $aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
                    }
            }
            
            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
                    $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string($input['sSearch_'.$i])."%'";
                }
            }

            if (!empty($aFilteringRules)) {
                $sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
                } else {
                $sWhere = "";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            $output = "";
            // $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                
                    
                    $row = array($aRow['nik'],$aRow['periode'],$aRow['nama'],$aRow['departemen'],$aRow['jabatan'],$this->fungsi->dateToIndo($aRow['tanggal_masuk']));
                    $btn = array('<a target="_blank" title="Laporan : '.$aRow['nama'].'" class="text-dark mr-1" href="claporan/gaji/'.$aRow['nik'].'/'.$aRow['periode'].'"><i class="fa fa-list"></i></a>
                        <a target="_blank" title="Laporan Periode : '.$aRow['periode'].'" class="text-dark mr-2" href="claporanall/gaji/'.$aRow['periode'].'"><i class="fa fa-bars"></i></a>');
                        // <a title="Detail" class="text-dark" href="ctpgajikaryawan/detail/'.$aRow['nik'].'"/'.$aRow['periode'].'"><i class="fa fa-info"></i></a>');
                    $data[] = array_merge($btn,$row);
                    $output['aaData'] = $data;
                    
                
                $row = array();
                    
            }
            echo json_encode( $output );
    }
    
    public function delete($nik,$periode){
        $this->db->query("delete from trans_periode_gaji_karyawan where nik='$nik' and periode='$periode'");
        redirect('ctpgajikaryawan');
    }

    function closingperiod(){
        $last = date("Y-m-d h:i:s");
        $periode = $this->session->userdata('periode');
        $data = array(
            'status' => 'CLOSE',
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $periode = $this->session->userdata('periode');
        $this->db->where('periode', $periode);
        $this->db->update('trans_periode', $data);
        redirect('ctpgajikaryawan');
    }
    // public function detail($nik){
    //     $ot = $this->session->userdata('periode');
    //     $sql = "CALL vtrans_periode_gaji_karyawans ($ot) where='".$nik."'";
    //     $query = $this->db->query($sql, "*");


    //     var_dump($query->result());
    // }
    public function excel(){
        $dataResult = array();

        $ot = $this->session->userdata('periode');
        $sql = "CALL vtrans_periode_gaji_karyawans ($ot)";
        $aColumns = array();
        $parameters = array();
        $query = $this->db->query($sql, $aColumns);
        $sIndexColumn = 'nik';
        $input =& $_POST;
        $iColumnCount = count($aColumns);
        $aQueryColumns = array();
        foreach ($aColumns as $col) {
            if ($col != ' ') {
                $aQueryColumns[] = $col;
            }
        }
        //$output = "";

        // $dataColomHeader = $this->db->get($this->sTabless);
        // $header = array('NIK','Periode','Nama','Departemen','jabatan','Tanggal Masuk');

        // foreach ($dataColomHeader->result() as $value) {
        //     array_push($header, $value->keterangan);
        // }
        $this->db->reconnect();
        foreach ($query->result_array() as $aRow) {
            $p = $this->session->userdata('periode');
            $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
            
                $row = array_values($aRow);
                $data[] = array_merge($row);
                $dataResult = $data;
                
        }
        
            // 'Gaji Pokok','Tunjangan Transport','Tunjangan Kendaraan',
            // 'Tunjangan Makan','Dependence Allowance','Sales Insentive','Bonus Tahunan','THR','Potongan Koperasi',
            // 'Adjusment Plus','Adjusment Minus');

        // $data2[] = $dataResult;


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"PERIODE GAJI KARYAWAN -".date("Ymdhis").".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        

        $handle = fopen('php://output', 'w');


        foreach ($data as $data_array) {
            fputcsv($handle, $data_array);
        }
            fclose($handle);
        exit;
    }
    function header(){
        $data['active_accordion'] = "e";
        $data['aria_expanded'] = "e";
        $data['sub_menu_show'] = "e";
        $data['active_menu'] = 23;
        $this->load->view('header',$data);
    }
}