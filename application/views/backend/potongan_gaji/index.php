<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h1 style="text-align: center">Data Potongan Gaji</h1>
				<?php if ($this->session->userdata('session_hak_akses') == 'koordinator') : ?>
					<button type="button" class="btn btn-primary btn-bg-gradient-x-purple-blue box-shadow-2" data-toggle="modal" data-target="#tambah">
						<i class="ft-plus-circle"></i> Tambah Potongan
					</button>
				<?php endif ?>
			</div>

			<div class="card-body">
				<table class="table table-bordered w-100" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Potongan</th>
							<th>Jumlah Potongan</th>
							<th>Keterangan Potongan</th>
							<?php if ($this->session->userdata('session_hak_akses') == 'koordinator') : ?>
								<td style="text-align: center"><i class="ft-settings spinner"></i></td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($potongan as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['potongan_nama'] ?></td>
								<td><?= nominal($value['potongan_jumlah']) ?></td>
								<td><?= $value['potongan_keterangan'] ?></td>
								<?php if ($this->session->userdata('session_hak_akses') == 'koordinator') : ?>
									<td>
										<button class="btn btn-success btn-sm  btn-bg-gradient-x-blue-green box-shadow-2 potongan-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['potongan_id'] ?>"><i class="ft-edit"></i></button>
										<button class="btn btn-danger btn-sm  btn-bg-gradient-x-red-pink box-shadow-2 potongan-delete" data-id="<?= $value['potongan_id'] ?>"><i class="ft-trash"></i></button>
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
				<h3 class="modal-title" id="myModalLabel35"> Tambah Data Potongan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('potongan_gaji/store') ?>
			<div class="modal-body">
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Potongan</label>
					<input type="text" class="form-control" name="potongan" id="potongan" placeholder="Masukkan nama potongan" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Jumlah</label>
					<input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Masukkan jumlah potongan" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Keterangan</label>
					<textarea name="keterangan" id="keterangan" cols="1" rows="3" class="form-control" placeholder="Masukkan keterangan potongan" autocomplete="off" required></textarea>
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
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Potongan</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('potongan_gaji/update') ?>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<fieldset class="form-group floating-label-form-group">
					<label for="potongan">Potongan</label>
					<input type="text" class="form-control" name="potongan" id="edit_potongan" placeholder="Masukkan nama potongan" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Jumlah</label>
					<input type="number" class="form-control" name="jumlah" id="edit_jumlah" placeholder="Masukkan jumlah potongan" autocomplete="off" required>
				</fieldset>
				<fieldset class="form-group floating-label-form-group">
					<label for="jumlah">Keterangan</label>
					<textarea name="keterangan" id="edit_keterangan" cols="1" rows="3" class="form-control" placeholder="Masukkan keterangan potongan" autocomplete="off" required></textarea>
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
