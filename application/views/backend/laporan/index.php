<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h1 style="text-align: center">Data Laporan</h1>
			</div>
			<div class="card-body">
				<?php if ($this->session->userdata('session_hak_akses') != 'karyawan') : ?>
					<div class="row">
						<div class="col-sm-3 mb-2">
							<label class="text-danger" style="font-size: 10pt;">*) Pilih untuk filter laporan</label>
							<input type="month" class="form-control gaji-month-picker" value="<?= $date_set ?>">
						</div>
						<div class="col-sm-2 mb-2">
							<label for="laporan-btn-lihat" style="color: white">&nbsp;</label>
							<button class="btn btn-primary btn-block btn-bg-gradient-x-purple-blue" id="cetak_laporan">
								<i class="fa fa-print"></i> Cetak Laporan
							</button>
						</div>
					</div>
				<?php endif; ?>
				<table class="table table-bordered w-100 text-nowrap" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>Bulan</th>
							<th>Status Gaji</th>
							<th>Gaji Pokok</th>
							<th>Bonus</th>
							<th>Uang Makan</th>
							<th>Uang Transport</th>
							<th>Pinjaman</th>
							<th>Tabungan</th>
							<th>Potongan Kemacetan</th>
							<th>Potongan Tidak Masuk</th>
							<th>Potongan Lainnya</th>
							<th>Total Gaji</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($laporan as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= $value['karyawan_status'] ?></td>
								<td><?= $value['gaji_bulan_ke'] . " " . $value['gaji_tahun_ke'] ?></td>
								<td><?= $value['gaji_status'] ?></td>
								<td><?= nominal($value['gaji_pokok']) ?></td>
								<td><?= $value['gaji_bonus'] ? nominal($value['gaji_bonus']) : nominal(0) ?></td>
								<td><?= $value['gaji_uang_makan'] ? nominal($value['gaji_uang_makan']) : nominal(0) ?></td>
								<td><?= $value['gaji_transport'] ? nominal($value['gaji_transport']) : nominal(0) ?></td>
								<td><?= $value['gaji_pinjaman_bayar'] ? nominal($value['gaji_pinjaman_bayar']) : nominal(0) ?></td>
								<td><?= ($value['gaji_tabungan_masuk'] ? '(+) ' . nominal($value['gaji_tabungan_masuk']) : $value['gaji_tabungan_keluar']) ? '(-) ' . nominal($value['gaji_tabungan_keluar']) : nominal(0) ?></td>
								<td><?= $value['gaji_potongan_kemacetan'] ? nominal($value['gaji_potongan_kemacetan']) : nominal(0) ?></td>
								<td><?= $value['gaji_potongan_tidak_masuk'] ? nominal($value['gaji_potongan_tidak_masuk']) : nominal(0) ?></td>
								<td><?= $value['gaji_potongan_total'] ? nominal($value['gaji_potongan_total']) : nominal(0) ?></td>
								<td><?= nominal($value['gaji_total']) ?></td>
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
