  <div class="container">
    <br /><br /><br />
    <table id="ta" class="stripe row-border order-column table-hover table table-condensed table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Act</th>
                <th>nik</th>
                <th>nama</th>
                <th>departemen</th>
                <th>grup</th>
                <th>jabatan</th>
                <th>tanggal</th>
                <th>jadwal_masuk</th>
                <th>jadwal_pulang</th>
                <th>shift</th>
                <th>status_aktual</th>
                <th>masuk</th>
                <th>pulang</th>
                <th>terlambat</th>
                <th>lembur_awal</th>
                <th>pulang_lebih_cepat (Menit)</th>
                <th>lastupdate</th>
                <th>user_id</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Act</th>
                <th>nik</th>
                <th>nama</th>
                <th>departemen</th>
                <th>grup</th>
                <th>jabatan</th>
                <th>tanggal</th>
                <th>jadwal_masuk</th>
                <th>jadwal_pulang</th>
                <th>shift</th>
                <th>status_aktual</th>
                <th>masuk</th>
                <th>pulang</th>
                <th>terlambat</th>
                <th>lembur_awal</th>
                <th>pulang_lebih_cepat</th>
                <th>lastupdate</th>
                <th>user_id</th>
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
            } else if ($(this).text() == "nik"){
                $(this).html( '' );
            } else if ($(this).text() == "nama"){
                $(this).html( '' );
            } else {
                var title = $('#ta thead th').eq( $(this).index() ).text();
                $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
            }
        } );
        
        var table = $('#ta').DataTable({
            oLanguage: {
              sLoadingRecords: '<img src="assets/ajax-loader.gif">'
            },
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "sAjaxSource": "<?php echo site_url('cabsena/list_absena')?>",
            "sServerMethod": "POST",
            lengthChange: true,

            dom: '<"top">C<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
            colVis: {
                exclude: [ 0,1,2 ],
                buttonText: 'Pilih kolom'
            },
            
            columnDefs: [
                {
                    targets: [-1],
                    visible: false
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
            scrollCollapse: true,
            paging:         true,
            fixedColumns:   {
                leftColumns: 2
            }
            });
            $("div.toolbar").html('<div class="text-center"><strong>Transaksi Absen Aktual - Detail</strong></div>');
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