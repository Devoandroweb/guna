<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Gaji Karyawan Periode Information</h4>
                <hr>
                <?php echo form_open('cgajikaryawanperiode/update');?>
                <div class="container">
                       
                    <div class="row">
                        <div class="col">
                            <?php 
                                $q = $this->db->query("SELECT a.nik,b.nama FROM master_gaji_karyawan_periode a join master_karyawan b on a.nik=b.nik WHERE a.nik='$nik'");
                                $nik = $q->row('nik');
                                ?>
                                <input name="jum" value="<?php echo $q->num_rows();?>" type="hidden" class="form-control input-smm" />
                                <form class="form-horizontal">
                                    <div class="form-group">
                                    <label for="nik" class="control-label">NIK</label>  
                                    <input value="<?php echo $q->row('nik');?>" disabled="disabled" type="text" class="form-control input-smm" id="nik" placeholder="NIK">
                                    <input name="nik" value="<?php echo $q->row('nik');?>" type="hidden" class="form-control input-smm" id="nik" placeholder="NIK" />
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="nik" class="col-sm-29 control-label">Nama</label>
                                        <input value="<?php echo $q->row('nama');?>" disabled="disabled" type="text" class="form-control input-smm">
                                    </div>
                                    <div class="form-group">
                                        <label for="periode" class="col-sm-29 control-label">Periode</label>
                                        <?php $periode = $this->session->userdata('periode');?>
                                        <input value="<?php echo $periode;?>" disabled="disabled" type="text" class="form-control input-smm" />
                                        <input name="periode" value="<?php echo $periode;?>" type="hidden" class="form-control input-smm" />
                                    </div>
                                    <?php 
                                    $n = 0;
                                    foreach($query->result() as $row):
                                    $n++;
                                    ?>
                                    <div class="rows wellsy">
                                        <?php 
                                        $qs = $this->db->query("SELECT * FROM master_gaji where kode_gaji='$row->kode_gaji'")->row();
                                        ?>
                                        <div class="form-group">
                                            <label for="kode_gaji"><?php echo $qs->keterangan;?></label>
                                            <input maxlength="25" name="nilai_gaji<?php echo $n;?>" value="<?php echo $row->nilai_gaji;?>" required="required" type="text" class="form-control input-smm" id="nilai_gaji" placeholder="Nilai Gaji" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                            <input name="kode_gaji<?php echo $n;?>" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                        </div>
                                    </div>
                                    <?php endforeach;?>

                                    <?php 
                                    $n = 0;
                                    $qs2 = $this->db->query("SELECT * FROM master_gaji WHERE kode_gaji NOT IN 
                                        (SELECT kode_gaji FROM master_gaji_karyawan_periode WHERE kode_gaji IS NOT NULL and nik='$nik')");
                                    $total = $qs2->num_rows();
                                    foreach($qs2->result() as $row):
                                    $n++;    
                                    ?>

                                    
                                    <div class="form-group">
                                        <input name="tambah" value="<?php echo $total;?>" type="hidden" class="form-control input-smm" />
                                        <label for="kode_gaji"><?php echo $row->keterangan;?></label>
                                        <input maxlength="25" name="nilai_gaji_baru<?php echo $n;?>" value="" required="required" type="text" class="form-control input-smm" id="nilai_gaji" placeholder="<?php echo $row->keterangan;?>"  />
                                        <input name="kode_gaji_baru<?php echo $n;?>" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control input-smm" id="kode_gaji" />
                                    </div>
                                    
                                    <?php endforeach;?>
                        </div>
                    </div>
                     <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cgajikaryawanperiode" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

<?php include "footer.php" ?>
</body>
</html>