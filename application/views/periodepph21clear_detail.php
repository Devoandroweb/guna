<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail PPH-21 Clear</h4>
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
                                <td class="label">Periode Penggajian</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->periode_penggajian;?></td>
                            </tr>
                            <tr>
                                <td class="label">Segment</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->segmen;?></td>
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
                                <td class="label">Base</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->base);?></td>
                            </tr>
                            <tr>
                                <td class="label">Overtime</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->over_time);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Kendaraan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_kendaraan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Transport</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_kendaraan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Makan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_makan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Anak</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_anak);?></td>
                            </tr>
                            <tr>
                                <td class="label">Sales Insentive</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->sales_insentive);?></td>
                            </tr>
                            <tr>
                                <td class="label">Adjustment Plus</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->adjustment_plus);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan PPH21</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_pph21);?></td>
                            </tr>
                            <tr>
                                <td class="label">BPJS Ketenagakerjaan Karyawan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->bpjs_ketenagakerjaan_karyawan);?></td>
                            </tr>
                            <?php endforeach;?>

                        </table>

                    </div>
                    <div class="col">

                        <table>
                            <?php foreach($query->result() as $row):?>
                            <tr>
                                <td class="label">BPJS Kesehatan Karyawan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->bpjs_kesehatan_karyawan);?></td>
                            </tr>
                            <tr>
                                <td class="label">JPN Karyawan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jpn_karyawan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Potongan Koperasi</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->potongan_koperasi);?></td>
                            </tr>
                            <tr>
                                <td class="label">Potongan Mangkir</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->potongan_mangkir);?></td>
                            </tr>
                            <tr>
                                <td class="label">Adjusment Minus</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->adjustment_minus);?></td>
                            </tr>
                            <tr>
                                <td class="label">Potongan PPH21</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->potongan_pph21);?></td>
                            </tr>
                            <tr>
                                <td class="label">THP</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->thp);?></td>
                            </tr>
                            <tr>
                                <td class="label">Metode PPH21</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->metode_pph21;?></td>
                            </tr>
                            <tr>
                                <td class="label">BPJS Kesehatan Perusahaan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->bpjs_kesehatan_perusahaan);?></td>
                            </tr>
                            <tr>
                                <td class="label">JPN Perusahaan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jpn_perusahaan);?></td>
                            </tr>
                            <tr>
                                <td class="label">BPJS Ketenagakerjaan Perusahaan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->bpjs_ketenagakerjaan_perusahaan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Potongan BPJS</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->potongan_bpjs);?></td>
                            </tr>
                            <?php endforeach;?>

                        </table>

                    </div>
                </div>
                <hr>
                <div class="row mt-4">
                    <div class="col">
                        <a href="<?= site_url() ?>cperiodepph21clear" class="btn btn-primary">Kembali</a>
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