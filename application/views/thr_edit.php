
    <?php echo form_open('cthr/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>THR Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cthr" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-1 btn-sm">&nbsp;</div>
                <div class="rows col-md-9 wellsy btn-sm">
                    <?php 
                    $q = $this->db->query("SELECT a.nik,b.nama FROM master_gaji_karyawan_periode a join master_karyawan b on a.nik=b.nik WHERE a.nik='$nik'");
                    $nik = $q->row('nik');
                    ?>
                    <input name="jum" value="<?php echo $q->num_rows();?>" type="hidden" class="form-control col-md-4 input-smm" />
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="nik" class="col-sm-29 control-label">NIK</label>
                            <div class="col-sm-10">
                              <input value="<?php echo $q->row('nik');?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK">
                              <input name="nik" value="<?php echo $q->row('nik');?>" type="hidden" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nik" class="col-sm-29 control-label">Nama</label>
                            <div class="col-sm-10">
                              <input value="<?php echo $q->row('nama');?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm">
                            </div>
                        </div>
                        
                        <?php 
                        foreach($query->result() as $row):
                        ?>
                        <div class="rows col-md-4 wellsy btn-sm">
                            <?php 
                            $qs = $this->db->query("SELECT * FROM master_gaji where kode_gaji='309'")->row();
                            ?>
                            <div class="form-group">
                                <label for="kode_gaji"><?php echo $qs->keterangan;?></label>
                                <input maxlength="25" name="nilai_gaji" value="<?php echo $row->nilai_gaji;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_gaji" placeholder="Nilai Gaji" />
                                <input name="kode_gaji" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control col-md-4 input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                            </div>
                        </div>
                        <?php endforeach;?>

                        
                </div>
            </div>
        </div>
        </form>
    </div>
    <?php echo form_close(); ?>

<?php include "footer.php" ?>
</body>
</html>