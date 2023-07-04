<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h1 style="text-align: center">Data Storting</h1>
				<?php if ($this->session->userdata('session_hak_akses') == 'karyawan') : ?>
					<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-blue box-shadow-2" data-toggle="modal" data-target="#tambah">
						<i class="ft-plus-circle"></i> Tambah Storting
					</button>
				<?php endif ?>
			</div>

			<div class="card-body">
				<div class="row">
					<div class="col-sm-3 mb-2">
						<label class="text-danger note-filter" style="font-size: 10pt;">*) Pilih untuk filter storting</label>
						<input type="month" class="form-control storting-month-picker" value="<?= $date_set ?>">
					</div>
					<div class="col-sm-9">
						<?php if ($this->session->userdata('session_hak_akses') == 'karyawan' && $this->session->userdata('session_karyawan_status') != 'kasir') : ?>
							<?= form_open('storting/kemacetan-store') ?>
							<input type="hidden" name="kemacetan_date" value="<?= $date_set ?>">
							<div class="row">
								<div class="col-md-5 border-left border-1 mb-2">
									<?php if (!empty($kemacetan) && $kemacetan->kemacetan_status == 'pending') : ?>
										<label><span class="badge badge-sm badge-danger"><?= $kemacetan->kemacetan_status ?></span> | Total Kemacetan Bulan Ini</label>
									<?php elseif (!empty($kemacetan) && $kemacetan->kemacetan_status == 'tervalidasi') : ?>
										<label><span class="badge badge-sm badge-success"><?= $kemacetan->kemacetan_status ?></span> | Total Kemacetan Bulan Ini</label>
									<?php else : ?>
										<label><span class="badge badge-sm badge-warning">belum Input</span> | Total Kemacetan Bulan Ini</label>
									<?php endif; ?>
									<input type="number" class="form-control" name="kemacetan" id="edit_total_kemacetan" placeholder="Masukkan jumlah kemacetan" value="<?= $kemacetan ? $kemacetan->kemacetan_jumlah : '' ?>" autocomplete="off" readonly required>
								</div>
								<?php if (empty($kemacetan) || $kemacetan->kemacetan_status != 'tervalidasi') : ?>
									<div class="col-md-4 mb-2">
										<div class="btn-group mt-1 pt-1">
											<button type="button" class="btn btn-success btn-md btn-bg-gradient-x-blue-green box-shadow-2 btn-kemacetan-edit" title="Edit kemacetan"><i class="ft-edit"></i></button>
											<button type="submit" class="btn btn-primary btn-md ml-1 btn-bg-gradient-x-purple-blue box-shadow-2 btn-kemacetan-save" title="Simpan perubahan" hidden><i class="ft-check"></i></button>
											<button type="button" class="btn btn-warning btn-md ml-1 btn-bg-gradient-x-purple-red box-shadow-2 btn-kemacetan-cancel" title="Cancel perubahan" hidden><i class="ft-trash"></i></button>
										</div>
									</div>
								<?php endif; ?>
							</div>
							<?= form_close() ?>
						<?php endif; ?>
					</div>
				</div>
				<table class="table table-bordered w-100" id="table_data">
					<?php if ($this->session->userdata('session_hak_akses') == 'karyawan') : ?>
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
										<?php if ($value['storting_status'] == 'pending') : ?>
											<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2 storting-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['storting_id'] ?>"><i class="ft-edit"></i></button>
											<button class="btn btn-danger btn-sm  btn-bg-gradient-x-red-pink box-shadow-2 storting-delete" data-id="<?= $value['storting_id'] ?>" title="Hapus data"><i class="ft-trash"></i></button>
										<?php else : ?>
											<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2" disabled><i class="ft-edit"></i></button>
											<button class="btn btn-danger btn-sm  btn-bg-gradient-x-red-pink box-shadow-2" disabled><i class="ft-trash"></i></button>
										<?php endif; ?>
									</td>
								</tr>
							<?php
								$no++;
							endforeach;
							?>
						</tbody>
					<?php else : ?>
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Karyawan</th>
								<th>Bulan</th>
								<th>Tahun</th>
								<th>Total Pinjaman</th>
								<th>Total Angsuran</th>
								<th>Total Angsuran Hutang</th>
								<th>Total Kemacetan</th>
								<?php if ($this->session->userdata('session_hak_akses') == 'pimpinan') : ?>
									<td style="text-align: center"><i class="ft-settings spinner"></i></td>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($storting as $key => $value) :
							?>
								<tr>
									<td><?= $no ?></td>
									<td><?= $value['nama'] ?></td>
									<td><?= $value['bulan'] ? bulan($value['bulan']) : bulan($month_set) ?></td>
									<td><?= $value['tahun'] ? $value['tahun'] : $year_set ?></td>
									<td><?= $value['total_pinjaman'] ? nominal($value['total_pinjaman']) : '-' ?></td>
									<td><?= $value['total_angsuran'] ? nominal($value['total_angsuran']) : '-' ?></td>
									<td><?= $value['total_angsuran_hutang'] ? nominal($value['total_angsuran_hutang']) : '-' ?></td>
									<td><?= $value['kemacetan_jumlah'] ? nominal($value['kemacetan_jumlah']) : '-' ?></td>
									<?php if ($this->session->userdata('session_hak_akses') == 'pimpinan') : ?>
										<td>
											<button type="button" class="btn btn-success btn-sm btn-bg-gradient-x-purple-blue box-shadow-2 storting-riwayat-show" data-id="<?= $value['karyawan_id'] ?>" title="Lihat riwayat"><i class="ft-eye"></i></button>
										</td>
									<?php endif; ?>
								</tr>
							<?php
								$no++;
							endforeach;
							?>
						</tbody>
					<?php endif; ?>
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
				<h3 class="modal-title" id="myModalLabel35"> Tambah Data Storting</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('storting/store') ?>
			<div class="modal-body">
				<fieldset class="form-group floating-label-form-group">
					<label for="tanggal">Tanggal Storting</label>
					<input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Masukkan tanggal storting" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="pinjaman">Storting Pinjaman</label>
					<input type="number" class="form-control" name="pinjaman" id="pinjaman" placeholder="Masukkan jumlah storting pinjaman" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="angsuran">Storting Angsuran</label>
					<input type="number" class="form-control" name="angsuran" id="angsuran" placeholder="Masukkan jumlah storting angsuran" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="angsuran_hutang">Storting Angsuran Hutang</label>
					<input type="number" class="form-control" name="angsuran_hutang" id="angsuran_hutang" placeholder="Masukkan jumlah storting angsuran hutang" autocomplete="off" required>
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
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Storting</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('storting/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<fieldset class="form-group floating-label-form-group">
					<label for="tanggal">Tanggal Storting</label>
					<input type="date" class="form-control" name="tanggal" id="edit_tanggal" placeholder="Masukkan tanggal storting" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="pinjaman">Storting Pinjaman</label>
					<input type="number" class="form-control" name="pinjaman" id="edit_pinjaman" placeholder="Masukkan jumlah storting pinjaman" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="angsuran">Storting Angsuran</label>
					<input type="number" class="form-control" name="angsuran" id="edit_angsuran" placeholder="Masukkan jumlah storting angsuran" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="angsuran_hutang">Storting Angsuran Hutang</label>
					<input type="number" class="form-control" name="angsuran_hutang" id="edit_angsuran_hutang" placeholder="Masukkan jumlah storting angsuran hutang" autocomplete="off" required>
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
