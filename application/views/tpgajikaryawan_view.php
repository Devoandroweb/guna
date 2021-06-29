<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                          <h4 class="card-title">Transaksi Periode Gaji Karyawan</h4>
                        </div>
                        <div class="col text-right buttons">
                          <!-- <a href="<?= base_url() ?>ctpgajikaryawan/export" class="btn btn-primary ">
                            <i class="fa fa-plus"></i> 
                            Export
                          </a>
                          <a href="<?= base_url() ?>ctpgajikaryawan/calculate" class="btn btn-danger ">
                            <i class="fa fa-plus"></i> 
                            Calculate
                          </a> -->
                        </div>
                      </div>
                      <hr>
                    <div class="table-responsive">
                        <table id="ta" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Opsi</th>
                                    <th>NIK</th>
                                    <th>Periode</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Masuk</th>
                                    <!-- <?php foreach($query->result() as $row):?>
                                    <th><?php echo $row->keterangan;?></th>
                                    <?php endforeach;?> -->
                                </tr>
                            </thead>
                            </tfoot>
                            <tbody></tbody>
                        </table>
                      </div>

              <script type="text/javascript">
                $(document).ready(function() {
                    // $('#ta tfoot th').each( function () {
                    //     if($(this).text() == "Act"){
                    //         $(this).html( '' );
                    //     } else if ($(this).text() == "nik"){
                    //         $(this).html( '' );
                    //     } else if ($(this).text() == "nama"){
                    //         $(this).html( '' );
                    //     } else if ($(this).text() == "periode"){
                    //         $(this).html( '' );
                    //     } else {
                    //         var title = $('#ta thead th').eq( $(this).index() ).text();
                    //         $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
                    //     }
                    // });
                    
                    var SEARCH_COL = 2;
                    var table = $('#ta').DataTable({
                        // oLanguage: {
                        //   sLoadingRecords: '<img src="assets/ajax-loader.gif">',
                          
                        // },
                        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                        "iDisplayLength": 25,
                        "sServerMethod": "POST",
                        lengthChange: true,
                        ajax: "<?php echo site_url('ctpgajikaryawan/list_tpgajikaryawan')?>",
                        // dom: '<"top">BC<"clear"><"toolbar"><"clear">frt<"bottom">i<"clear">pl<"clear">',
                        // colVis: {
                        //     exclude: [ 0,1 ],
                        //     buttonText: 'Pilih kolom'
                        // },
                        <?php
                            $p = $this->session->userdata('periode');
                            $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                            if ($q->status == "OPEN"){
                        ?>
                            // buttons: [
                            // {
                            //     extend:    'excelHtml5',
                            //     text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
                            //     titleAttr: 'Download excel file',
                            //     exportOptions: {
                            //         columns: [ 1,3,4,5,7,8,9,10,11,12,13,14,15,16,17 ]
                            //     }
                            // },
                            // {
                            //     text: 'Calculate',
                            //     titleAttr: "Calculate",
                            //     cssClass: 'btn btn-primary',
                            //     action: function (e, dt, node, config) {
                            //         window.location.href = "ctpgajikaryawan/calculate";
                            //     }
                            // }
                            "drawCallback": function () {
                            $(".buttons").html('<a href="<?= base_url() ?>ctpgajikaryawan/excel" class="btn btn-success "><i class="fa fa-file-excel-o"></i> Export </a> <a href="<?= base_url() ?>ctpgajikaryawan/calculate" class="btn btn-danger "><i class="fa fa-share"></i> Calculate</a>');
                            },
                        <?php
                            } else if ($q->status == "CLOSE"){
                        ?>
                        "drawCallback": function () {

                            $(".buttons").html('<a href="<?= base_url() ?>ctpgajikaryawan/excel" class="btn btn-success" ><i class="fa fa-file-excel-o"></i> Export </a> <a href="<?= base_url() ?>ctpgajikaryawan/calculate" class="btn btn-danger "><i class="fa fa-share"></i> Calculate</a> <a href="<?= base_url() ?>ctpgajikaryawan/closingperiod" class="btn btn-info "><i class="fa fa-plus"></i> Closing Period</a>');
                        },
                        // buttons: [
                        //     {
                        //         extend:    'excelHtml5',
                        //         text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
                        //         titleAttr: 'Download excel file',
                        //         exportOptions: {
                        //             columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14 ]
                        //         }
                        //     },
                        //     {
                        //         text: 'Calculate',
                        //         titleAttr: "Calculate",
                        //         cssClass: 'btn btn-primary',
                        //         action: function (e, dt, node, config) {
                        //             window.location.href = "ctpgajikaryawan/calculate";
                        //         }
                        //     },

                        //     {
                        //         action: function (e, dt, node, config) {
                        //             window.location.href = "ctpgajikaryawan/closingperiod";
                        //         },
                        //         cssClass: 'btn btn-primary',
                        //         text: 'Closing period',
                        //         titleAttr: "Closing period"
                        //     }
                        // ],
                        <?php
                            }
                        ?>
                        
                        columnDefs: [
                            {
                                targets: [ ],
                                visible: false
                            }
                        ],
                        "language": {
                            "lengthMenu": 'se _MENU_',
                            "zeroRecords": "-kosong-",
                            "infoEmpty": "-kosong-",
                            "infoFiltered": "(_MAX_ total)",
                            "search": " Cari: "
                        },
                        
                        scrollY:        "440px",
                        scrollX:        true,
                        scrollCollapse: true,
                        paging:         true,
                        fixedColumns:   {
                            leftColumns: 4
                        },
                    });
                    <?php
                        $p = $this->session->userdata('periode');
                        $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                        if ($q->status == "OPEN"){
                        ?>
                        // table.buttons().enable();
                        $(".buttons").removeClass("disabled");
                        <?php
                        } else if ($q->status == "CLOSE"){
                        ?>
                        // table.buttons().disable();
                        $(".buttons").addClass("disabled");

                        <?php
                        }
                    ?>
                    $("div.toolbar").html('<div class="text-center"><strong>Transaksi Periode Gaji Karyawan</strong></div>');
                    $('div.dataTables_filter input').addClass('input-smx');
                    table.buttons().container().appendTo('#ta_wrapper .col-sm-6:eq(0)');
                    table.columns().indexes().each(function (idx){
                        $('input', table.column(idx).footer()).on('keyup change', function (){
                            table
                                .column(idx)
                                .search(this.value)
                                .draw();
                        });
                    });
                
                    function reload_table(){
                      table.ajax.reload(null,false);
                    }

                });
              </script>
              </div>
            </div>
    <?php include "footer.php" ?>
</body>            
</html>