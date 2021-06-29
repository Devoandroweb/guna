<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Jamsostek Komponen Dasar Information</h4>
                <hr>
                <?php echo form_open('cjamsostek/create');?>
                <div class="container">
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">

                            <div class="rows col-md-12 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="nama_program">Nama Program</label>
                                    <input name="nama_program" required="required" type="text" class="form-control input-smm" id="nama_program" placeholder="Nama Program" />
                                </div>
                                <div class="form-group">
                                    <label for="bagian_perusahaan">Bagian Perusahaan</label>
                                    <input name="bagian_perusahaan" required="required" type="text" class="form-control input-smm" id="bagian_perusahaan" placeholder="Bagian Perusahaan" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="bagian_karyawan">Bagian Karyawan</label>
                                    <input name="bagian_karyawan" required="required" type="text" class="form-control input-smm" id="bagian_karyawan" placeholder="Bagian Karyawan" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="maksimal_dasar">Maksimal Dasar</label>
                                    <input name="maksimal_dasar" required="required" type="text" class="form-control input-smm" id="maksimal_dasar" placeholder="Maksimal Dasar" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="kode_gaji_potongan">Kode Gaji Potongan</label>
                                    <select required="required" id="kode_gaji_potongan" name="kode_gaji_potongan" class="form-control input-smm">
                                        <option></option>
                                        <?php foreach($query4->result() as $row):?>
                                        <option><?php echo $row->kode_gaji."-".$row->keterangan; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cjamsostek" class="btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>