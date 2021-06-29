<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Absen Sakit / Cuti</h4>
                <hr>
                <div class="row">
                    <div class="col">

                        <table>
                            <?php foreach($query->result() as $row):?>
                            <tr>
                                <td class="label">Periode</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->periode;?></td>
                            </tr>
                            <tr>
                                <td class="label">NIK</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->nik;?></td>
                            </tr>
                            <tr>
                                <td class="label">Nama</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->nama;?></td>
                            </tr>
                            <tr>
                                <td class="label">Dari Tanggal</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->dari_tanggal);?></td>
                            </tr>
                            <tr>
                                <td class="label">Sampai Tanggal</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->sampai_tanggal);?></td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->status;?></td>
                            </tr>
                            <tr>
                                <td class="label">Keterangan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->keterangan;?></td>
                            </tr>
                            <tr>
                                <td class="label">Last Update</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->lastupdate);?></td>
                            </tr>
                            <tr>
                                <td class="label">User ID</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->user_id;?></td>
                            </tr>
                            <?php endforeach;?>

                        </table>

                    </div>
                </div>
                <hr>
                <div class="row mt-4">
                    <div class="col">
                        <a href="<?= site_url() ?>cabsensakitcuti" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("table tr .label").addClass("font-weight-bold pr-4");
                $("table tr .value").addClass("pl-2");
            })
        </script>
<?php include "footer.php" ?>
</body>
</html>