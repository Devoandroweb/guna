<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Absen Sakit atau Cuti</h4>
                <hr>

                <?php echo form_open('cabsensakitcuti/create');?>
                <div class="container">
                        
                    
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">

                            <div class="rows col-md-6 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="search" name="nik" required="required" class="autocomplete form-control input-smm" id="autocomplete" placeholder="NIK" />
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input readonly type="text" name="nama" required="required" class="autocomplete form-control input-smm" id="v_nama" placeholder="Nama" />
                                </div>
                                <!-- <div class="form-group">
                                    <label for="tanggal">Dari Tanggal</label>
                                    <input id="tgla" name="dari_tanggal" required="required" type="text" class="form-control input-smm" placeholder="Dari Tanggal" />
                                </div> -->
                                <div class="form-group">
                                    <label for="tanggal_lahir">Dari Tanggal</label>
                                    <div id="" class="input-group date datepicker datepicker-popup">
                                        <input type="text" class="form-control" name="dari_tanggal"  placeholder="Dari Tanggal" id="tgla">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>
                           <!--      <div class="form-group">
                                    <label for="tanggal">Sampai Tanggal</label>
                                    <input id="tgls" name="sampai_tanggal" required="required" type="text" class="form-control input-smm" placeholder="Sampai Tanggal" />
                                </div> -->
                                <div class="form-group">
                                    <label for="tanggal_lahir">Sampai Tanggal</label>
                                    <div id="" class="input-group date datepicker datepicker-popup">
                                        <input type="text" class="form-control" name="sampai_tanggal"  placeholder="Sampai Tanggal" id="tgls">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="rows col-md-6 wellsy btn-sm">
                                <div class="form-group">
                                    <?php
                                            $ssa = $this->db->query("SELECT status_aktual,id_status_aktual FROM content_status_aktual");
                                            ?>
                                    <label for="status_aktual">Status</label>
                                    <select required="required" id="status_aktual" name="id_status_aktual" class="form-control input-smm">
                                        <option></option>
                                        <?php foreach($ssa->result() as $ro):?>
                                        <option value="<?php echo $ro->id_status_aktual?>"><?php echo $ro->status_aktual?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group pt-2">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cabsensakitcuti" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <script type="text/javascript">
             $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $(".datepicker-popup").datepicker();
        </script>
<?php include "footer.php" ?>
</body>
</html>