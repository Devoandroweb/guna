<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Periode</h4>
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
                				<td class="label">Segmen</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->segmen;?></td>
                			</tr>
                			<tr>
                				<td class="label">Mulai</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->mulai);?></td>
                			</tr>
                			<tr>
                				<td class="label">Selesai</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->selesai);?></td>
                			</tr>
                			<tr>
                				<td class="label">THR</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->thr;?></td>
                			</tr>
                            <tr>
                                <td class="label">Status</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->status;?></td>
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
                		<a href="<?= site_url() ?>cperiode" class="btn btn-primary">Kembali</a>
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