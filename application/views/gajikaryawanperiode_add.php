<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Gaji Karyawan Periode Information</h4>
                <hr> 
                <?php echo form_open('cgajikaryawanperiode/create');?>
                <div class="container">
                        
                    
                    <div class="row">
                        <div class="col">
                            <?php 
                                $q = $this->db->query("SELECT kode_gaji FROM master_gaji");
                                ?>
                                <input name="jum" value="<?php echo $q->num_rows();?>" type="hidden" class="form-control input-smm" />
                                <form class="form-horizontal">
                                    <div class="form-group option-nik">
                                        <label for="nik" class="col-sm-29 control-label">NIK</label>
                                        <!-- <input type="search" name="nik" required="required" class="autocomplete form-control input-smm" id="autocomplete" placeholder="NIK" /> -->
                                        <select class="form-control input-smm" name="nik">
                                            <option selected="" disabled>- Pilih NIK -</option>
                                            <?php foreach ($pegawai as $key) :?>
                                                <option><?= $key->nik ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="nama" class="col-sm-29 control-label">Nama</label>
                                        <input readonly type="text" name="nama" required="required" class="autocomplete form-control input-smm" id="v_nama" placeholder="Nama" />
                                    </div>
                                    <p>&nbsp;</p>
                                    <?php 
                                    $n = 0;
                                    foreach($querys->result() as $row):
                                    $n++;
                                    ?>
                                    <div class="rows wellsy">
                                        <div class="form-group">
                                            <label for="kode_gaji"><?php echo $row->keterangan;?></label>
                                            <input maxlength="25" name="nilai_gaji<?php echo $n;?>" required="required" type="text" class="form-control input-smm" id="nilai_gaji" placeholder="<?php echo $row->keterangan;?>" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                            <input name="kode_gaji<?php echo $n;?>" value="<?php echo $row->kode_gaji;?>" type="hidden" class="form-control input-smm" id="kode_gaji" placeholder="Kode Gaji" />
                                        </div>
                                    </div>

                                </form>
                                <?php endforeach;?>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgajikaryawanperiode" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

<?php include "footer.php" ?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('change', 'select[name="nik"]', function(event) {
            var value = $(this).val();
            $.ajax({
                url: '<?= base_url() ?>/Cgajikaryawan/search/'+value,
                type: 'POST',
                dataType: 'JSON',
            })
            .done(function(data) {
                // console.log(data['suggestions'][0].nama);
                $("input[name='nama']").val(data['suggestions'][0].nama);
            })
            
            
        });   
    });
</script>
</body>
</html>
