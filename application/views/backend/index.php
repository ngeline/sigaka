<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-lg-4 col-md-12">
				<div class="row">
					<div class="col-12">
						<div class="card ll-uppu bg-gradient-directional-danger">
							<div class="card-header">
								<h4 class="card-title white">Jumlah Karyawan</h4>
								<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
								<div class="heading-elements">
									<ul class="list-inline mb-0">
										<li>
											<a class="btn btn-sm btn-white danger box-shadow-1 round pull-right" href="<?= base_url('karyawan') ?>">Lihat<i class="ft-arrow-right pl-1"></i></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-content collapse show">
								<div class="card-body">
									<div class="media d-flex">
										<div class="align-self-center width-100">
											<div><i class="ft-users" style="color: white;font-size: 700%"></i></div>
										</div>
										<div class="media-body text-right mt-1">
											<h3 class="font-large-2 white"><?= $jumlah_karyawan ?></h3>
											<h6 class="mt-1"><span class="text-muted white">Jumlah Semua Karyawan</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12">
				<div class="row">
					<div class="col-12">
						<div class="card pull-up bg-gradient-directional-danger">
							<div class="card-header">
								<h4 class="card-title white">Absen</h4>
								<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
								<div class="heading-elements">
									<ul class="list-inline mb-0">
										<li>
											<a class="btn btn-sm btn-white danger box-shadow-1 round pull-right" href="<?= base_url('absensi') ?>">Lihat<i class="ft-arrow-right pl-1"></i></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-content collapse show">
								<div class="card-body">
									<div class="media d-flex">
										<div class="align-self-center width-100">
											<div><i class="ft-user-check" style="color: white;font-size: 700%"></i>
											</div>
										</div>
										<div class="media-body text-right mt-1">
											<h3 class="font-large-2 white"><?= $jumlah_absen ?></h3>
											<h6 class="mt-1"><span class="text-muted white">Jumlah Karyawan Absen Hari Ini
											</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12">
				<div class="row">
					<div class="col-12">
						<div class="card pull-up bg-gradient-directional-danger">
							<div class="card-header">
								<h4 class="card-title white">Pinjaman</h4>
								<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
								<div class="heading-elements">
									<ul class="list-inline mb-0">
										<li>
											<a class="btn btn-sm btn-white danger box-shadow-1 round pull-right" href="<?= base_url('pinjaman') ?>">Lihat<i class="ft-arrow-right pl-1"></i></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-content collapse show">
								<div class="card-body">
									<div class="media d-flex">
										<div class="align-self-center width-100">
											<div><i class="ft-calendar" style="color: white;font-size: 700%"></i>
											</div>
										</div>
										<div class="media-body text-right mt-1">
											<h3 class="font-large-2 white"><?= $jumlah_pinjam ?></h3>
											<h6 class="mt-1"><span class="text-muted white">Jumlah Karyawan Yang Meminjam
											</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
