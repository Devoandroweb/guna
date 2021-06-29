<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Gaji Karyawan Periode Information</h4>
                <hr>
                <?php echo form_open('cgajikaryawanperiode/update');?>
                <div class="container">
                       
                    <div class="row">
                        <div class="col">
                            <table>
                                
                            
                            <?php 
                                $q = $this->db->query("SELECT a.nik,b.nama FROM master_gaji_karyawan_periode a join master_karyawan b on a.nik=b.nik WHERE a.nik='$nik'");
                                $nik = $q->row('nik');
                                ?>
                                <tr>
                                    <td class="label">NIK</td>
                                    <td>:</td>
                                    <td class="value"><?php echo $q->row('nik');?></td>
                                </tr>
                                <tr>
                                    <td class="label">Nama</td>
                                    <td>:</td>
                                    <td class="value"><?php echo $q->row('nama');?></td>
                                </tr>

                                <tr>
                                    <?php $periode = $this->session->userdata('periode');?>
                                    <td class="label">Periode</td>
                                    <td>:</td>
                                    <td class="value"><?php echo $periode;?></td>
                                </tr>
                                        
                                    <?php 
                                    $n = 0;
                                    foreach($query->result() as $row):
                                    $n++;
                                    ?>
                                    
                                        <?php 
                                        $qs = $this->db->query("SELECT * FROM master_gaji where kode_gaji='$row->kode_gaji'")->row();
                                        ?>
                                        <tr>
                                            <td class="label"><?php echo $qs->keterangan;?></td>
                                            <td>:</td>
                                            <td class="value"><?php echo $this->fungsi->FormatNum($row->nilai_gaji);?></td>
                                        </tr>

                                    <?php endforeach;?>

                                    <?php 
                                    $n = 0;
                                    $qs2 = $this->db->query("SELECT * FROM master_gaji WHERE kode_gaji NOT IN 
                                        (SELECT kode_gaji FROM master_gaji_karyawan_periode WHERE kode_gaji IS NOT NULL and nik='$nik')");
                                    $total = $qs2->num_rows();
                                    foreach($qs2->result() as $row):
                                    $n++;    
                                    ?>
                                    <tr>
                                        <td class="label"><?php echo $row->keterangan;?></td>
                                        <td>:</td>
                                        <td class="value"><?php echo $row->kode_gaji;?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </table>
                        </div>
                    </div>
                    <hr>
                     <div class="row">
                            <div class="col">
                                <a href="<?=base_URL()?>cgajikaryawanperiode" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

<?php include "footer.php" ?>
</body>
</html>