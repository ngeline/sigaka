<div class="row">
	<div class="col-md-12">
		<div class="card box-shadow-2">

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
				<h1 style="text-align: center">Data Karyawan</h1>
				<?php if ($this->session->userdata('session_hak_akses') == 'manajer') : ?>
					<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-blue box-shadow-2" data-toggle="modal" data-target="#bootstrap">
						<i class="ft-plus-circle"></i> Tambah data karyawan
					</button>
				<?php endif; ?>
			</div>
			<div class="card-body">
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Username Pengguna</th>
							<th>Nama Karyawan</th>
							<!-- <th>No Telepon</th> -->
							<th>Tanggal Masuk</th>
							<th>Jabatan Karyawan</th>
							<td style="text-align: center"><i class="ft-settings spinner"></i></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($karyawan as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['pengguna_username'] ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<!-- <td><?= $value['karyawan_nomor_hp'] ?></td> -->
								<td><?= date_indo($value['karyawan_tanggal_gabung']) ?></td>
								<td><?= $value['karyawan_status'] ?></td>
								<td>
									<button class="btn btn-success btn-sm  btn-bg-gradient-x-purple-blue box-shadow-2 karyawan-show" data-toggle="modal" data-target="#lihat" data-id="<?= $value['karyawan_id'] ?>" title="Lihat selengkapnya"><i class="ft-eye"></i></button>
									<?php if ($this->session->userdata('session_hak_akses') == 'manajer') : ?>
										<button class="btn btn-success btn-sm  btn-bg-gradient-x-blue-green box-shadow-2 karyawan-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['karyawan_id'] ?>" title="Update data karyawan"><i class="ft-edit"></i></button>
										<button class="btn btn-danger btn-sm  btn-bg-gradient-x-red-pink box-shadow-2 karyawan-delete" data-id-karyawan="<?= $value['karyawan_id'] ?>" data-id-pengguna="<?= $value['pengguna_id'] ?>" title="Hapus data karyawan"><i class="ft-trash"></i></button>
									<?php endif; ?>
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

<!-- Modal tambah -->
<div class="modal fade text-left" id="bootstrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Tambah Data Karyawan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('karyawan/store') ?>
			<div class="modal-body">
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_tg">Tanggal Bergabung <span class="text-danger fs-12">*</span></label>
					<div class='input-group'>
						<input type="date" class="form-control" id="tg" name="tanggal_gabung" placeholder="" autocomplete="off" required>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan Karyawan <span class="text-danger fs-12">*</span></label>
					<select name="status" id="status" class="select2 form-control" style="width: 100%">
						<option value="rekap tetap" selected>Rekap Tetap</option>
						<option value="rekap training">Rekap training</option>
						<option value="lapangan tetap">Lapangan tetap</option>
						<option value="lapangan training">Lapangan training</option>
					</select>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="nama">Nama <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan nama pengguna" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="tempat">Tempat Lahir <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" name="tempat_lahir" id="tempat" placeholder="Masukkan tempat lahir" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="tl">Tanggal Lahir <span class="text-danger fs-12">*</span></label>
					<div class='input-group'>
						<input type="date" class="form-control" name="tanggal_lahir" id="tl" placeholder="" autocomplete="off" required>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="nohp">Nomor HP <span class="text-danger fs-12">*</span></label>
					<input type="number" class="form-control" id="nohp" name="nomor_hp" placeholder="Masukkan nomor hp" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="alamat">Alamat <span class="text-danger fs-12">*</span></label>
					<textarea class="form-control" id="alamat" rows="3" name="alamat" placeholder="Masukkan alamat karyawan" autocomplete="off" required></textarea>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="nohp">Username Akun Pengguna <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username pengguna" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="nohp">Password Akun Pengguna <span class="text-danger fs-12">*</span></label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password pengguna" autocomplete="off" required>
				</fieldset>
				<!-- <fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan</label>
					<select name="jabatan" id="jabatan" class="select2 form-control" style="width: 100%">
						<?php
						foreach ($jabatan as $key => $value) :
						?>
							<option value="<?= $value['jabatan_id'] ?>"><?= $value['jabatan_nama'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</fieldset> -->
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
				<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Simpan">
			</div>
			<?= form_close() ?>
		</div>
	</div>
</div>

<!-- Modal lihat -->
<div class="modal fade text-left" id="lihat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Lihat Data Karyawan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<fieldset class="form-group floating-label-form-group">
					<label for="show_tg">Tanggal Bergabung</label>
					<div class='input-group'>
						<input type="date" class="form-control" id="show_tg" name="tanggal_gabung" placeholder="" autocomplete="off" readonly>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan Karyawan</label>
					<input type="text" class="form-control" name="status" id="show_status" placeholder="" autocomplete="off" readonly>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_nama">Nama</label>
					<input type="text" class="form-control" name="nama" id="show_nama" placeholder="Masukkan nama pengguna" autocomplete="off" readonly>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_tempat">Tempat Lahir</label>
					<input type="text" class="form-control" name="tempat_lahir" id="show_tempat" placeholder="Masukkan tempat lahir" autocomplete="off" readonly>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_tl">Tanggal Lahir</label>
					<div class='input-group'>
						<input type="date" class="form-control" name="tanggal_lahir" id="show_tl" placeholder="" autocomplete="off" readonly>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_nohp">Nomor HP</label>
					<input type="number" class="form-control" id="show_nohp" name="nomor_hp" placeholder="Masukkan nomor hp" autocomplete="off" readonly>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_alamat">Alamat</label>
					<textarea class="form-control" id="show_alamat" rows="3" name="alamat" placeholder="Masukkan alamat karyawan" autocomplete="off" readonly></textarea>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="show_nohp">Username Akun Pengguna</label>
					<input type="text" class="form-control" id="show_username" name="username" placeholder="Masukkan username pengguna" autocomplete="off" readonly>
				</fieldset>
				<!-- <fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan</label>
					<select name="jabatan" id="jabatan" class="select2 form-control" style="width: 100%">
						<?php
						foreach ($jabatan as $key => $value) :
						?>
							<option value="<?= $value['jabatan_id'] ?>"><?= $value['jabatan_nama'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</fieldset> -->
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
			</div>
		</div>
	</div>
</div>

<!-- Modal update -->
<div class="modal fade text-left" id="ubah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Update Data Karyawan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('karyawan/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id_karyawan" id="edit_id_karyawan">
				<input type="hidden" name="id_pengguna" id="edit_id_pengguna">
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_tg">Tanggal Bergabung <span class="text-danger fs-12">*</span></label>
					<div class='input-group'>
						<input type="date" class="form-control" id="edit_tg" name="tanggal_gabung" placeholder="" autocomplete="off" required>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan Karyawan <span class="text-danger fs-12">*</span></label>
					<select name="status" id="edit_status" class="select2 form-control" style="width: 100%">
						<option value="rekap tetap">Rekap Tetap</option>
						<option value="rekap training">Rekap training</option>
						<option value="lapangan tetap">Lapangan tetap</option>
						<option value="lapangan training">Lapangan training</option>
					</select>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_nama">Nama <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" name="nama" id="edit_nama" placeholder="Masukkan nama pengguna" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_tempat">Tempat Lahir <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" name="tempat_lahir" id="edit_tempat" placeholder="Masukkan tempat lahir" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_tl">Tanggal Lahir <span class="text-danger fs-12">*</span></label>
					<div class='input-group'>
						<input type="date" class="form-control" name="tanggal_lahir" id="edit_tl" placeholder="" autocomplete="off" required>
					</div>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_nohp">Nomor HP <span class="text-danger fs-12">*</span></label>
					<input type="number" class="form-control" id="edit_nohp" name="nomor_hp" placeholder="Masukkan nomor hp" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_alamat">Alamat <span class="text-danger fs-12">*</span></label>
					<textarea class="form-control" id="edit_alamat" rows="3" name="alamat" placeholder="Masukkan alamat karyawan" autocomplete="off" required></textarea>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_nohp">Username Akun Pengguna <span class="text-danger fs-12">*</span></label>
					<input type="text" class="form-control" id="edit_username" name="username" placeholder="Masukkan username pengguna" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="edit_nohp">Password Akun Pengguna <span class="text-danger" style="font-size: 9pt;">*) kosongkan password jika tidak update</span></label>
					<input type="password" class="form-control" id="edit_password" name="password" placeholder="Masukkan password pengguna" autocomplete="off">
				</fieldset>
				<!-- <fieldset class="form-group floating-label-form-group">
					<label for="jabatan">Jabatan</label>
					<select name="jabatan" id="jabatan" class="select2 form-control" style="width: 100%">
						<?php
						foreach ($jabatan as $key => $value) :
						?>
							<option value="<?= $value['jabatan_id'] ?>"><?= $value['jabatan_nama'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</fieldset> -->
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
				<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="update" value="Update">
			</div>
			<?= form_close() ?>
		</div>
	</div>
</div>

<!-- Modal hapus -->
<div class="modal fade text-left" id="hapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Hapus Data Karyawan ?</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-blue-cyan" data-dismiss="modal" value="Tutup">
				<div id="hapuskaryawan">

				</div>
			</div>
		</div>
	</div>
</div>
