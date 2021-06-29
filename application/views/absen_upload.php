<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Absen Aktual</h4>  
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-danger" href="<?php echo site_url()?>download/absensi.csv"><i class="fa fa-file-excel-o"></i> Template</a>
                        </div>
                    </div>
                <hr>
                    <div class="container">
                        <div class="rows col-md-12 wells">
                            <div class="row">   
                                <div class="rows col-md-12 ">
                                    
                                    <div class="form-group">
                                        <label for="x" class="col-sm-12 control-label">Tabel</label>
                                        <div class="col-sm-5">
                                            <input name="tb1 mb-2" id="tb1" value="client" type="hidden" />
                                            <label for="x" class="control-label font-weight-bold">Master Absen</label>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label for="x" class="col-sm-12 control-label">File (csv)</label>
                                        <div class="col-sm-12">
                                          <form method="post" action="<?php echo base_url() ?>cabsen/form" enctype="multipart/form-data">
                                                <input required="required" class="btn btn-default border" type="file" name="file"><br />
                                                <input class="btn-lgs btn btn-primary mt-2" type="submit" name="preview" value="Preview">
                                          </form>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <br />
                            <div class="row scrolls">
                                <div class="rows">
                                    <div class="form-group">
                                        <div class="col-sm-11">
                                            
                                            <?php
                                            if(isset($_POST['preview'])){
                                                if(isset($upload_error)){
                                                    echo "
                                                    <script type='text/javascript'>
                                                        window.alert('File upload yang dimasukkan salah.');
                                                        window.location.href='upload';
                                                    </script>";
                                                    //die;
                                                }
                                                echo "<form method='post' action='".base_url("cabsen/import")."'>";
                                                // echo "<div style='color: red;' id='kosong'>
                                                // Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.
                                                // </div>";
                                                echo "<div id='kosong' class='alert alert-danger' role='alert'>Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.</div>";
                                                echo "<table cellspacing='0' width='100%' class='table table-hover table-bordered' border='1' cellpadding='4'>
                                                <tr>
                                                    <th colspan='8'>Preview Data</th>
                                                </tr>
                                                <tr>
                                                    <th>enroll</th>
                                                    <th>no</th>
                                                    <th>nama</th>
                                                    <th>waktu</th>
                                                    <th>kondisi</th>
                                                    <th>kondisi_baru</th>
                                                    <th>status</th>
                                                    <th>operasi</th>
                                                    
                                                </tr>";
                                                $numrow = 1;
                                                $kosong = 0;
                                                foreach($sheet as $row){
                                                    $cellIterator = $row->getCellIterator();
                                                    $cellIterator->setIterateOnlyExistingCells(false);
                                                    $get = array();
                                                    foreach ($cellIterator as $cell) {
                                                        array_push($get, $cell->getValue());
                                                    }

                                                    $enroll         = $get[0];
                                                    $no             = $get[1];
                                                    $nama           = $get[2];
                                                    $waktu          = $get[3];
                                                    $kondisi        = $get[4];
                                                    $kondisi_baru   = $get[5];
                                                    $status         = $get[6];
                                                    $operasi        = $get[7];
                                                    

                                                    if($enroll == "" && $no == "" && $nama == "" && $waktu == "" && $kondisi == "" && $kondisi_baru == "" && $status == "" && $operasi == "")
                                                        continue;
                                                    if($numrow > 1){
                                                        $enroll_td          = ($enroll == "")? " style='background: #E07171;'" : "";
                                                        $no_td              = ($no == "")? " style='background: #E07171;'" : "";
                                                        $nama_td            = ($nama == "")? " style='background: #E07171;'" : "";
                                                        $waktu_td           = ($waktu == "")? " style='background: #E07171;'" : "";
                                                        $kondisi_td         = ($kondisi == "")? " style='background: #E07171;'" : "";
                                                        $kondisi_baru_td    = ($kondisi_baru == "")? " style='background: #E07171;'" : "";
                                                        $status_td          = ($status == "")? " style='background: #E07171;'" : "";
                                                        $operasi_td         = ($operasi == "")? " style='background: #E07171;'" : "";
                                                        
                                                        if($enroll == "" or $no == "" or $nama == "" or $waktu == "" or $kondisi == "" or $kondisi_baru == "" or $status == "" or $operasi == ""){
                                                            $kosong++;
                                                        }

                                                        echo "<tr>";
                                                            echo "<td".$enroll_td.">".$enroll."</td>";
                                                            echo "<td".$no_td.">".$no."</td>";
                                                            echo "<td".$nama_td.">".$nama."</td>";
                                                            echo "<td".$waktu_td.">".$waktu."</td>";
                                                            echo "<td".$kondisi_td.">".$kondisi."</td>";
                                                            echo "<td".$kondisi_baru_td.">".$kondisi_baru."</td>";
                                                            echo "<td".$status_td.">".$status."</td>";
                                                            echo "<td".$operasi_td.">".$operasi."</td>";
                                                        echo "</tr>";
                                                    }
                                                    $numrow++;
                                                }

                                                echo "</table>";
                                                if($kosong > 1){
                                                ?>
                                                    <script>
                                                    $(document).ready(function(){
                                                        $("#jumlah_kosong").html('<?php echo $kosong; ?>');
                                                        $("#kosong").show();
                                                    });
                                                    </script>
                                                    <button type='submit' class="btn btn-danger" name='import'>Import</button>
                                                    <a class='btn btn-light ml-2 text-muted' href='<?php echo base_url() ?>cabsen'>Cancel</a>
                                                <?php
                                                } else {
                                                    echo "<hr />";
                                                    echo "<button type='submit' class='btn btn-danger' name='import'>Import</button>";
                                                    echo "<a class='btn btn-light ml-2 text-muted' href='".base_url("cabsen")."'>Cancel</a>";
                                                }
                                                echo "</form>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

