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
				<h1 style="text-align: center">Data Absensi</h1>
			</div>

			<div class="card-body">
				<div class="text-danger note-filter" style="font-size: 10pt;">
					*) Pilih untuk filter absensi
				</div>
				<div class="row">
					<div class="col-sm-3 mb-2">
						<input type="month" class="form-control absensi-month-picker" value="<?= $date_set ?>">
					</div>
				</div>
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Karyawan</th>
							<th>Bulan</th>
							<th>Tahun</th>
							<th>Kehadiran</th>
							<th>Ketidakhadiran</th>
							<?php if ($this->session->userdata('session_hak_akses') == 'manajer') : ?>
								<td style="text-align: center"><i class="ft-settings spinner"></i></td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($absensi as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= $value['absensi_bulan_ke'] ? bulan($value['absensi_bulan_ke']) : bulan($month_set) ?></td>
								<td><?= $value['absensi_tahun_ke'] ? $value['absensi_tahun_ke'] : $year_set ?></td>
								<td><?= $value['absensi_kehadiran'] ? $value['absensi_kehadiran'] : '-' ?></td>
								<td><?= $value['absensi_ketidakhadiran'] ? $value['absensi_ketidakhadiran'] : '-' ?></td>
								<?php if ($this->session->userdata('session_hak_akses') == 'manajer') : ?>
									<td>
										<?php if ($date_set == date('Y-m')) : ?>
											<button class="btn btn-success btn-sm  btn-bg-gradient-x-blue-green box-shadow-2 absensi-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['karyawan_id'] ?>"><i class="ft-edit"></i></button>
										<?php else : ?>
											<button class="btn btn-success btn-sm  btn-bg-gradient-x-blue-green box-shadow-2" disabled><i class="ft-edit"></i></button>
										<?php endif; ?>
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

<!-- Modal ubah -->
<div class="modal fade text-left" id="ubah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Absensi</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('absensi/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<input type="hidden" name="karyawan" id="karyawan">
				<input type="hidden" name="tahun" id="tahun">
				<input type="hidden" name="bulan" id="bulan">
				<fieldset class="form-group floating-label-form-group">
					<label for="hadir">Kehadiran</label>
					<input type="number" class="form-control" name="kehadiran" id="edit_kehadiran" placeholder="Masukkan jumlah kehadiran" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="sakit">Ketidakhadiran</label>
					<input type="number" class="form-control" name="ketidakhadiran" id="edit_ketidakhadiran" placeholder="Masukkan jumlah ketidakhadiran" autocomplete="off" required>
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
