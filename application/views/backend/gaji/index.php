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
				<h1 style="text-align: center">Data Gaji</h1>
			</div>

			<div class="card-body d-print-none">
				<div class="row">
					<div class="col-sm-3 mb-2">
						<label class="text-danger" style="font-size: 10pt;">*) Pilih untuk filter gaji</label>
						<input type="month" class="form-control gaji-month-picker" value="<?= $date_set ?>">
					</div>
				</div>
				<table class="table table-bordered w-100 d-print-none" id="table_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Karyawan</th>
							<th>Jabatan Karyawan</th>
							<th>Bulan</th>
							<th>Tahun</th>
							<th>Total Gaji</th>
							<th>Status Gaji</th>
							<td style="text-align: center"><i class="ft-settings spinner"></i></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($gaji as $key => $value) :
						?>
							<tr>
								<td><?= $no ?></td>
								<td><?= $value['karyawan_nama'] ?></td>
								<td><?= $value['karyawan_status'] ?></td>
								<td><?= $value['gaji_bulan_ke'] ? bulan($value['gaji_bulan_ke']) : bulan($month_set) ?></td>
								<td><?= $value['gaji_tahun_ke'] ? $value['gaji_tahun_ke'] : $year_set ?></td>
								<td><?= $value['gaji_total'] ? nominal($value['gaji_total']) : '-' ?></td>
								<td><?= $value['gaji_status'] ? $value['gaji_status'] : '-' ?></td>
								<td>
									<?php if ($value['gaji_status'] == 'terbayar') : ?>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-purple-blue box-shadow-2 gaji-slip" data-toggle="modal" data-target="#slip" data-id="<?= $value['karyawan_id'] ?>" title="Cetak slip"><i class="ft-printer"></i></button>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-purple-blue box-shadow-2 gaji-show" data-toggle="modal" data-target="#detail" data-id="<?= $value['karyawan_id'] ?>" title="Detail gaji"><i class="ft-eye"></i></button>
									<?php elseif (empty($value['gaji_total'])) : ?>
										<button class="btn btn-danger btn-sm  btn-bg-gradient-x-red-pink box-shadow-2 gaji-hitung" data-id="<?= $value['karyawan_id'] ?>" data-status="<?= $value['karyawan_status'] ?>" title="Hitung gaji"><i class="ft-briefcase"></i></button>
									<?php else : ?>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-blue-green box-shadow-2 gaji-edit" data-toggle="modal" data-target="#ubah" data-id="<?= $value['karyawan_id'] ?>" title="Edit gaji"><i class="ft-edit"></i></button>
										<button class="btn btn-success btn-sm btn-bg-gradient-x-purple-blue box-shadow-2 gaji-show" data-toggle="modal" data-target="#detail" data-id="<?= $value['karyawan_id'] ?>" title="Detail gaji"><i class="ft-eye"></i></button>
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

