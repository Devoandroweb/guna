<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Surat Perintah Lembur Information</h4>
                <hr>
                <?php echo form_open('cspl/create');?>
                <div class="container">
                        
                    
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="rows col-md-6 wellsy">
                                
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="search" name="nik" required="required" class="autocomplete form-control input-smm" id="autocomplete" placeholder="NIK" />
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input readonly type="text" name="nama" required="required" class="autocomplete form-control input-smm" id="v_nama" placeholder="Nama" />
                                </div>
                                    
                                <!-- <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input id="tgla" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                </div> -->
                                <div class="form-group">
                                      <label for="tanggal_lahir">Tanggal</label>
                                            <div id="" class="input-group date datepicker datepicker-popup p-0">
                                            <input id="tgla" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                            <span class="input-group-addon input-group-append border-left">
                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                            </span>
                                       </div>
                                 </div>

                                <div class="form-group">
                                    <label for="mulai">Mulai Lembur (00:00)</label>
                                    <input id="timepicker1" required="required" name="mulai" type="text" class="form-control input-smm">
                                </div>
                                    
                                <div class="form-group">
                                    <label for="selesai">Selesai Lembur (00:00)</label>
                                    <input id="timepicker2" required="required" name="selesai" type="text" class="form-control input-smm">
                                </div>
                            </div>
                            
                            <div class="rows col-md-6 wellsy">
                                <div class="form-group">
                                    <label for="jumlah_jam">Jumlah Jam Lembur</label>
                                    <input maxlength="20" name="jumlah_jam" required="required" type="text" class="form-control input-smm" id="jumlah_jam" placeholder="Jumlah Jam Lembur" />
                                </div>
                                <div class="form-group">
                                    <label for="hari">Hari</label>
                                    <select required="required" id="hari" name="hari" class="form-control input-smm h45">
                                        <option></option>
                                        <option>Kerja</option>
                                        <option>Libur</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col">
                                <input class="btn-lgs btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cspl" class="btn-lgs btn btn-default text-muted">Cancel</a>
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