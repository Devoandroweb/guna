<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsenaktualth extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsenaktualth','absenaktualth');
    }

    public function index(){
        //$data['query'] = $this->db->get($this->sTabless);
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('absenaktualth_view');
    }

    public function search(){
        $keyword = $this->uri->segment(3);
        $data = $this->db->from('master_karyawan')->like('nik',$keyword)->get();
        foreach($data->result() as $row)
        {
            $arr['query'] = $keyword;
            $arr['suggestions'][] = array(
                'value' =>$row->nik,
                'nama'   =>$row->nama,
            );
        }
        echo json_encode($arr);
    }

    function list_absenaktualth() {
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d");
        $sql = "SELECT DISTINCT a.nik, a.nama, a.departemen, a.grup, a.jabatan
        FROM master_karyawan a
        WHERE NOT EXISTS 
        (SELECT * from master_mesin_clear 
        where master_mesin_clear.nik=a.nik and master_mesin_clear.periode='$ot')";
        $aColumns = array();
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
                $sWhere = " WHERE periode='$ot'";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            $output = "";
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT DISTINCT a.nik, a.nama, a.departemen, a.grup, a.jabatan
                FROM master_karyawan a
                WHERE NOT EXISTS 
                (SELECT * from master_absen_aktual 
                where master_absen_aktual.nik=a.nik and master_absen_aktual.periode=$p)")->row();
                $row = array_values($aRow);
                $btn = array('<i class="glyphicon glyphicon-pencil"></i><i class="glyphicon glyphicon-trash"></i>');
                $data[] = array_merge($btn,$row);
                $output['aaData'] = $data;
            }
            echo json_encode($output);
    }
}