<!-- Modal hitung -->
<div class="modal fade text-left" id="hitung" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Hitung Gaji <span id="text_name_karyawan"></span></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formHitungGaji" action="gaji/store" method="POST">
				<input type="hidden" name="karyawan_id" id="hitung_karyawan_id">
				<input type="hidden" name="karyawan_status" id="hitung_karyawan_status">
				<input type="hidden" name="month_set" id="hitung_month_set">
				<div class="modal-body" id="rekap">
					<div class="row">
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Jabatan Karyawan</label>
								<input type="text" class="form-control" id="hitung_status_karyawan" readonly>
							</fieldset>
						</div>
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Gaji Pokok</label>
								<input type="number" class="form-control" id="hitung_rekap_gaji_pokok" readonly>
							</fieldset>
						</div>
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Total Kehadiran</label>
								<input type="number" class="form-control" id="hitung_rekap_jumlah_kehadiran" readonly>
							</fieldset>
						</div>
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Uang Makan (Rp 15.000/hari)</label>
								<input type="number" class="form-control" id="hitung_rekap_uang_makan" readonly>
							</fieldset>
						</div>
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Transport</label>
								<input type="number" class="form-control" id="hitung_rekap_uang_transport" readonly>
							</fieldset>
						</div>
						<div class="col-md-6">
							<fieldset class="form-group floating-label-form-group">
								<label>Pinjaman</label>
								<input type="number" class="form-control" id="hitung_rekap_total_pinjam" readonly>
							</fieldset>
						</div>
						<div class="col-md-12">
							<div class="rekap_tetap">
								<div class="row">
									<div class="col-md-4">
										<fieldset class="form-group floating-label-form-group">
											<label>Tabungan Saat ini</label>
											<input type="text" class="form-control" id="hitung_rekap_tabungan_saat_ini" readonly>
										</fieldset>
									</div>
									<div class="col-md-4">
										<fieldset class="form-group floating-label-form-group">
											<label>Tabungan Masuk</label>
											<input type="number" class="form-control" name="hitung_rekap_tabungan_masuk" id="hitung_rekap_tabungan_masuk">
										</fieldset>
									</div>
									<div class="col-md-4">
										<fieldset class="form-group floating-label-form-group">
											<label>Tabungan Keluar</label>
											<input type="number" class="form-control" name="hitung_rekap_tabungan_keluar" id="hitung_rekap_tabungan_keluar">
										</fieldset>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-body" id="lapangan">
						<!-- <fieldset class="form-group floating-label-form-group">
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
					</fieldset> -->
					</div>
				</div>
				<div class="modal-footer">
					<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
					<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Hitung Total Gaji Sekarang">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal ubah -->
<div class="modal fade text-left" id="ubah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Ubah Data Gaji <span id="edit_text_name_karyawan"></span></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formEditGaji" action="gaji/update" method="POST">
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<fieldset class="form-group floating-label-form-group">
						<label for="tanggal">Status Gaji</label>
						<select name="status_gaji" id="edit_status_gaji" class="form-control select2-ubah" required>
							<option value=""></option>
							<option value="pending">Pending</option>
							<option value="terbayar">Terbayar</option>
						</select>
					</fieldset>
				</div>
				<div class="modal-footer">
					<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
					<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Simpan">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal detail -->
<div class="modal fade text-left" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Detail Data Gaji <span id="detail_text_name_karyawan"></span></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detail_gaji_rekap">
				<div class="row">
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Status Gaji</label>
							<input type="text" class="form-control" id="show_rekap_gaji_status" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Gaji Bulan</label>
							<input type="text" class="form-control" id="show_rekap_gaji_bulan" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Gaji Tahun</label>
							<input type="text" class="form-control" id="show_rekap_gaji_tahun" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Gaji Pokok</label>
							<input type="text" class="form-control" id="show_rekap_gaji_pokok" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Uang Makan</label>
							<input type="text" class="form-control" id="show_rekap_gaji_uang_makan" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Transport</label>
							<input type="text" class="form-control" id="show_rekap_gaji_transport" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Pinjaman</label>
							<input type="text" class="form-control" id="show_rekap_gaji_pinjaman" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Tabungan Masuk</label>
							<input type="text" class="form-control" id="show_rekap_gaji_tabungan_masuk" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Tabungan Keluar</label>
							<input type="text" class="form-control" id="show_rekap_gaji_tabungan_keluar" readonly>
						</fieldset>
					</div>
					<div class="col-md-4">
						<fieldset class="form-group floating-label-form-group">
							<label>Total Gaji</label>
							<input type="text" class="form-control" id="show_rekap_gaji_total" readonly>
						</fieldset>
					</div>
				</div>
			</div>
			<div class="modal-body" id="detail_gaji_lapangan">
				<fieldset class="form-group floating-label-form-group">
					<label>Status Gaji</label>
					<input type="text" class="form-control" id="show_status_gaji" readonly>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
				<input type="submit" class="btn btn-primary btn-bg-gradient-x-blue-cyan" name="simpan" value="Simpan">
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.tengah {
		text-align: center;
	}

	.kotak {
		/* border: 1px solid rgba(0, 0, 0, 0.1); */
		padding: 5px;
	}

	@media print {
		body * {
			visibility: hidden;
		}

		.kotak,
		.kotak * {
			visibility: visible;
		}

		.kotak {
			position: absolute;
			width: 100%;
			margin-top: 350px;
			transform: scale(2);
		}
	}
