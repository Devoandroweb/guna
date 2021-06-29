<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Gaji Information</h4>
                <hr>
                <?php echo form_open('cgaji/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                    <label for="kode_gaji">Kode Gaji</label>
                                    <input value="<?php echo $row->kode_gaji;?>" disabled="disabled" type="text" class="form-control input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                    <input name="kode_gaji" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <input maxlength="85" name="keterangan" value="<?php echo $row->keterangan;?>" required="required" type="text" class="form-control input-smm" id="nama_shift" placeholder="Keterangan" />
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <?php
                                    $jenis = $this->db->query("SELECT * FROM content_jenis_gaji WHERE jenis NOT LIKE '$row->jenis'")->result();
                                    ?>
                                    <select required="required" id="grup" name="jenis" class="form-control input-smm">
                                        <option selected="selected" value="<?php echo $row->jenis;?>"><?php echo $row->jenis;?></option>
                                        <?php
                                        foreach ($jenis as $bsd) {
                                        echo '<option value="'.$bsd->jenis.'">'.$bsd->jenis.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_hitung">Periode Hitung</label>
                                    <select required="required" id="periode_hitung" name="periode_hitung" class="form-control input-smm">
                                        <option></option>
                                        <?php
                                        if ($row->periode_hitung == "jam"){
                                            echo "
                                            <option selected='selected'>jam</option>
                                            <option>hari</option>
                                            <option>bulan</option>
                                            ";
                                        } else if ($row->periode_hitung == "hari"){
                                            echo "
                                            <option>jam</option>
                                            <option selected='selected'>hari</option>
                                            <option>bulan</option>
                                            ";
                                        } else if ($row->periode_hitung == "bulan"){
                                            echo "
                                            <option>jam</option>
                                            <option>hari</option>
                                            <option selected='selected'>bulan</option>
                                            ";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="rumus">Rumus</label>
                                    <textarea name="rumus" required="required" class="form-control input-smm" rows="5" placeholder="Rumus"><?php echo $row->rumus;?></textarea>
                                </div>
                            
                        </div>
                        
                    </div>
                    <?php endforeach;?>
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