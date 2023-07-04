<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h1 style="text-align: center">Data Pinjaman</h1>
				<?php if ($this->session->userdata('session_hak_akses') == 'karyawan' && $this->session->userdata('session_karyawan_status') == 'rekap tetap' || $this->session->userdata('session_karyawan_status') == 'lapangan tetap' || $this->session->userdata('session_karyawan_status') == 'kasir') : ?>
					<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-blue box-shadow-2" data-toggle="modal" data-target="#tambah">
						<i class="ft-plus-circle"></i> Tambah Pinjaman
					</button>
				<?php endif ?>
			</div>

			<div class="card-body">
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal Pinjaman</th>
							<th>Nama Karyawan</th>
							<th>Jumlah Pinjaman</th>
							<th>Status Pinjaman</th>
							<th>Deskripsi Pinjaman</th>
							<?php if ($this->session->userdata('session_hak_akses') != 'owner' && $this->session->userdata('session_hak_akses') != 'koordinator') : ?>
								<td style="text-align: center"><i class="ft-settings spinner"></i></td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>

						<?php
						$no = 1;
						foreach ($pinjaman as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= mediumdate_indo($value['pinjaman_date_created']) ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= nominal($value['pinjaman_jumlah']) ?></td>
								<td><?= $value['pinjaman_status'] ?></td>
								<td><?= $value['pinjaman_deskripsi'] ?></td>
								<?php if ($this->session->userdata('session_hak_akses') == 'pimpinan' && $value['pinjaman_status'] == 'proses') : ?>
									<td>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2 pinjaman-validasi" data-toggle="modal" data-target="#validasi" data-id="<?= $value['pinjaman_id'] ?>"><i class="ft-edit"></i></button>
									</td>
								<?php elseif ($this->session->userdata('session_hak_akses') == 'karyawan' && $value['pinjaman_status'] == 'proses') : ?>
									<td>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2 pinjaman-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['pinjaman_id'] ?>"><i class="ft-edit"></i></button>
									</td>
								<?php elseif ($this->session->userdata('session_hak_akses') != 'owner' && $this->session->userdata('session_hak_akses') != 'koordinator') : ?>
									<td>
										<button class="btn btn-success btn-sm" disabled><i class="ft-edit"></i></button>
									</td>
								<?php endif; ?>
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

<!-- Modal tambah -->
<div class="modal fade text-left" id="tambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Tambah Data Pinjaman</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('pinjaman/store') ?>
			<div class="modal-body">
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Nama Karyawan</label>
					<select name="karyawan" id="add_karyawan" class="form-control select2-tambah" required>
						<option value=""></option>
						<?php foreach ($karyawan as $value) : ?>
							<option value="<?= $value['karyawan_id'] ?>" selected><?= $value['karyawan_nama'] ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Jumlah Pinjaman</label>
					<input type="number" class="form-control" name="jumlah" id="add_jumlah" placeholder="Masukkan jumlah pinjaman" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Deskripsi Pinjaman</label>
					<textarea name="deskripsi" id="add_deskripsi" cols="1" rows="3" class="form-control" placeholder="Masukkan deskripsi pinjaman" autocomplete="off" required></textarea>
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

<!-- Modal ubah -->
<div class="modal fade text-left" id="ubah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Pinjaman</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('pinjaman/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Nama Karyawan</label>
					<select name="karyawan" id="edit_karyawan" class="form-control select2-ubah" required>
						<option value=""></option>
						<?php foreach ($karyawan as $value) : ?>
							<option value="<?= $value['karyawan_id'] ?>" selected><?= $value['karyawan_nama'] ?></option>
						<?php endforeach; ?>
					</select>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Jumlah Pinjaman</label>
					<input type="number" class="form-control" name="jumlah" id="edit_jumlah" placeholder="Masukkan jumlah pinjaman" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Deskripsi Pinjaman</label>
					<textarea name="deskripsi" id="edit_deskripsi" cols="1" rows="3" class="form-control" placeholder="Masukkan deskripsi pinjaman" autocomplete="off" required></textarea>
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

<div class="modal fade text-left" id="validasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Validasi Data Pinjaman</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('pinjaman/validasi') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id_validasi">
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Nama Karyawan</label>
					<input type="text" class="form-control" id="pinjaman_validasi_nama" readonly>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Status</label>
					<select name="validasi_status_peminjaman" id="validasi_status_peminjaman" class="form-control select2-validasi" required>
						<option value=""></option>
						<option value="terhutang">Terhutang</option>
						<option value="dibatalkan">Dibatalkan</option>
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
