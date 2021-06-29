<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Jamsostek Information</h4>
                <hr>

                <?php echo form_open('cjamsostek/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="rows col-md-12 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="nama_program">Nama Program</label>
                                    <input name="nama_program" required="required" value="<?php echo $row->nama_program;?>" type="text" class="form-control input-smm" id="nama_program" placeholder="Nama Program" />
                                    <input name="id" value="<?php echo $row->id;?>" type="hidden" class="form-control input-smm" />
                                </div>
                                <div class="form-group">
                                    <label for="bagian_perusahaan">Bagian Perusahaan</label>
                                    <input name="bagian_perusahaan" value="<?php echo $row->bagian_perusahaan;?>" required="required" type="text" class="form-control input-smm" id="bagian_perusahaan" placeholder="Bagian Perusahaan" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="bagian_karyawan">Bagian Karyawan</label>
                                    <input name="bagian_karyawan" value="<?php echo $row->bagian_karyawan;?>" required="required" type="text" class="form-control input-smm" id="bagian_karyawan" placeholder="Bagian Karyawan" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="maksimal_dasar">Maksimal Dasar</label>
                                    <input name="maksimal_dasar" value="<?php echo $row->maksimal_dasar;?>" required="required" type="text" class="form-control input-smm" id="maksimal_dasar" placeholder="Maksimal Dasar" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="kode_gaji_potongan">Kode Gaji Potongan</label>
                                    <?php
                                    $jenis = $this->db->query("SELECT * FROM master_gaji")->result();
                                    ?>
                                    <select required="required" id="kode_gaji_potongan" name="kode_gaji_potongan" class="form-control input-smm">
                                        <?php
                                        foreach ($jenis as $bsd) {
                                            $selected = "";
                                            if ($bsd->kode_gaji == $row->kode_gaji_potongan){
                                                $selected = 'selected="selected"';
                                            }
                                        echo '<option '.$selected.' value="'.$bsd->kode_gaji.'">'.$bsd->kode_gaji." - ".$bsd->keterangan; '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cjamsostek" class="btn btn-default text-muted">Cancel</a>  
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>