</style>

<div class="modal fade text-left" id="slip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-print-none">
				<h3 class="modal-title" id="myModalLabel35"> Lihat Slip Gaji</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="kotak">
					<div class="row">
						<div class="col-12">
							<div class="tengah">
								<h3><b>Koperasi [nama]</b></h3>
							</div>
							<div class="tengah">[alamat]]</div>
							<hr>
							<div class="tengah"><b><u>SLIP GAJI KARYAWAN</u></b></div>
							<br>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<table>
								<tr>
									<td><b>Nama</b></td>
									<td>: <span id="slip_nama"></span></td>
								</tr>
								<tr>
									<td><b>Jabatan</b></td>
									<td>: <span id="slip_jabatan"></span></td>
								</tr>
							</table>
						</div>
						<div class="col-6">
							<table>
								<tr>
									<td><b>Bulan</b></td>
									<td>: <span id="slip_bulan"></span></td>
								</tr>
								<tr>
									<td><b>Jumlah Hari Masuk</b></td>
									<td>: <span id="slip_hari"></span></td>
								</tr>
							</table>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-8">
							<table style="width: 100%">
								<tr>
									<td><b>Gaji Pokok</b></td>
									<td>: <span id="slip_gaji_pokok"></span></td>
								</tr>
								<tr>
									<td><b>Gaji Bonus</b></td>
									<td>: <span id="slip_gaji_bonus"></span></td>
								</tr>
								<tr>
									<td><b>Lain - lain</b></td>
									<td>: <span id="slip_tabungan_keluar"></span></td>
								</tr>
							</table>
						</div>
						<div class="col-12 text-right">
							<p><b>JUMLAH &nbsp;<u><span id="slip_total_atas"></span></u></b></p>
						</div>
						<div class="col-7">
							<table style="width: 100%">
								<tr>
									<td><b>Bon</b></td>
									<td>: <span id="slip_pinjam"></span></td>
								</tr>
								<tr>
									<td><b>Tabungan</b></td>
									<td>: <span id="slip_tabungan"></span></td>
								</tr>
								<tr>
									<td><b>Potongan</b></td>
									<td>: <span id="slip_potongan"></span></td>
								</tr>
								<tr>
									<td><b>Kemacetan</b></td>
									<td>: <span id="slip_kemacetan"></span></td>
								</tr>
								<tr>
									<td><b>Lain-lain</b></td>
									<td>: <span id="slip_tidak_masuk"></span></td>
								</tr>
							</table>
						</div>
						<div class="col-12 text-right">
							<p><b>JUMLAH &nbsp;<u><span id="slip_total_bawah"></span></u></b></p>
						</div>
						<br>
						<div class="col-12 text-right">
							<p><b>TOTAL GAJI &nbsp;<u><span id="slip_sisa_gaji"></span></u></b></p>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-6">&nbsp;</div>
						<div class="col-6 text-center">
							<p>Kediri, <?= date_indo(date('Y-m-d')) ?></p>
							<p>Manajer</p>
							<br>
							<br>
							<br>
							<p><b><u>Nur Wahyuda</u></b></p>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-print-none">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-red-pink" data-dismiss="modal" value="Tutup">
				<button onclick="window.print()" class="btn btn-primary btn-bg-gradient-x-purple-blue"><i class="fa fa-print"></i> Cetak
				</button>
			</div>
		</div>
	</div>
</div>
