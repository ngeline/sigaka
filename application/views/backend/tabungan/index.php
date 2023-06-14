<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h1 style="text-align: center">Data Tabungan</h1>
			</div>

			<div class="card-body">
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Karyawan</th>
							<th>Saldo Tabungan</th>
							<th>Terakhir Update</th>
							<th style="text-align: center"><i class="ft-settings spinner"></i></th>
						</tr>
					</thead>
					<tbody>

						<?php
						$no = 1;
						foreach ($tabungan as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= $value['tabungan_jumlah'] ? nominal($value['tabungan_jumlah']) : "-" ?></td>
								<td><?= $value['tabungan_date_updated'] ? mediumdate_indo($value['tabungan_date_updated']) : "-" ?></td>
								<td>
									<button class="btn btn-success btn-sm  btn-bg-gradient-x-purple-blue box-shadow-2 riwayat-tabungan-show" data-toggle="modal" data-target="#lihat" data-id="<?= $value['tabungan_id'] ?>" title="Lihat riwayat"><i class="ft-eye"></i></button>
								</td>
							</tr>
						<?php
							$no++;
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal lihat -->
<div class="modal fade text-left" id="lihat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Lihat Riwayat Tabungan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered w-100" id="table_riwayat_tabungan">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Status</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
			</div>
		</div>
	</div>
</div>
