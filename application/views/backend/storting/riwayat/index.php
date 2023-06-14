<div class="row">
	<div class="col-md-12">
		<div class="card">
			<?php
			if ($this->session->flashdata('alert') == 'insert') :
			?>
				<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Data berhasil ditambahkan
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'update') :
			?>
				<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Data berhasil diupdate
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'delete') :
			?>
				<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Data berhasil dihapus
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'error') :
			?>
				<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?= $this->session->flashdata('message'); ?>
				</div>
			<?php
			endif;
			$this->session->set_flashdata('alert', '');
			$this->session->set_flashdata('message', '');
			?>

			<div class="card-header">
				<h1 style="text-align: center">Data Riwayat Storting</h1>
				<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-blue box-shadow-2 storting-riwayat-kembali">
					<i class="ft-arrow-left"></i> Kembali
				</button>
			</div>

			<div class="card-body">
				<div class="data-storting-riwayat" data-id="<?= $id_karyawan ?>"></div>
				<div class="row">
					<div class="col-sm-3 mb-2">
						<label class="text-danger note-filter" style="font-size: 10pt;">*) Pilih untuk filter storting riwayat</label>
						<input type="month" class="form-control storting-riwayat-month-picker" value="<?= $date_set ?>">
					</div>
					<div class="col-md-4 border-left border-1 mb-2">
						<label>Total Kemacetan Bulan Ini</label>
						<input type="number" class="form-control" name="kemacetan" id="edit_total_kemacetan" placeholder="Masukkan jumlah kemacetan" value="<?= $kemacetan ? $kemacetan->kemacetan_jumlah : '' ?>" autocomplete="off" readonly required>
					</div>
					<div class="col-md-4 text-right mb-2">
						<label>Klik untuk validasi semua status bulan ini</label>
						<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-red box-shadow-2 storting-riwayat-validasi-semua">
							<i class="ft-check"></i> Validasi Semua
						</button>
					</div>
				</div>
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Karyawan</th>
							<th>Tanggal Storting</th>
							<th>Pinjaman</th>
							<th>Angsuran</th>
							<th>Angsuran Hutang</th>
							<th>Validasi Admin</th>
							<td style="text-align: center"><i class="ft-settings spinner"></i></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($storting as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= mediumdate_indo($value['storting_tanggal']) ?></td>
								<td><?= $value['storting_pinjaman'] ? nominal($value['storting_pinjaman']) : '-' ?></td>
								<td><?= $value['storting_angsuran'] ? nominal($value['storting_angsuran']) : '-' ?></td>
								<td><?= $value['storting_angsuran_hutang'] ? nominal($value['storting_angsuran_hutang']) : '-' ?></td>
								<td><?= $value['storting_status'] ?></td>
								<td>
									<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2 storting-riwayat-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['storting_id'] ?>"><i class="ft-edit"></i></button>
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

<!-- Modal ubah -->
<div class="modal fade text-left" id="ubah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Riwayat Storting</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('storting/riwayat/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<input type="hidden" name="return_date" id="return_date_riwayat_storting">
				<input type="hidden" name="return_id_karyawan" id="return_id_karyawan_riwayat_storting">
				<fieldset class="form-group floating-label-form-group">
					<label for="tanggal">Status Storting</label>
					<select name="status" id="edit-storting-riwayat-status" class="form-control select2-ubah" required>
						<option value="pending">Pending</option>
						<option value="terverifikasi">Terverifikasi</option>
					</select>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
				<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Simpan">
			</div>
			<?= form_close() ?>
		</div>
	</div>
</div>
