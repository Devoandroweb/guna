
<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Transaksi Surat Perintah Lembur</h4>
                <hr>
                <?php echo form_open('cspl/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>

              
                    <div class="row">
                        <div class="col-md-6 wellsy">
                            <div class="form-group">
                                <label for="nik">ID SPL</label>
                                <input value="<?php echo $row->id_spl;?>" disabled="disabled" name="id_spl" type="text" class="form-control input-smm" id="id_spl" placeholder="ID SPL" />
                                <input name="id_spl" value="<?php echo $row->id_spl;?>" type="hidden" class="form-control input-smm" id="id_spl" />
                            </div>
                            
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input value="<?php echo $row->nik;?>" type="search" name="nik" required="required" class="autocomplete form-control input-smm" id="autocomplete" placeholder="NIK" />
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input readonly type="text" name="nama" required="required" class="autocomplete form-control input-smm" id="v_nama" placeholder="Nama" />
                            </div>

                            <!-- <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input id="tgla" value="<?php echo $row->tanggal;?>" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                            </div> -->
                            <div class="form-group">
                                  <label for="tanggal">Tanggal</label>
                                        <div id="" class="input-group date datepicker datepicker-popup">
                                        <input id="tgla" value="<?php echo $row->tanggal;?>" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                        <span class="input-group-addon input-group-append border-left">
                                                <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                   </div>
                             </div>
                            <div class="form-group">
                                <label for="mulai">Mulai Lembur (00:00)</label>
                                <input id="timepicker1" value="<?php echo $row->mulai;?>" required="required" name="mulai" type="text" class="form-control input-smm">
                            </div>
                                
                            <div class="form-group">
                                <label for="selesai">Selesai Lembur (00:00)</label>
                                <input id="timepicker2" value="<?php echo $row->selesai;?>" required="required" name="selesai" type="text" class="form-control input-smm">
                            </div>
                        </div>
                        
                        <div class="col-md-6 wellsy">
                            <div class="form-group">
                                <label for="jumlah_jam">Jumlah Jam Lembur</label>
                                <input maxlength="20" value="<?php echo $row->jumlah_jam;?>" name="jumlah_jam" required="required" type="text" class="form-control input-smm" id="jumlah_jam" placeholder="Jumlah Jam Lembur" />
                            </div>
                            <div class="form-group">
                                <label for="hari">Hari</label>
                                <select required="required" id="hari" name="hari" class="form-control input-smm">
                                    <option></option>
                                    <option>Kerja</option>
                                    <option>Libur</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"><?php echo $row->keterangan;?></textarea>
                            </div>

                        </div>
                    </div>
                    <?php endforeach;?>
                    <div class="row">
                        <div class="col-md-6">
                            <input class="btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cspl" class="btn btn-default text-muted">Cancel</a>
                        </div>
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