<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Gaji Karyawan Information</h4>
                <hr>
                <?php echo form_open('cgajikaryawan/update');?>
                    <div class="">
                        <div class="rows wells">
                            <div class="row">
                                
                                <?php 
                                $q = $this->db->query("SELECT nik FROM master_gaji_karyawan WHERE nik='$nik'");
                                ?>
                                <input name="jum" value="<?php echo $q->num_rows();?>" type="hidden" class="form-control input-smm" />
                                <form class="form-horizontal">
                                    <div class="col-md-12 m-0 p-0 wellsy">
                                        <div class="form-group">
                                            <label for="nik" class="col-sm-12 control-label">NIK</label>
                                            <div class="col-sm-12">
                                              <input value="<?php echo $q->row('nik');?>" disabled="disabled" type="text" class="form-control input-smm" id="nik" placeholder="NIK">
                                              <input name="nik" value="<?php echo $q->row('nik');?>" type="hidden" class="form-control input-smm" id="nik" placeholder="NIK" />
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <?php 
                                    $n = 0;
                                    foreach($query->result() as $row):
                                    $n++;
                                    ?>
                                    
                                    <div class="rows col-md-12 wellsy">
                                        <?php 
                                        $qs = $this->db->query("SELECT * FROM master_gaji where kode_gaji='$row->kode_gaji'")->row();
                                        ?>
                                        <div class="form-group">
                                            <label for="kode_gaji"><?php echo $qs->keterangan;?></label>
                                            <input maxlength="25" name="nilai_gaji<?php echo $n;?>" value="<?php echo $row->nilai_gaji;?>" required="required" type="text" class="form-control col-md-12 input-smm" id="nilai_gaji" placeholder="Nilai Gaji" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'" />
                                            <input name="kode_gaji<?php echo $n;?>" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control col-md-12 input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                        </div>
                                    </div>

                                </form>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary " type="submit" value="Save">
                                <a href="<?=base_URL()?>cgajikaryawan" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                        
                    </div>
                <?php echo form_close(); ?>
</div>
</div>
<?php include "footer.php" ?>
</body>
</html>