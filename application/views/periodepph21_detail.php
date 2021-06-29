<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail PPH-21 Komponen</h4>
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
                				<td class="label">Status Pernikahan</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->status_perkawinan;?></td>
                			</tr>
                			<tr>
                				<td class="label">NPWP</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->npwp;?></td>
                			</tr>
                			<tr>
                				<td class="label">Departemen</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->departemen;?></td>
                			</tr>
                            <tr>
                                <td class="label">Jabatan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->jabatan;?></td>
                            </tr>
                            <tr>
                                <td class="label">Over Time</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->over_time);?></td>
                            </tr>
                            <tr>
                                <td class="label">Base</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->base);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Transport</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tunjangan_transport);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tunjangan Kendaraan</td>
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
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->sales_incentive);?></td>
                            </tr>
                            <tr>
                                <td class="label">Bonus</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->bonus);?></td>
                            </tr>
                            <tr>
                                <td class="label">Adjustment Plus</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->adjustment_plus);?></td>
                            </tr>
                            <tr>
                                <td class="label">JKM Perusahaan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jkm_perusahaan);?></td>
                            </tr>
                            <tr>
                                <td class="label">JKK Perusahaan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jkk_perusahaan);?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">JHT Perusahaan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->jht_perusahaan);?></td>
                			</tr>
                			<tr>
                				<td class="label">JKN Perusahaan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->jkn_perusahaan);?></td>
                			</tr>
                			<tr>
                				<td class="label">JPN Perusahaan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->jpn_perusahaan);?></td>
                			</tr>
                			<tr>
                				<td class="label">Gross</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->penghasilan_kotor);?></td>
                			</tr>
                            <tr>
                                <td class="label">JHT Karyawan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jht_karyawan);?></td>
                            </tr>
                            <tr>
                                <td class="label">JPN Karyawan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->jpn_karyawan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Biaya Jabatan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->biaya_jabatan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Total Pengurang</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->total_pengurang);?></td>
                            </tr>
                            <tr>
                                <td class="label">Netto</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->netto);?></td>
                            </tr>
                            <tr>
                                <td class="label">Netto Setahun</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->netto_setahun);?></td>
                            </tr>
                            <tr>
                                <td class="label">PTKP Gaji</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->ptkp_gaji);?></td>
                            </tr>
                            <tr>
                                <td class="label">Penghasilan Kena Pajak</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->penghasilan_kena_pajak);?></td>
                            </tr>
                            <tr>
                                <td class="label">PPH21 Gaji Setahun</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->pph21_gaji_setahun);?></td>
                            </tr>
                            <tr>
                                <td class="label">PPH21 Gaji Sebulan</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->pph21_gaji_sebulan);?></td>
                            </tr>
                            <tr>
                                <td class="label">Tambahan Non NPWP</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->tambahan_non_npwp);?></td>
                            </tr>
                            <tr>
                                <td class="label">Metode PPH21</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->metode_pph21;?></td>
                            </tr>
                            <tr>
                                <td class="label">PPH21 Nett</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->pph21_nett);?></td>
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
                		<a href="<?= site_url() ?>cperiodepph21" class="btn btn-primary">Kembali</a>
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