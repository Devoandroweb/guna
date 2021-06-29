<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Gaji Information</h4>
                <hr>
                <?php echo form_open('cgaji/create');?>
                <div class="container">
                    
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                    <label for="kode_gaji">Kode Gaji</label>
                                    <input name="kode_gaji" type="text" class="form-control input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <input maxlength="85" name="keterangan" required="required" type="text" class="form-control input-smm" id="nama_shift" placeholder="Keterangan" />
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select required="required" id="jenis" name="jenis" class="form-control input-smm">
                                        <option></option>
                                        <?php foreach($query4->result() as $row):?>
                                        <option><?php echo $row->jenis?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_hitung">Periode Hitung</label>
                                    <select required="required" id="periode_hitung" name="periode_hitung" class="form-control input-smm">
                                        <option></option>
                                        <option>jam</option>
                                        <option>hari</option>
                                        <option>bulan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="rumus">Rumus</label>
                                    <textarea name="rumus" required="required" class="form-control input-smm" rows="5" placeholder="Rumus"></textarea>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input class="btn-lgs btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgaji" class="btn-lgs btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    <?php include "footer.php" ?>
</body>
</html>