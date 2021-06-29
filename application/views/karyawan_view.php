<div class="main-panel">
        <div class="content-wrapper">
            
            <!--<div class="row">
                <div class="col-md-12 col-lg-12">
                    <table id="ta" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Act</th>
                                <th>nik</th>
                                <th>nama</th>
                                <th>jenis_kelamin</th>
                                <th>tanggal_lahir</th>
                                <th>agama</th>
                                <th>status_perkawinan</th>
                                <th>alamat</th>
                                <th>telepon</th>
                                <th>email</th>
                                <th>departemen</th>
                                <th>grup</th>
                                <th>jabatan</th>
                                <th>tanggal_masuk</th>
                                <th>akhir_kontrak</th>
                                <th>status</th>
                                <th>npwp</th>
                                <th>bank</th>
                                <th>no_rekening</th>
                                <th>pemilik_rekening</th>
                                <th>periode_penggajian</th>
                                <th>mata_uang</th>
                                <th>pph21_metode</th>
                                <th>bpjs_kesehatan</th>
                                <th>enroll</th>
                                <th>aktif</th>
                                <th>lastupdate</th>
                                <th>user_id</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Act</th>
                                <th>nik</th>
                                <th>nama</th>
                                <th>jenis_kelamin</th>
                                <th>tanggal_lahir</th>
                                <th>agama</th>
                                <th>status_perkawinan</th>
                                <th>alamat</th>
                                <th>telepon</th>
                                <th>email</th>
                                <th>departemen</th>
                                <th>grup</th>
                                <th>jabatan</th>
                                <th>tanggal_masuk</th>
                                <th>akhir_kontrak</th>
                                <th>status</th>
                                <th>npwp</th>
                                <th>bank</th>
                                <th>no_rekening</th>
                                <th>pemilik_rekening</th>
                                <th>periode_penggajian</th>
                                <th>mata_uang</th>
                                <th>pph21_metode</th>
                                <th>bpjs_kesehatan</th>
                                <th>enroll</th>
                                <th>aktif</th>
                                <th>lastupdate</th>
                                <th>user_id</th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                    <div class="card mt-4">
                    <div class="card-body">
                      <h4 class="card-title">Master Karyawan</h4>
                      <div class="row">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table id="ta" class="table dataTables_wrapper">
                              <thead>
                                <tr>
                                    <th>Act</th>
                                    <th>nik</th>
                                    <th>nama</th>
                                    <th>jenis_kelamin</th>
                                    <th>tanggal_lahir</th>
                                    <th>agama</th>
                                    <th>status_perkawinan</th>
                                    <th>alamat</th>
                                    <th>telepon</th>
                                    <th>email</th>
                                    <th>departemen</th>
                                    <th>grup</th>
                                    <th>jabatan</th>
                                    <th>tanggal_masuk</th>
                                    <th>akhir_kontrak</th>
                                    <th>status</th>
                                    <th>npwp</th>
                                    <th>bank</th>
                                    <th>no_rekening</th>
                                    <th>pemilik_rekening</th>
                                    <th>periode_penggajian</th>
                                    <th>mata_uang</th>
                                    <th>pph21_metode</th>
                                    <th>bpjs_kesehatan</th>
                                    <th>enroll</th>
                                    <th>aktif</th>
                                    <th>lastupdate</th>
                                    <th>user_id</th>
                                </tr>
                              </thead>
                              <tbody>
                               
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
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
                            
                            $('#ta').DataTable( {
                                oLanguage: {
                                  sLoadingRecords: '<img src="assets/ajax-loader.gif">'
                                },
                                "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                                "iDisplayLength": 25,
                                "sAjaxSource": "<?php echo site_url('ckaryawan/list_karyawan')?>",
                                "sServerMethod": "POST",
                                // lengthChange: true,
                                // dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
                                // colVis: {
                                //     exclude: [ 0,1,2 ],
                                //     buttonText: 'Pilih kolom'
                                // },
                                buttons: [
                                    {
                                        action: function (e, dt, node, config) {
                                        window.location.href = "ckaryawan/tambah";
                                        },
                                        text: '<button type="button" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Data</a>',
                                        titleAttr: "Tambah data"
                                    },
                                    {
                                        extend:    'excelHtml5',
                                        className : 'exportExcel'
                                        text:      '<button type="button" class="btn btn-success exportExcel"><i class="fa fa-file-excel-o"></i>Export Excel</a>',
                                        titleAttr: 'Download excel file',
                                        exportOptions: {
                                            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26 ],
                                            format: {
                                                body: function ( data, column, row ) {
                                                return column === 7,8 ?
                                                data.replace( /[Rp.]/g, '' ) :
                                                data;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        action: function (e, dt, node, config) {
                                        window.location.href = "ckaryawan/upload";
                                        },
                                        text: '<div class="input-sm glyphicons glyphicon-plus-2"></div>',
                                        titleAttr: "Upload csv file"
                                    }
                                ],
                                columnDefs: [
                                    {
                                        targets: [-1,26],
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
                                    leftColumns: 3
                                }
                                });
                                
                                // $("div.toolbar").html('<div class="text-center"><strong>Master Karyawan</strong></div>');
                                $('div.dataTables_filter input').addClass('input-smx');
                                table.buttons().container().appendTo('.content-wrapper #actionButton');

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
                            $('#ta_length').css('margin-right','2rem');
                        });
                      </script> -->
            
<div class="modal fade" id="editImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel-2">Update Image</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                    <!-- <div class="dropify-wrapper">
                        <div class="dropify-message">
                            <span class="file-icon"></span> 
                            <p>Drag and drop a file here or click</p>
                            <p class="dropify-error">Ooops, something wrong appended.</p>
                        </div>
                    <div class="dropify-loader">
                        
                    </div>
                    <div class="dropify-errors-container">
                        <ul>
                            
                        </ul>
                    </div>
                    <input type="file" class="dropify">
                        <button type="button" class="dropify-clear">Remove</button><div class="dropify-preview">
                            <span class="dropify-render"></span>
                            <div class="dropify-infos">
                                <div class="dropify-infos-inner">
                                    <p class="dropify-filename">
                                        <span class="file-icon"></span> 
                                        <span class="dropify-filename-inner"></span>
                                    </p>
                                    <p class="dropify-infos-message">Drag and drop or click to replace</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <?php echo form_open_multipart('ckaryawan/upload_image',array('id'=>'formUploadImg'));?>
                <!-- <form id="formUploadImg" method="post" enctype="multipart/form-data"> -->
                    <input id="inputNikImage" type="hidden" name="nik" value="" />
                    <input type="file" name="file_upload_img" class="dropify"/>
                    
                </div>
                <div class="modal-footer">
                  <input  type="submit" class="btn btn-success" value="Save">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
                <!-- </form> -->
                <?= form_close(); ?>
                
              </div>
            </div>
          </div>
            <div class="modal fade" id="showImage" tabindex="-1" role="dialog" aria-labelledby="" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                    <button type="button" data-dismiss="modal" class="close mb-2">
                        <span aria-hidden="true">×</span>
                    </button>

                    <img id="img-show-in-modal" class="w-100" src="">
                </div>
              </div>
            </div>
          </div>
      <script>
            $('.dropify').dropify();
            function uploadImage($nik) {
                $('#inputNikImage').val($nik);
                $('#editImage').modal('show');
            }
            function imgShow($img){
                $('#img-show-in-modal').attr("src","<?= base_url() ?>uploads/karyawan/"+$img);
                $('#showImage').modal('show');
            }
        </script>
    <div class="row">
         <div class="col-md-12 col-lg-12">

            <div class="card">
                <div class="card-body">
                	<div class="row">
                        <div class="col-md-6 col-lg-6 text-left">
                            <h4 class="card-title">Master Karyawan</h4>
                        </div>

		                <div class="col-md-6 col-lg-6 text-right">
		                    <a href="<?php site_url() ?>ckaryawan/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                            <a href="<?php site_url() ?>ckaryawan/upload" class="btn btn-light"><i class="fa fa-upload"></i> Upload</a>
		                </div>
		            </div>
                    <hr>
                    <div class="">
                        <!-- <?= $this->session->flashdata('alert_img'); ?> -->
                        <table id="t" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Opsi</th>
                                    <th>Nik</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Foto</th>
                                    <!-- <th>Agama</th> -->
                                    <!-- <th>status_perkawinan</th>
                                    <th>alamat</th>
                                    <th>telepon</th>
                                    <th>email</th>
                                    <th>departemen</th>
                                    <th>grup</th>
                                    <th>jabatan</th>
                                    <th>tanggal_masuk</th>
                                    <th>akhir_kontrak</th>
                                    <th>status</th>
                                    <th>npwp</th>
                                    <th>bank</th>
                                    <th>no_rekening</th>
                                    <th>pemilik_rekening</th>
                                    <th>periode_penggajian</th>
                                    <th>mata_uang</th>
                                    <th>pph21_metode</th>
                                    <th>bpjs_kesehatan</th>
                                    <th>enroll</th>
                                    <th>aktif</th>
                                    <th>lastupdate</th>
                                    <th>user_id</th> -->
                                </tr>
                            </thead>
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
                            //     } else {
                            //         var title = $('#ta thead th').eq( $(this).index() ).text();
                            //         $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
                            //     }
                            // } );
                            
                            // $('#ta').DataTable( {
                            //     oLanguage: {
                            //       sLoadingRecords: '<img src="assets/ajax-loader.gif">'
                            //     },
                            //     "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                            //     "iDisplayLength": 25,
                            //     "sAjaxSource": "<?php echo site_url('ckaryawan/list_karyawan')?>",
                            //     "sServerMethod": "POST",
                            //     // lengthChange: true,
                            //     // dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
                            //     colVis: {
                            //         exclude: [ 0,1,2 ],
                            //         buttonText: 'Pilih kolom'
                            //     },
                            //     buttons: [
                            //         {
                            //             action: function (e, dt, node, config) {
                            //             window.location.href = "ckaryawan/tambah";
                            //             },
                            //             text: '<div class="input-sm glyphicons glyphicon-plus"></div>',
                            //             titleAttr: "Tambah data"
                            //         },
                            //         {
                            //             extend:    'excelHtml5',
                            //             text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
                            //             titleAttr: 'Download excel file',
                            //             exportOptions: {
                            //                 columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26 ],
                            //                 format: {
                            //                     body: function ( data, column, row ) {
                            //                     return column === 7,8 ?
                            //                     data.replace( /[Rp.]/g, '' ) :
                            //                     data;
                            //                     }
                            //                 }
                            //             }
                            //         },
                            //         {
                            //             action: function (e, dt, node, config) {
                            //             window.location.href = "ckaryawan/upload";
                            //             },
                            //             text: '<div class="input-sm glyphicons glyphicon-plus-2"></div>',
                            //             titleAttr: "Upload csv file"
                            //         }
                            //     ],
                            //     columnDefs: [
                            //         {
                            //             targets: [-1,26],
                            //             visible: false
                            //         }
                            //     ],
                            //     "language": {
                            //         "lengthMenu": 'se _MENU_',
                            //         "zeroRecords": "-kosong-",
                            //         "infoEmpty": "-kosong-",
                            //         "infoFiltered": "(_MAX_ total)",
                            //         "search": " Cari: "
                            //     },
                            //     scrollY:        "440px",
                            //     scrollX:        true,
                            //     scrollCollapse: true,
                            //     paging:         true,
                            //     fixedColumns:   {
                            //         leftColumns: 3
                            //     }
                            //     });
                                
                            //    // $("div.toolbar").html('<div class="text-center"><strong>Master Karyawan</strong></div>');
                            //    // $('div.dataTables_filter input').addClass('input-smx');
                            //    // table.buttons().container().appendTo('#ta_wrapper .col-sm-6:eq(0)');
                            //    /*
                            //     table.columns().indexes().each(function (idx){
                            //     $('input', table.column(idx).footer()).on('keyup change', function (){
                            //         table
                            //             .column(idx)
                            //             .search(this.value)
                            //             .draw();
                            //     });
                            // });
                            // */
                            function reload_table(){
                              table.ajax.reload(null,false);
                            }
                            (function($) {
							  'use strict';
							  $(function() {
							    $('#t').DataTable({
							      "aLengthMenu": [
							        [5, 10, 15, -1],
							        [5, 10, 15, "All"]
							      ],
							      "sAjaxSource": "<?php echo site_url('ckaryawan/list_karyawan')?>",
							      "iDisplayLength": 10,
							      "language": {
							        search: ""
							      }
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
							  });
							})(jQuery);
                        });
                      </script>
                </div>
            </div>
            
         </div>
    </div>

  <?php require "footer.php"; ?>
  <script type="text/javascript">
        <?= $this->session->flashdata('alert_img'); ?>
  </script>
</body>
</html>