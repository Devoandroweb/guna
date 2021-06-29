<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                          <h4 class="card-title">Periode Salary</h4>
                        </div>
                        <div class="col text-right buttons">
                          <a href="<?= base_url() ?>ctsalary/excel" class="btn btn-success ">
                            <i class="fa  fa-file-excel-o"></i> 
                            Export
                          </a>
                          <!-- <a href="<?= base_url() ?>ctsalary/copy" class="btn btn-secondary ">
                            <i class="fa  fa-copy"></i> 
                            Copy
                          </a> -->
                          <!-- <a href="<?= base_url() ?>ctsalary/pdf" class="btn btn-danger ">
                            <i class="fa   fa-file-pdf-o"></i> 
                            PDF
                          </a> -->
                        </div>
                      </div>
                      <hr>
                    <div class="table-responsive">
                      <div class="">
                        <table id="t" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Periode Penggajian</th>
                                    <th>Segmen</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Bank</th>
                                    <th>No Rekening</th>
                                    <th>THP</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                      </div>
                  </div>
              </div>
          </div>
  <?php include "footer.php" ?>
  <script type="text/javascript">
    $(document).ready(function() {
        // $('#ta tfoot th').each( function () {
        //     if($(this).text() == "periode"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "periode_penggajian"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "segmen"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "nik"){
        //         $(this).html( '' );
        //     } 
        // } );
        
        // var table = $('#ta').DataTable({

        //     "footerCallback": function ( row, data, start, end, display ) {
        //             var api = this.api(), data;
        //             var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display;
        //             var intVal = function ( i ) {
        //                 return typeof i === 'string' ?
        //                     i.replace(/[\$,]/g, '')*1 :
        //                     typeof i === 'number' ?
        //                         i : 0;
        //             };

        //             if (api.column(7).data().length){
        //             var total = api
        //             .column( 7 )
        //             .data()
        //             .reduce( function (a, b) {
        //             return intVal(a) + intVal(b);
        //             },0 ) }
        //             else{ total = 0};
         
        //             // Total over this page
        //             if (api.column(7).data().length){
        //             var pageTotal = api
        //             .column( 7, { page: 'current'} )
        //             .data()
        //             .reduce( function (a, b) {
        //                 return intVal(a) + intVal(b);
        //             },0 ) }
        //             else{ pageTotal = 0};
         
        //             // Update footer
        //             $( api.column(7).footer() ).html(
        //             ''+numFormat(pageTotal)
        //             );
        //         },
                
        //     oLanguage: {
        //       sLoadingRecords: '<img src="assets/ajax-loader.gif">'
        //     },
        //     "bFooter": true,
        //     "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        //     "iDisplayLength": 25,
        //     "sAjaxSource": "<?php echo site_url('ctsalary/list_tsalary')?>",
        //     "sServerMethod": "POST",
        //     // lengthChange: true,
        //     // dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
        //     // colVis: {
        //     //     exclude: [0,1,2,3],
        //     //     buttonText: 'Pilih kolom'
        //     // },

        //     buttons: [
        //         {
        //             extend:    'copyHtml5',
        //             text:      'Copy',
        //             titleAttr: 'Copy to clipboard',
        //             footer: true,
        //             exportOptions: {
        //                 columns: [ 0, ':visible' ]
        //             }
        //         },
        //         {
        //             extend:    'excelHtml5',
        //             text:      'Excel',
        //             titleAttr: 'Download excel file',
        //             footer: true,
        //             exportOptions: {
        //                 columns: [ 0, ':visible' ]
        //             }
        //         },
        //         {
        //             extend:    'pdfHtml5',
        //             text:      'Pdf',
        //             titleAttr: 'Download pdf file',
        //             footer: true,
        //             exportOptions: {
        //                 columns: [ 0, ':visible' ]
        //             }
        //         }
        //     ],

        //     "language": {
        //         "lengthMenu": '_MENU_',
        //         "zeroRecords": "-kosong-",
        //         "infoEmpty": "-kosong-",
        //         "infoFiltered": "(_MAX_ total)",
        //         "search": " Cari: "
        //     },
        //     scrollY:        "440px",
        //     scrollX:        true,
        //     scrollCollapse: true
        //     });
            
            
        

        //     $("div.toolbar").html('<div class="text-center"><strong>Periode Salary</strong></div>');
        //     $('div.dataTables_filter input').addClass('input-smx');
        //     table.buttons().container().appendTo('#ta_wrapper .col-sm-6:eq(0)');
        //     table.columns().indexes().each(function (idx){
        //     $('input', table.column(idx).footer()).on('keyup change', function (){
        //         table
        //             .column(idx)
        //             .search(this.value)
        //             .draw();
        //     });
        // });
        
        // function reload_table(){
        //   table.ajax.reload(null,false);
        // }
        (function($) {
          'use strict';
          $(function() {
            var table = $('#t').DataTable({
              "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
              ],
              "sAjaxSource": "<?php echo site_url('ctsalary/list_tsalary')?>",
              "iDisplayLength": 10,
              "language": {
                search: ""
              },
              "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display;
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        if (api.column(7).data().length){
                        var total = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                        },0 ) }
                        else{ total = 0};
             
                        // Total over this page
                        if (api.column(7).data().length){
                        var pageTotal = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        },0 ) }
                        else{ pageTotal = 0};
             
                        // Update footer
                        $( api.column(7).footer() ).html(
                        ''+numFormat(pageTotal)
                        );
                    },
                oLanguage: {
                  sLoadingRecords: '<img src="assets/ajax-loader.gif">'
                },
                "sScrollX": false  
            });
            $('#t').each(function() {
              var datatable = $(this);
              // SEARCH - Add the placeholder for Search and Turn this into in-line form control
              var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
              search_input.attr('placeholder', 'Search');
              search_input.removeClass('form-control-sm');
              // LENGTH - Inline-Form control
              var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
              length_sel.removeClass('form-control-sm');
              
            });
            
            function reload_table(){
              table.ajax.reload(null,false);
            }
          });
        })(jQuery);     

    });
  </script>

</body>
</html>