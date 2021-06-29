<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Karyawan Information</h4>
                <hr>
                <?php echo form_open('ckaryawan/update');?>
                <div class="">
                        
                    <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input value="<?php echo $row->nik;?>" disabled="disabled" type="text" class="form-control input-smm" id="nik" placeholder="NIK" />
                                    <input name="nik" value="<?php echo $row->nik;?>" type="hidden" class="form-control input-smm" id="nik" placeholder="NIK" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="departemen">Departemen</label>
                                    <?php
                                    $departemen = $this->db->query("SELECT * FROM content_departemen WHERE departemen NOT LIKE '$row->departemen'")->result();
                                    ?>
                                    <select required="required" id="grup" name="departemen" class="form-control h45 input-smm">
                                        <option selected="selected" value="<?php echo $row->departemen;?>"><?php echo $row->departemen;?></option>
                                        <?php
                                        foreach ($departemen as $bsd) {
                                        echo '<option value="'.$bsd->departemen.'">'.$bsd->departemen.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank">Bank</label>
                                    <?php
                                    $bank = $this->db->query("SELECT * FROM content_bank WHERE bank NOT LIKE '$row->bank'")->result();
                                    ?>
                                    <select required="required" id="bank" name="bank" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->bank;?>"><?php echo $row->bank;?></option>
                                        <?php
                                        foreach ($bank as $bb) {
                                        echo '<option value="'.$bb->bank.'">'.$bb->bank.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input maxlength="25" name="nama" value="<?php echo $row->nama;?>" required="required" type="text" class="form-control input-smm" id="nama" placeholder="Nama" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grup">Grup</label>
                                    <?php
                                    $grup = $this->db->query("SELECT distinct grup FROM master_absen_grup_shift WHERE grup NOT LIKE '$row->grup'")->result();
                                    ?>
                                    <select required="required" id="grup" name="grup" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->grup;?>"><?php echo $row->grup;?></option>
                                        <?php
                                        foreach ($grup as $bg) {
                                        echo '<option value="'.$bg->grup.'">'.$bg->grup.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_rekening">No Rekening</label>
                                    <input maxlength="25" name="no_rekening" value="<?php echo $row->no_rekening;?>" type="text" required="required" class="form-control input-smm" id="no_rekening" placeholder="No Rekening" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" required="required" class="form-control input-smm h45">
                                        <?php
                                        if ($row->jenis_kelamin == "Laki-Laki"){
                                        ?>
                                        <option selected="selected" value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                        <?php
                                        } else {
                                        ?>
                                        <option value="Laki-Laki">Laki-laki</option>
                                        <option selected="selected" value="Perempuan">Perempuan</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <?php
                                    $jabatan = $this->db->query("SELECT * FROM content_jabatan WHERE jabatan NOT LIKE '$row->jabatan'")->result();
                                    ?>
                                    <select required="required" id="jabatan" name="jabatan" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->jabatan;?>"><?php echo $row->jabatan;?></option>
                                        <?php
                                        foreach ($jabatan as $bj) {
                                        echo '<option value="'.$bj->jabatan.'">'.$bj->jabatan.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pemilik_rekening">Pemilik Rekening</label>
                                    <input maxlength="25" name="pemilik_rekening" value="<?php echo $row->pemilik_rekening;?>" type="text" required="required" class="form-control input-smm" id="pemilik_rekening" placeholder="Pemilik Rekening" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                            <!-- <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="text" value="<?php echo $row->tanggal_lahir;?>" required="required" class="form-control input-smm" name="tanggal_lahir" id="tgl" placeholder="Tanggal Lahir" />
                                </div> -->
                                <div class="form-group">
                                      <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <div id="" class="input-group date datepicker datepicker-popup p-0">
                                            <input type="text" value="<?php echo $row->tanggal_lahir;?>" required="required" class="form-control input-smm" name="tanggal_lahir" id="tgl" placeholder="Tanggal Lahir" />
                                            <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                            </span>
                                       </div>
                                 </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status Kerja</label>
                                    <?php
                                    $status = $this->db->query("SELECT * FROM content_status_karyawan WHERE status NOT LIKE '$row->status'")->result();
                                    ?>
                                    <select required="required" id="status" name="status" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->status;?>"><?php echo $row->status;?></option>
                                        <?php
                                        foreach ($status as $bs) {
                                        echo '<option value="'.$bs->status.'">'.$bs->status.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periode_penggajian">Periode Penggajian</label>
                                    <?php
                                    $periode_penggajian = $this->db->query("SELECT * FROM content_periode_penggajian WHERE periode_penggajian NOT LIKE '$row->periode_penggajian'")->result();
                                    ?>
                                    <select id="periode_penggajian" required="required" name="periode_penggajian" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->periode_penggajian;?>"><?php echo $row->periode_penggajian;?></option>
                                        <?php
                                        foreach ($periode_penggajian as $bp) {
                                        echo '<option value="'.$bp->periode_penggajian.'">'.$bp->periode_penggajian.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="agama">Agama</label>
                                    <?php
                                    $agama = $this->db->query("SELECT * FROM content_agama WHERE agama NOT LIKE '$row->agama'")->result();
                                    ?>
                                    <select required="required" id="agama" name="agama" class="form-control h45 input-smm">
                                        <option selected="selected" value="<?php echo $row->agama;?>"><?php echo $row->agama;?></option>
                                        <?php
                                        foreach ($agama as $b) {
                                        echo '<option value="'.$b->agama.'">'.$b->agama.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <!-- <div class="form-group">
                                    <label for="tanggal_masuk">Tanggal Masuk</label>
                                    <input id="tgla" name="tanggal_masuk" value="<?php echo $row->tanggal_masuk;?>" required="required" type="text" class="form-control input-smm" placeholder="Tanggal Masuk" />
                                </div> -->
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Masuk</label>
                                    <div id="" class="input-group date datepicker datepicker-popup p-0">
                                        <input id="tgla" name="tanggal_masuk" value="<?php echo $row->tanggal_masuk;?>" required="required" type="text" class="form-control input-smm" placeholder="Tanggal Masuk" />
                                        <span class="input-group-addon input-group-append border-left">
                                                <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                   </div>
                                 </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mata_uang">Mata Uang</label>
                                    <?php
                                    $mata_uang = $this->db->query("SELECT * FROM content_mata_uang WHERE mata_uang NOT LIKE '$row->mata_uang'")->result();
                                    ?>
                                    <select id="mata_uang" required="required" name="mata_uang" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->mata_uang;?>"><?php echo $row->mata_uang;?></option>
                                        <?php
                                        foreach ($mata_uang as $bm) {
                                        echo '<option value="'.$bm->mata_uang.'">'.$bm->mata_uang.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_perkawinan">Status Perkawinan</label>
                                    <?php
                                    $status_perkawinan = $this->db->query("SELECT * FROM content_status_perkawinan WHERE status_perkawinan NOT LIKE '$row->status_perkawinan'")->result();
                                    ?>
                                    <select required="required" id="sp" name="status_perkawinan" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->status_perkawinan;?>"><?php echo $row->status_perkawinan;?></option>
                                        <?php
                                        foreach ($status_perkawinan as $bs) {
                                        echo '<option value="'.$bs->status_perkawinan.'">'.$bs->status_perkawinan.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <!-- <div class="form-group">
                                    <label for="akhir_kontrak">Akhir Kontrak</label>
                                    <input id="tgls" name="akhir_kontrak" value="<?php echo $row->akhir_kontrak;?>" required="required" type="text" class="form-control input-smm" placeholder="Akhir Kontrak" />
                                </div> -->
                                <div class="form-group">
                                      <label for="tanggal_lahir">Akhir Kontrak</label>
                                            <div id="" class="input-group date datepicker datepicker-popup p-0">
                                            <input id="tgls" name="akhir_kontrak" value="<?php echo $row->akhir_kontrak;?>" required="required" type="text" class="form-control input-smm" placeholder="Akhir Kontrak" />
                                            <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                            </span>
                                       </div>
                                 </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pph21_metode">PPH21 Metode</label>
                                    <?php
                                    $pph21_metode = $this->db->query("SELECT * FROM content_pph21_metode WHERE pph21_metode NOT LIKE '$row->pph21_metode'")->result();
                                    ?>
                                    <select required="required" id="pph21_metode" name="pph21_metode" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->pph21_metode;?>"><?php echo $row->pph21_metode;?></option>
                                        <?php
                                        foreach ($pph21_metode as $bss) {
                                        echo '<option value="'.$bss->pph21_metode.'">'.$bss->pph21_metode.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" required="required" class="form-control input-smm" rows="2" placeholder="Alamat"><?php echo $row->alamat;?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="npwp">NPWP</label>
                                    <input maxlength="25" name="npwp" value="<?php echo $row->npwp;?>" type="text" required="required" class="form-control input-smm" id="npwp" placeholder="NPWP" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bpjs_kesehatan">BPJS Kesehatan</label>
                                    <select name="bpjs_kesehatan" required="required" class="form-control input-smm h45">
                                        <?php
                                        if ($row->bpjs_kesehatan == "Ya"){
                                        ?>
                                        <option selected="selected" value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                        <?php
                                        } else {
                                        ?>
                                        <option value="Ya">Ya</option>
                                        <option selected="selected" value="Tidak">Tidak</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telepon">Telepon</label>
                                    <input maxlength="20" name="telepon" value="<?php echo $row->telepon;?>" type="text" required="required" class="form-control input-smm" id="telepon" placeholder="Telepon" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input maxlength="25" type="text" value="<?php echo $row->email;?>" class="form-control input-smm" name="email" placeholder="Email" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="enroll">Enroll</label>
                                    <input maxlength="25" name="enroll" value="<?php echo $row->enroll;?>" type="text" required="required" class="form-control input-smm" id="enroll" placeholder="Enroll" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="aktif">Aktif</label>
                                    <select name="aktif" required="required" class="form-control input-smm h45">
                                        <?php
                                        if ($row->aktif == "Active"){
                                        ?>
                                        <option selected="selected" value="Active">Active</option>
                                        <option value="End">End</option>
                                        <?php
                                        } else {
                                        ?>
                                        <option value="Active">Active</option>
                                        <option selected="selected" value="End">End</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                    <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                    <a href="<?=base_URL()?>ckaryawan" class="btn btn-light text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
                <script type="text/javascript">
                    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
                    $(".datepicker-popup").datepicker();
                </script>
<?php include "footer.php" ?>
</body>
</html>