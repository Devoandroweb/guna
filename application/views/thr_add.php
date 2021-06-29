    <?php echo form_open('cgajikaryawanperiode/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Gaji Karyawan Periode Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgajikaryawanperiode" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-1 btn-sm">&nbsp;</div>
                <div class="rows col-md-9 wellsy btn-sm">
                    <?php 
                    $q = $this->db->query("SELECT kode_gaji FROM master_gaji");
                    ?>
                    <input name="jum" value="<?php echo $q->num_rows();?>" type="hidden" class="form-control col-md-4 input-smm" />
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="nik" class="col-sm-29 control-label">NIK</label>
                            <div class="col-sm-10">
                              <input type="search" name="nik" required="required" class="autocomplete form-control col-md-4 input-smm" id="autocomplete" placeholder="NIK" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama" class="col-sm-29 control-label">Nama</label>
                            <div class="col-sm-10">
                              <input readonly type="text" name="nama" required="required" class="autocomplete form-control col-md-4 input-smm" id="v_nama" placeholder="Nama" />
                            </div>
                        </div>
                        <p>&nbsp;</p>
                        <?php 
                        $n = 0;
                        foreach($querys->result() as $row):
                        $n++;
                        ?>
                        <div class="rows col-md-4 wellsy btn-sm">
                            <div class="form-group">
                                <label for="kode_gaji"><?php echo $row->keterangan;?></label>
                                <input maxlength="25" name="nilai_gaji<?php echo $n;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_gaji" placeholder="<?php echo $row->keterangan;?>" />
                                <input name="kode_gaji<?php echo $n;?>" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control col-md-4 input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                            </div>
                        </div>

                    </form>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        
    </div>
    <?php echo form_close(); ?>

<?php include "footer.php" ?>
</body>
</html>
