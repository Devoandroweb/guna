<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'master_absen_jadwal_karyawan';
 
// Table's primary key
$primaryKey = 'nik';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'nik',           'dt' => 0 ),
    array( 'db' => 'tanggal',       'dt' => 1 ),
    array( 'db' => 'grup',          'dt' => 2 ),
    array( 'db' => 'jenis',         'dt' => 3 ),
    array( 'db' => 'shift',         'dt' => 4 ),
    array( 'db' => 'status_jadwal', 'dt' => 5 ),
    array( 'db' => 'jadwal_masuk',  'dt' => 6 ),
    array( 'db' => 'jadwal_pulang', 'dt' => 7 )
);
 
// SQL server connection information
$sql_details = array(
    'user' => '',
    'pass' => '',
    'db'   => '',
    'host' => ''
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require("<?php echo site_url('assets/server_side/ssp.class.php')?>");
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);