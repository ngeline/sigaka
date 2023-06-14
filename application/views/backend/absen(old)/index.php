<div class="row">
	<div class="col-md-12">
		<div class="card box-shadow-2">
			<?php
			if ($this->session->flashdata('alert') == 'tambah_absen'):
				?>
				<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Berhasil absen
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'update_absen'):
				?>
				<div class="alert alert-success alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Data berhasil diupdate
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'hapus_absen'):
				?>
				<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Data berhasil dihapus
				</div>
			<?php
			elseif ($this->session->flashdata('alert') == 'absen_sudah_ada'):
				?>
				<div class="alert alert-danger alert-dismissible animated fadeInDown" id="feedback" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Karyawan sudah absen hari ini
				</div>
			<?php
			endif;
			?>
			<div class="card-header">
				<h1 style="text-align: center">Absen Karyawan</h1>
				<div class="card">
			  	<div class="card-body">
    				<form class="form-inline">
		  			<div class="form-group mb-1">
		    			<label for="staticEmail2">Bulan</label>
	    				<select class="form-control ml-2" name="bulan">
		    				<option value=""> Pilih Bulan </option>
		    				<option value="Januari">Januari</option>
		    				<option value="Februari">Februari</option>
		    				<option value="Maret">Maret</option>
			    			<option value="April">April</option>
		    				<option value="Mei">Mei</option>
			    			<option value="Juni">Juni</option>
			    			<option value="Juli">Juli</option>
			    			<option value="Agustus">Agustus</option>
			    			<option value="September">September</option>
		    				<option value="Oktober">Oktober</option>
		    				<option value="November">November</option>
		    				<option value="Desember">Desember</option>
	   			 		</select>
	 				</div>
	  			<div class="form-group mb-1 ml-4">
	    			<label for="staticEmail2">Tahun</label>
	    				<select class="form-control ml-2" name="tahun">
		    				<option value=""> Pilih Tahun </option>
		    				<?php $tahun = date('Y');
		    					for($i=2023;$i<$tahun+5;$i++) { ?>
		    				<option value="<?php echo $i ?>"><?php echo $i ?></option>
							<?php }?>
						</select>
	  			</div>
	 		<button type="submit" class="btn btn-primary mb-1 ml-4 btn-bg-gradient-x-purple-blue box-shadow-2"><i class="ft-eye"></i> Tampilkan Data</button>
			
			<?php if ($this->session->userdata('session_hak_akses') == 'manajer'): ?>
			
				<a href="<?=base_url('absen/tambah/')?>"
				class="btn btn-primary mb-1 ml-2 btn-bg-gradient-x-blue-green box-shadow-2"
				data-toggle="tooltip" data-placement="top"
				title="Tambah Rekap absensi"><i class="ft-plus-circle"></i>Input Data Absensi</a>
			
			<?php endif; ?>
			<?php
				if((isset($_GET['bulan']) && $_GET['bulan']!='') && (isset($_GET['tahun']) && $_GET['tahun']!='')){
					$bulan = $_GET['bulan'];
					$tahun = $_GET['tahun'];
					$bulantahun = $bulan.$tahun;
				}else{
					$bulan = date('m');
					$tahun = date('Y');
					$bulantahun = $bulan.$tahun;
				}
	?>
			
			<div class="mb-1">
				Menampilkan Data Kehadiran Karyawan Bulan: <span class="font-weight-bold"><?php echo $bulan ?></span> Tahun: <span class="font-weight-bold"><?php echo $tahun ?></span>
			</div>
				<table class="table table-bordered" style="width: 100%">
					<thead>
					<tr>
						<td>ID Karyawan</td>
						<td>Nama Karyawan</td>
						<td>Jabatan</td>
						<td>Hadir</td>
						<td>Sakit</td>
						<td>Alpha</td>
						<!-- <td style="text-align: center"><i class="ft-settings spinner"></i></td> -->
					</tr>
					</thead>
					<tbody>
					<?php
					$no = 1;
					foreach ($absen as $key => $value):
						?>
						<tr>
							<td><?= $value['karyawan_id'] ?></td>
							<td><?= $value['karyawan_nama'] ?></td>
							<td><?= $value['jabatan_nama'] ?></td>
							<td><?= $value['absen_hari'] ?></td>
							<td><?php
								$tanggal = explode(' ', $value['absen_date_created']);
								echo date_indo($tanggal[0]);
								?>
							</td>
							<td>
								<?php
								if ($value['absen_status'] == 'normal'):
									?>
									<div class="badge badge-primary">
										<i class="ft-sun"></i> Normal
									</div>
								<?php
								elseif ($value['absen_status'] == 'lembur'):
									?>
									<div class="badge badge-secondary">
										<i class="ft-moon"></i> Lembur
									</div>
								<?php
								endif;
								?>
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
	</form>
  </div>
</div>

	
	
	</div>
</div>

<!-- Modal tambah -->



<!-- Modal lembur -->
<div class="modal fade text-left" id="lembur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel35"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel35"> Karyawan Lembur ?</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-footer">
				<input type="reset" class="btn btn-secondary btn-bg-gradient-x-blue-cyan" data-dismiss="modal"
					   value="Tutup">
				<div id="tombol-lembur">

				</div>
			</div>
		</div>
	</div>
</div>
