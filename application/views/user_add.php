
    <?php echo form_open('cuser/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Surat Perintah Lembur Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cuser" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-2 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="nik">ID SPL</label>
                        <input maxlength="20" value="<?=$kodeunik?>" name="id_spl" type="text" class="form-control col-md-4 input-smm" id="id_spl" placeholder="ID SPL" />
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="search" name="nik" required="required" class="autocomplete form-control col-md-4 input-smm" id="autocomplete" placeholder="NIK" />
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input readonly type="text" name="nama" required="required" class="autocomplete form-control col-md-4 input-smm" id="v_nama" placeholder="Nama" />
                    </div>
                        
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input id="tgla" name="tanggal" required="required" type="text" class="form-control col-md-4 input-smm" placeholder="Tanggal" />
                    </div>

                    <div class="form-group">
                        <label for="mulai">Mulai Lembur (00:00)</label>
                        <input id="timepicker1" required="required" name="mulai" type="text" class="form-control col-md-4 input-smm">
                    </div>
                        
                    <div class="form-group">
                        <label for="selesai">Selesai Lembur (00:00)</label>
                        <input id="timepicker2" required="required" name="selesai" type="text" class="form-control col-md-4 input-smm">
                    </div>
                </div>
                
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="jumlah_jam">Jumlah Jam Lembur</label>
                        <input maxlength="20" name="jumlah_jam" required="required" type="text" class="form-control col-md-4 input-smm" id="jumlah_jam" placeholder="Jumlah Jam Lembur" />
                    </div>
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select required="required" id="hari" name="hari" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option>Kerja</option>
                            <option>Libur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control col-md-4 input-smm" rows="2" placeholder="Keterangan"></textarea>
                    </div>
                    <br /><br /><br />
                </div>
            </div>
        </div>
    </div>
            <script type="text/javascript">
                $('#timepicker1').timepicker();
                $('#timepicker2').timepicker();
            </script>
    <?php echo form_close(); ?>
</body>
</html>