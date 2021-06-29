    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Employee Information</h4>
                    <?php echo form_open('ckaryawan/create');?>
                    <div class="container">

                      <!--   <div class="rows col-md-11 wells btn-info">
                            <div class="rows">
                                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Employee Information</strong></div>
                                <div class="col-xs-6 col-md-4" align="right">
                                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>ckaryawan" class="btn-lgs btn btn-default">Cancel</a></div>
                            </div>
                        </div> -->
                    
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input maxlength="20" name="nik" required="required" type="text" class="form-control" id="nik" placeholder="NIK" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input maxlength="25" type="email" class="form-control" id="email" name="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="npwp">NPWP</label>
                                <input maxlength="25" name="npwp" type="number" required="required" class="form-control" id="npwp" placeholder="NPWP" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input maxlength="25" name="nama" required="required" type="text" class="form-control" id="nama" placeholder="Nama" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="departemen">Departemen</label>
                                <select required="required" id="grup" name="departemen" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query3->result() as $row3):?>
                                    <option><?php echo $row3->departemen?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="bank">Bank</label>
                                <select required="required" id="bank" name="bank" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query7->result() as $row7):?>
                                    <option><?php echo $row7->bank?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" required="required" class="form-control h45">
                                    <option></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="grup">Grup</label>
                                <select required="required" id="grup" name="grup" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query4->result() as $row4):?>
                                    <option><?php echo $row4->grup?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="no_rekening">No Rekening</label>
                                <input maxlength="25" name="no_rekening" type="number" required="required" class="form-control" id="no_rekening" placeholder="No Rekening" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="text" required="required" class="form-control" name="tanggal_lahir" id="tgl" />
                                
                            </div> -->
                            
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <div id="" class="input-group date datepicker datepicker-popup">
                                <input type="text" class="form-control" name="tanggal_lahir"  placeholder="Tanggal Lahir" id="tgl">
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                                </span>
                              </div>
                        </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select required="required" id="jabatan" name="jabatan" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query5->result() as $row5):?>
                                    <option><?php echo $row5->jabatan?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="pemilik_rekening">Pemilik Rekening</label>
                                <input maxlength="25" name="pemilik_rekening" type="text" required="required" class="form-control" id="pemilik_rekening" placeholder="Pemilik Rekening" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <select required="required" id="agama" name="agama" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query->result() as $row):?>
                                    <option><?php echo $row->agama?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="status">Status Kerja</label>
                                <select required="required" id="status" name="status" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query6->result() as $row6):?>
                                    <option><?php echo $row6->status?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="periode_penggajian">Periode Penggajian</label>
                                <select id="periode_penggajian" required="required" name="periode_penggajian" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query8->result() as $row8):?>
                                    <option><?php echo $row8->periode_penggajian?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="status_perkawinan">Status Perkawinan</label>
                                <select required="required" id="sp" name="status_perkawinan" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query2->result() as $row2):?>
                                    <option><?php echo $row2->status_perkawinan?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        <!-- <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk</label>
                                <input id="tgls" name="tanggal_masuk" required="required" type="text" class="form-control" placeholder="Tanggal Masuk" />
                            </div> -->
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Masuk</label>
                                <div id="" class="input-group date datepicker datepicker-popup">
                                    <input id="tgls" name="tanggal_masuk" required="required" type="text" class="form-control" placeholder="Tanggal Masuk" />
                                    <span class="input-group-addon input-group-append border-left">
                                      <span class="mdi mdi-calendar input-group-text"></span>
                                    </span>
                                  </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="mata_uang">Mata Uang</label>
                                <select id="mata_uang" required="required" name="mata_uang" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query9->result() as $row9):?>
                                    <option><?php echo $row9->mata_uang?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" required="required" class="form-control" rows="2" placeholder="Alamat"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        <!-- <div class="form-group">
                                    <label for="akhir_kontrak">Akhir Kontrak</label>
                                    <input id="tgla" name="akhir_kontrak" required="required" type="text" class="form-control" placeholder="Akhir Kontrak" />
                                </div> -->
                                <div class="form-group">
                                      <label for="tanggal_lahir">Akhir Kontrak</label>
                                            <div id="" class="input-group date datepicker datepicker-popup">
                                            <input id="tgla" name="akhir_kontrak" required="required" type="text" class="form-control" placeholder="Akhir Kontrak" />
                                            <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                            </span>
                                       </div>
                                 </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="pph21_metode">PPH21 Metode</label>
                                <select required="required" id="pph21_metode" name="pph21_metode" class="form-control h45">
                                    <option></option>
                                    <?php foreach($query10->result() as $row10):?>
                                    <option><?php echo $row10->pph21_metode?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input maxlength="20" name="telepon" type="number" required="required" class="form-control" id="telepon" placeholder="Telepon" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bpjs_kesehatan">BPJS Kesehatan</label>
                                <select required="required" id="bpjs_kesehatan" name="bpjs_kesehatan" class="form-control h45">
                                    <option></option>
                                    <option>Ya</option>
                                    <option>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="enroll">Enroll</label>
                                <input maxlength="25" name="enroll" type="text" required="required" class="form-control" id="enroll" placeholder="Enroll" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="aktif">Aktif</label>
                                <select name="aktif" required="required" class="form-control h45">
                                    <option value="Active">Active</option>
                                    <option value="End">End</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" class="btn btn-primary" value="Simpan">
                            <a href="<?=base_URL()?>ckaryawan" class="btn btn-default text-muted">Batal</a>
                        </div>
                    </div>
                </div>
                
                <?php
                    echo form_close(); 
                ?>

                </div>  
            </div>

    <script type="text/javascript">
        $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $(".datepicker-popup").datepicker();
    </script>
    
<?php include "footer.php" ?>
</body>
</html>