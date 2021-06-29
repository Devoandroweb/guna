
        <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js'); ?>"></script>
        
<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Employee Upload</h4>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-danger" href="<?php echo base_url('download/karyawan.csv'); ?>"><i class="fa fa-file-excel-o"></i> Template</a>
                        </div>
                    </div>
                
                
                <hr>
                    <div class="">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                        <label for="x" class="col control-label">Tabel</label>
                                        <div class="col-sm-5">
                                            <input name="tb1" id="tb1" value="client" type="hidden" />
                                            <label for="x" class="col-sm-12 control-label font-weight-bold">Master Karyawan</label>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label for="x" class="col control-label">File (csv)</label>
                                        <form method="post" action="<?php echo base_url("ckaryawan/form"); ?>" enctype="multipart/form-data">
                                            <input required="required" class="btn btn-default border" type="file" name="file"><br />
                                            <input class="btn btn-primary mt-2" type="submit" name="preview" value="Preview">
                                        </form>
                                    </div>
                                </div>
                                <br />
                                <div class="row scroll">
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
                                                    echo "<form method='post' action='".base_url("ckaryawan/import")."'>";
                                                    // echo "<div style='color: red;' id='kosong'>
                                                    // Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.
                                                    // </div>";
                                                    echo "<div id='kosong' class='alert alert-danger' role='alert'>Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.</div>";
                                                    echo "<table cellspacing='0' width='100%' class='table table-hover table-bordered' border='1' cellpadding='4'>
                                                    <tr>
                                                        <th colspan='25'>Preview Data</th>
                                                    </tr>
                                                    <tr>
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

                                                        $nik            = $get[0];
                                                        $nama           = $get[1];
                                                        $jenis_kelamin  = $get[2];
                                                        $tanggal_lahir  = $get[3];
                                                        $agama          = $get[4];
                                                        $status_perkawinan  = $get[5];
                                                        $alamat         = $get[6];
                                                        $telepon        = $get[7];
                                                        $email          = $get[8];
                                                        $departemen     = $get[9];
                                                        $grup           = $get[10];
                                                        $jabatan        = $get[11];
                                                        $tanggal_masuk  = $get[12];
                                                        $akhir_kontrak  = $get[13];
                                                        $status         = $get[14];
                                                        $npwp           = $get[15];
                                                        $bank           = $get[16];
                                                        $no_rekening    = $get[17];
                                                        $pemilik_rekening   = $get[18];
                                                        $periode_penggajian = $get[19];
                                                        $mata_uang          = $get[20];
                                                        $pph21_metode       = $get[21];
                                                        $bpjs_kesehatan     = $get[22];
                                                        $enroll             = $get[23];
                                                        $aktif              = $get[24];

                                                        if($nik == "" && $nama == "" && $jenis_kelamin == "" && $tanggal_lahir == "" && $agama == "" && $status_perkawinan == "" && $alamat == "" && 
                                                            $telepon == "" && $email == "" && $departemen == "" && $grup == "" && $jabatan == "" && $tanggal_masuk == "" && $akhir_kontrak == "" && $status == "" && 
                                                            $npwp == "" && $bank == "" && $no_rekening == "" && $pemilik_rekening == "" && $periode_penggajian == "" && $mata_uang == "" && 
                                                            $pph21_metode == "" && $bpjs_kesehatan == "" && $enroll == "" && $aktif =="")
                                                            continue;
                                                        if($numrow > 1){
                                                            $nik_td             = ($nik == "")? " style='background: #E07171;'" : "";
                                                            $nama_td            = ($nama == "")? " style='background: #E07171;'" : "";
                                                            $jenis_kelamin_td   = ($jenis_kelamin == "")? " style='background: #E07171;'" : ""; // Jika Jenis Kelamin kosong, beri warna merah
                                                            $tanggal_lahir_td   = ($tanggal_lahir == "")? " style='background: #E07171;'" : "";
                                                            $agama_td               = ($agama == "")? " style='background: #E07171;'" : "";
                                                            $status_perkawinan_td   = ($status_perkawinan == "")? " style='background: #E07171;'" : "";
                                                            $alamat_td              = ($alamat == "")? " style='background: #E07171;'" : "";
                                                            $telepon_td             = ($telepon == "")? " style='background: #E07171;'" : "";
                                                            $email_td               = ($email == "")? " style='background: #E07171;'" : "";
                                                            $departemen_td          = ($departemen == "")? " style='background: #E07171;'" : "";
                                                            $grup_td                = ($grup == "")? " style='background: #E07171;'" : "";
                                                            $jabatan_td             = ($jabatan == "")? " style='background: #E07171;'" : "";
                                                            $tanggal_masuk_td       = ($tanggal_masuk == "")? " style='background: #E07171;'" : "";
                                                            $akhir_kontrak_td       = ($akhir_kontrak == "")? " style='background: #E07171;'" : "";
                                                            $status_td              = ($status == "")? " style='background: #E07171;'" : "";
                                                            $npwp_td                = ($npwp == "")? " style='background: #E07171;'" : "";
                                                            $bank_td                = ($bank == "")? " style='background: #E07171;'" : "";
                                                            $no_rekening_td         = ($no_rekening == "")? " style='background: #E07171;'" : "";
                                                            $pemilik_rekening_td    = ($pemilik_rekening == "")? " style='background: #E07171;'" : "";
                                                            $periode_penggajian_td  = ($periode_penggajian == "")? " style='background: #E07171;'" : "";
                                                            $mata_uang_td           = ($mata_uang == "")? " style='background: #E07171;'" : "";
                                                            $pph21_metode_td        = ($pph21_metode == "")? " style='background: #E07171;'" : "";
                                                            $bpjs_kesehatan_td      = ($bpjs_kesehatan == "")? " style='background: #E07171;'" : "";
                                                            $enroll_td              = ($enroll == "")? " style='background: #E07171;'" : "";
                                                            $aktif_td               = ($aktif == "")? " style='background: #E07171;'" : "";    

                                                            if($nik == "" or 
                                                                $nama == "" or 
                                                                $jenis_kelamin == "" or 
                                                                $tanggal_lahir == "" or 
                                                                $agama == "" or 
                                                                $status_perkawinan == "" or 
                                                                $alamat == "" or 
                                                                $telepon == "" or 
                                                                $email == ""or 
                                                                $departemen == "" or 
                                                                $grup == "" or 
                                                                $jabatan == "" or 
                                                                $tanggal_masuk == "" or 
                                                                $akhir_kontrak == "" or 
                                                                $status == "" or 
                                                                $npwp == ""or 
                                                                $bank == "" or 
                                                                $no_rekening == "" or 
                                                                $pemilik_rekening == "" or 
                                                                $periode_penggajian == "" or 
                                                                $mata_uang == "" or 
                                                                $pph21_metode == "" or 
                                                                $bpjs_kesehatan == "" or 
                                                                $enroll == "" or 
                                                                $aktif ==""){
                                                                    $kosong++;
                                                            }

                                                            echo "<tr>";
                                                                echo "<td".$nik_td.">".$nik."</td>";
                                                                echo "<td".$nama_td.">".$nama."</td>";
                                                                echo "<td".$jenis_kelamin_td.">".$jenis_kelamin."</td>";
                                                                echo "<td".$tanggal_lahir_td.">".$tanggal_lahir."</td>";
                                                                echo "<td".$agama_td.">".$agama."</td>";
                                                                echo "<td".$status_perkawinan_td.">".$status_perkawinan."</td>";
                                                                echo "<td".$alamat_td.">".$alamat."</td>";
                                                                echo "<td".$telepon_td.">".$telepon."</td>";
                                                                echo "<td".$email_td.">".$email."</td>";
                                                                echo "<td".$departemen_td.">".$departemen."</td>";
                                                                echo "<td".$grup_td.">".$grup."</td>";
                                                                echo "<td".$jabatan_td.">".$jabatan."</td>";
                                                                echo "<td".$tanggal_masuk_td.">".$tanggal_masuk."</td>";
                                                                echo "<td".$akhir_kontrak_td.">".$akhir_kontrak."</td>";
                                                                echo "<td".$status_td.">".$status."</td>";
                                                                echo "<td".$npwp_td.">".$npwp."</td>";
                                                                echo "<td".$bank_td.">".$bank."</td>";
                                                                echo "<td".$no_rekening_td.">".$no_rekening."</td>";
                                                                echo "<td".$pemilik_rekening_td.">".$pemilik_rekening."</td>";
                                                                echo "<td".$periode_penggajian_td.">".$periode_penggajian."</td>";
                                                                echo "<td".$mata_uang_td.">".$mata_uang."</td>";
                                                                echo "<td".$pph21_metode_td.">".$pph21_metode."</td>";
                                                                echo "<td".$bpjs_kesehatan_td.">".$bpjs_kesehatan."</td>";
                                                                echo "<td".$enroll_td.">".$enroll."</td>";
                                                                echo "<td".$aktif_td.">".$aktif."</td>";
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
                                                    <?php
                                                    } else {
                                                        echo "<hr>";
                                                        echo "<button type='submit' name='import'>Import</button> ";
                                                        echo "<a href='".base_url("ckaryawan")."'>Cancel</a>";
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
  <?php include "footer.php" ?>
</body>
</html>


