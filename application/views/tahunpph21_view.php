
  <div class="container">
    <br /><br /><br />
    <table id="ta" class="stripe row-border order-column table-hover table table-condensed table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>nik</th>
                <th>nama</th>
                <?php foreach($query->result() as $row): ?>
                <th><?php echo $row->nama_bulan;?></th>
                <?php endforeach;?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>nik</th>
                <th>nama</th>
                <?php foreach($query->result() as $row):?>
                <th><?php echo $row->nama_bulan;?></th>
                <?php endforeach;?>
            </tr>
        </tfoot>
        <tbody></tbody>
    </table>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
        $('#ta tfoot th').each( function () {
            if($(this).text() == "Act"){
                $(this).html( '' );
            } else {
                var title = $('#ta thead th').eq( $(this).index() ).text();
                $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
            }
        } );
        
        var table = $('#ta').DataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                },
                
            oLanguage: {
              sLoadingRecords: '<img src="assets/ajax-loader.gif">'
            },
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "sAjaxSource": "<?php echo site_url('ctahunpph21/list_tahunpph21')?>",
            "sServerMethod": "POST",
            lengthChange: true,
            dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
            colVis: {
                exclude: [0,1],
                buttonText: 'Pilih kolom'
            },
            buttons: [
                {
                    extend:    'copyHtml5',
                    text:      'Copy',
                    titleAttr: 'Copy to clipboard',
                    footer: true,
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend:    'excelHtml5',
                    text:      'Excel',
                    titleAttr: 'Download excel file',
                    footer: true,
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend:    'pdfHtml5',
                    text:      'Pdf',
                    titleAttr: 'Download pdf file',
                    footer: true,
                    pageSize: 'A3',
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                }
            ],
            "language": {
                "lengthMenu": '_MENU_',
                "zeroRecords": "-kosong-",
                "infoEmpty": "-kosong-",
                "infoFiltered": "(_MAX_ total)",
                "search": " Cari: "
            },
            scrollY:        "440px",
            scrollX:        true,
            scrollCollapse: true
            });
            
            $("div.toolbar").html('<div class="text-center"><strong>Tahun Periode PPH21</strong></div>');
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
  <?php include "footer.php" ?>
</body>
</html>