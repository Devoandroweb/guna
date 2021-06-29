
<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Absen Shift Information</h4>
                <hr>
                <?php echo form_open('cabsensakitcuti/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="rows col-md-6 wellsy btn-sm">
                                    <div class="form-group">
                                        <label for="enroll">NIK</label>
                                        <input name="enroll" value="<?php echo $row->nik;?>" disabled="disabled" type="text" class="form-control input-smm" id="enroll" placeholder="Enroll" />
                                        <input name="no" value="<?php echo $row->no;?>" type="hidden" class="form-control input-smm" id="no" />
                                    </div>
                                    <?php
                                    $snama = $this->db->query("SELECT nama,departemen,grup,jabatan FROM master_karyawan where nik='$row->nik'")->row();
                                    if (!empty($snama)){
                                    ?>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input maxlength="25" name="nama" disabled="disabled" value="<?php echo $snama->nama;?>" required="required" type="text" class="form-control input-smm" id="nama" placeholder="Nama" />
                                    </div>
                         
                                    <div class="form-group">
                                        <label for="departemen">Departemen</label>
                                        <input maxlength="25" name="Departemen" disabled="disabled" value="<?php echo $snama->departemen;?>" required="required" type="text" class="form-control input-smm" id="departemen" placeholder="Departemen" />
                                    </div>
                     
                                    <div class="form-group">
                                        <label for="grup">Grup</label>
                                        <input maxlength="25" name="grup" disabled="disabled" value="<?php echo $snama->grup;?>" required="required" type="text" class="form-control input-smm" id="grup" placeholder="Grup" />
                                    </div>
                
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan</label>
                                        <input maxlength="25" name="jabatan" disabled="disabled" value="<?php echo $snama->jabatan;?>" required="required" type="text" class="form-control input-smm" id="jabatan" placeholder="Jabatan" />
                                    </div> 
                                    <?
                                    } 
                                    }
                                    ?>
                            </div>
                            <div class="rows col-md-6 wellsy btn-sm">
                                <!-- <div class="form-group">
                                    <label for="tanggal">Dari Tanggal</label>
                                    <input id="tgla" name="dari_tanggal" value="<?php echo $row->dari_tanggal;?>" required="required" type="text" class="form-control input-smm" placeholder="Dari Tanggal" />
                                </div> -->
                                <div class="form-group">
                                    <label for="tanggal_lahir">Dari Tanggal</label>
                                    <div id="" class="input-group date datepicker datepicker-popup">
                                        <input type="text" class="form-control" name="sampai_tanggal" value="<?php echo $row->dari_tanggal;?>" placeholder="Dari Tanggal" id="tgls">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="tanggal">Sampai Tanggal</label>
                                    <input id="tgls" name="sampai_tanggal" value="<?php echo $row->sampai_tanggal;?>" required="required" type="text" class="form-control input-smm" placeholder="Sampai Tanggal" />
                                </div> -->
                                <div class="form-group">
                                    <label for="tanggal_lahir">Sampai Tanggal</label>
                                    <div id="" class="input-group date datepicker datepicker-popup">
                                        <input type="text" class="form-control" name="sampai_tanggal" value="<?php echo $row->sampai_tanggal;?>" placeholder="Sampai Tanggal" id="tgls">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="status_aktual">Status</label>
                                            <?php
                                            $ssa = $this->db->query("SELECT status_aktual FROM content_status_aktual");
                                            ?>
                                        <select required="required" id="status" name="status" class="form-control input-smm">
                                            <option selected="selected" value="<?php echo $row->status;?>"><?php echo $row->status;?></option>
                                            <option></option>
                                            <?php foreach($ssa->result() as $ro):?>
                                            <option value="<?php echo $ro->status_aktual?>"><?php echo $ro->status_aktual?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"><?php echo $row->keterangan;?></textarea>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    } else {
                        echo'<div id="alert" class="alert alert-danger" role="alert">Unable load data karyawan, harap periksa kembali enroll.</div>';
                    } 
                    ?>
                    <?php endforeach;?>
                    <div class="rows col-md-11 wells">
                        <div class="rows">
                            <input class="btn-lgs btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cabsensakitcuti" class="btn-lgs btn btn-light text-muted">Cancel</a>
                                
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <script type="text/javascript">
             $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $(".datepicker-popup").datepicker();
        </script>
<?php include "footer.php" ?>
</body>
</html>