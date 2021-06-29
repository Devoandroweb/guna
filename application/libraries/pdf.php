<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
include APPPATH.'libraries/tcpdf/tcpdf.php';

class Pdf extends TCPDF {
    function __construct() {
        $this->CI =& get_instance();
        parent::__construct();
    }
} 