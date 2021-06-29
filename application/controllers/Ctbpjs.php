<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctbpjs extends CI_Controller {
    private $sTable = 'vlist_bpjs_periode';
    private $sTabless = 'master_jamsostek';

    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtbpjs','tbpjs');
    }

    public function index(){
        $data['query'] = $this->db->get($this->sTabless);
        $this->load->view('header');
        $this->load->view('tbpjs_view', $data);
    }

    function list_tbpjs() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL vlist_bpjs_periode ($ot)";
        $aColumns = array();
        $parameters = array();
        $query = $this->db->query($sql, $aColumns);
        $sIndexColumn = 'periode';
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
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
                $row = array_values($aRow);
                $btn = array('<a class="btn btn-smd btn-danger" href="ctbpjs/delete/'.$aRow['periode'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>
                ');
                $data[] = array_merge($row);
                $output['aaData'] = $data;
                $row = array();
            }
            echo json_encode( $output );
    }

}
