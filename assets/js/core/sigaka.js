$(document).ready(function () {

	// ------------------------------------------------------------------------------------------
	// Default Sigaka
	// ------------------------------------------------------------------------------------------
	var root = window.location.origin + '/sigaka/';
	var data_total_kemacetan;

	$('#feedback').delay(3000).fadeOut('slow');

	table = $('#table_data').DataTable({
		responsive: true,
		columnDefs: [{
			"targets": [0],
			"width": '1%'
		}],
	});

	$('.select2-tambah').select2({
		placeholder: '- Pilih opsi -',
		dropdownParent: $('#tambah'),
		theme: 'bootstrap-5'
	});

	$('.select2-ubah').select2({
		placeholder: '- Pilih opsi -',
		dropdownParent: $('#ubah'),
		theme: 'bootstrap-5'
	});

	$('.select2-validasi').select2({
		placeholder: '- Pilih opsi -',
		dropdownParent: $('#validasi'),
		theme: 'bootstrap-5'
	});

	// $(document).on('click', '.edit-password', function (e) {
	// 	$('#ubahPasswordModal').modal('show');
	// });

	// ------------------------------------------------------------------------------------------
	// End Default Sigaka
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// easy autocomplete
	// ------------------------------------------------------------------------------------------
	var options = {
		url: root + 'karyawan/ajaxIndex',
		getValue: 'karyawan_nama',
		adjustWidth: false,
		list: {
			sort: {
				enabled: true
			},
			showAnimation: {
				type: "fade", //normal|slide|fade
				time: 400,
				callback: function () {}
			},

			hideAnimation: {
				type: "slide", //normal|slide|fade
				time: 400,
				callback: function () {}
			},
			match: {
				enabled: true
			},
			onSelectItemEvent: function () {
				var value = $("#nama_karyawan").getSelectedItemData().karyawan_id;

				$("#id_karyawan").val(value).trigger("change");
			}
		}
	};

	// $('#nama_karyawan').easyAutocomplete(options);

	// ------------------------------------------------------------------------------------------

	$('#laporan-btn-lihat').click(function () {
		var tahun = $('#laporan-tahun').val();
		var bulan = $('#laporan-bulan').val();
		var getUrl = root + 'laporan/lihat/' + tahun + '/' + bulan;
		var bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		var html = '';
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (response != null) {
					html += '' +
						'<div class="d-print-none float-right">' +
						'<button onclick="window.print()" class="btn btn-sm btn-primary btn-bg-gradient-x-purple-blue"><i class="fa fa-user"></i> Cetak' +
						'</button>' +
						'</div>';
					html += '' +
						'<h2 style="text-align: center">Selkom Group</h2>' +
						'<p style="text-align: center">Laporan Bulan ' + bulanArr[parseInt(bulan) - 1] + ' ' + tahun + '</p>' +
						'<table class="table table-bordered">' +
						'<thead style="text-align: center">' +
						'<tr>' +
						'<th>No</th>' +
						'<th>Nama Karyawan</th>' +
						'<th>Jabatan</th>' +
						'<th>Tanggal</th>' +
						'<th>Gaji</th>' +
						'<th>Pinjaman</th>' +
						'<th>Gaji Bersih</th>' +
						'</tr>' +
						'</thead>' +
						'<tbody>';
					var no = 1;
					var total = 0;
					var kotor = 0;
					var pinjaman = 0;
					for (var i = 0; i < response.length; i++) {
						html += '' +
							'<tr>' +
							'<td>' + no + '</td>' +
							'<td>' + response[i].karyawan_nama + '</td>' +
							'<td>' + response[i].jabatan_nama + '</td>' +
							'<td>' + date_indo(response[i].gaji_tanggal) + '</td>' +
							'<td style="text-align: right"> Rp. ' + formatRupiah((parseInt(response[i].gaji_lembur) + parseInt(response[i].gaji_total)).toString()) + '</td>' +
							'<td style="text-align: right"> Rp. ' + formatRupiah((parseInt(response[i].gaji_bayar_pinjaman)).toString()) + '</td>' +
							'<td style="text-align: right"> Rp. ' + formatRupiah(((parseInt(response[i].gaji_lembur) + parseInt(response[i].gaji_total)) - parseInt(response[i].gaji_bayar_pinjaman)).toString()) + '</td>' +
							'</tr>';
						total = total + (parseInt(response[i].gaji_lembur) + parseInt(response[i].gaji_total)) - parseInt(response[i].gaji_bayar_pinjaman);
						kotor = kotor + (parseInt(response[i].gaji_lembur) + parseInt(response[i].gaji_total));
						pinjaman = pinjaman + parseInt(response[i].gaji_bayar_pinjaman);
						no++;
					}
					var d = new Date();
					html += '' +
						'</tbody>' +
						'<tfoot>' +
						'<tr>' +
						'<td colspan="4" style="text-align: center"><b>Total</b></td>' +
						'<td style="text-align: right"> <b>Rp.' + formatRupiah(kotor.toString()) + '</b></td>' +
						'<td style="text-align: right"> <b>Rp.' + formatRupiah(pinjaman.toString()) + '</b></td>' +
						'<td style="text-align: right"> <b>Rp.' + formatRupiah(total.toString()) + '</b></td>' +
						'</tr>' +
						'</tfoot>' +
						'</table>' +
						'<div class="row">' +
						'<div class="col-8"></div>' +
						'<div class="col-4 text-center">' +
						'<p>Pekanbaru, ' + date_indo(d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()) + '</p>' +
						'<p>Manajer</p>' +
						'<br>' +
						'<br>' +
						'<br>' +
						'<p><b><u>Abdul Mustaqim</u></b></p>' +
						'</div>' +
						'</div>';
					$('#laporan').html(html);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		})
	});

	// ------------------------------------------------------------------------------------------
	// JS Potongan Gaji
	// ------------------------------------------------------------------------------------------

	$(document).on('click', '.potongan-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'potongan_gaji/edit/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.potongan_id);
					$('#edit_potongan').val(response.potongan_nama);
					$('#edit_jumlah').val(response.potongan_jumlah);
					$('#edit_keterangan').val(response.potongan_keterangan);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.potongan-delete', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'potongan_gaji/delete/' + id;

		Swal.fire({
			title: 'Anda yakin ingin hapus data ini?',
			text: "Data yang terhubung juga akan hilang!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus data ini!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = getUrl;
			}
		})
	});

	// ------------------------------------------------------------------------------------------
	// End JS Potongan Gaji
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Potongan Karyawan
	// ------------------------------------------------------------------------------------------

	$(document).on('click', '.karyawan-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'karyawan/show/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#edit_id_karyawan').val(response.karyawan_id);
					$('#edit_id_pengguna').val(response.pengguna_id);
					$('#edit_tg').val(response.karyawan_tanggal_gabung);
					$('#edit_status').val(response.karyawan_status).trigger('change');
					$('#edit_nama').val(response.karyawan_nama);
					$('#edit_tempat').val(response.karyawan_tempat_lahir);
					$('#edit_tl').val(response.karyawan_tanggal_lahir);
					$('#edit_alamat').val(response.karyawan_alamat);
					$('#edit_nohp').val(response.karyawan_nomor_hp);
					$('#edit_username').val(response.pengguna_username);
					// $('#edit_jabatan_karyawan').val(response.jabatan_nama);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.karyawan-delete', function (e) {
		e.preventDefault();
		var id_karyawan = $(this).data('id-karyawan');
		var id_pengguna = $(this).data('id-pengguna');
		var getUrl = root + 'karyawan/delete/' + id_karyawan + '/' + id_pengguna;

		Swal.fire({
			title: 'Anda yakin ingin hapus data ini?',
			text: "Data yang terhubung juga akan hilang!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus data ini!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = getUrl;
			}
		})
	});

	$(document).on('click', '.karyawan-show', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'karyawan/show/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#show_id_karyawan').val(response.karyawan_id);
					$('#show_id_pengguna').val(response.pengguna_id);
					$('#show_tg').val(response.karyawan_tanggal_gabung);
					$('#show_status').val(response.karyawan_status).trigger('change');
					$('#show_nama').val(response.karyawan_nama);
					$('#show_tempat').val(response.karyawan_tempat_lahir);
					$('#show_tl').val(response.karyawan_tanggal_lahir);
					$('#show_alamat').val(response.karyawan_alamat);
					$('#show_nohp').val(response.karyawan_nomor_hp);
					$('#show_username').val(response.pengguna_username);
					// $('#show_jabatan_karyawan').val(response.jabatan_nama);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	// ------------------------------------------------------------------------------------------
	// End JS Potongan Karyawan
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Absensi
	// ------------------------------------------------------------------------------------------

	$(document).on('change', '.absensi-month-picker', function (e) {
		var url = $(this).val();
		window.location.href = '/sigaka/absensi?month=' + url;
	})

	$(document).on('click', '.absensi-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'absensi/edit/' + id + '/' + $('.absensi-month-picker').val();
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.data.absensi_id ? response.data.absensi_id : 0);
					$('#karyawan').val(response.data.karyawan_id);
					$('#tahun').val(response.year_set);
					$('#bulan').val(response.month_set);
					$('#edit_kehadiran').val(response.data.absensi_kehadiran ? response.data.absensi_kehadiran : 0);
					$('#edit_ketidakhadiran').val(response.data.absensi_ketidakhadiran ? response.data.absensi_ketidakhadiran : 0);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	// ------------------------------------------------------------------------------------------
	// End JS Absensi
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Pinjaman
	// ------------------------------------------------------------------------------------------

	$(document).on('click', '.pinjaman-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'pinjaman/edit/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.data.pinjaman_id);
					$('#edit_karyawan').val(response.data.karyawan_id).trigger('change');
					$('#edit_jumlah').val(response.data.pinjaman_jumlah);
					$('#edit_deskripsi').val(response.data.pinjaman_deskripsi);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.pinjaman-validasi', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'pinjaman/edit/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id_validasi').val(response.data.pinjaman_id);
					$('#pinjaman_validasi_nama').val(response.data.karyawan_id);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	// ------------------------------------------------------------------------------------------
	// End JS Pinjaman
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Tabungan
	// ------------------------------------------------------------------------------------------

	$(document).on('click', '.riwayat-tabungan-show', function (e) {
		e.preventDefault();
		var id = $(this).data('id') ? $(this).data('id') : "0";
		var getUrl = root + 'tabungan/show/' + id;
		$('#table_riwayat_tabungan').DataTable().destroy();
		$('#table_riwayat_tabungan').DataTable({
			"responsive": true,
			"ajax": {
				"url": getUrl,
			},
			"columnDefs": [{
				"targets": [0],
				"width": '1%'
			}],
			"searching": false,
			"paging": false,
			"info": false
		});
	});

	// ------------------------------------------------------------------------------------------
	// End JS Tabungan
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Storting
	// ------------------------------------------------------------------------------------------

	$(document).on('change', '.storting-month-picker', function (e) {
		var url = $(this).val();
		window.location.href = '/sigaka/storting?month=' + url;
	})

	$(document).on('click', '.btn-kemacetan-edit', function (e) {
		data_total_kemacetan = $('#edit_total_kemacetan').val();
		$('.btn-kemacetan-save').prop('hidden', false);
		$('.btn-kemacetan-cancel').prop('hidden', false);
		$('.btn-kemacetan-edit').prop('hidden', true);
		$('#edit_total_kemacetan').prop('readonly', false);
	});

	$(document).on('click', '.btn-kemacetan-cancel', function (e) {
		data_total_kemacetan ? $('#edit_total_kemacetan').val(data_total_kemacetan) : $('#edit_total_kemacetan').val('');
		$('.btn-kemacetan-save').prop('hidden', true);
		$('.btn-kemacetan-cancel').prop('hidden', true);
		$('.btn-kemacetan-edit').prop('hidden', false);
		$('#edit_total_kemacetan').prop('readonly', true);
	});

	$(document).on('click', '.storting-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'storting/edit/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.data.storting_id);
					$('#edit_tanggal').val(response.data.storting_tanggal);
					$('#edit_pinjaman').val(response.data.storting_pinjaman);
					$('#edit_angsuran').val(response.data.storting_angsuran);
					$('#edit_angsuran_hutang').val(response.data.storting_angsuran_hutang);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.storting-delete', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var getUrl = root + 'storting/delete/' + id;

		Swal.fire({
			title: 'Anda yakin ingin hapus data ini?',
			text: "Data yang terhubung juga akan hilang!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus data ini!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = getUrl;
			}
		})
	});

	$(document).on('click', '.storting-riwayat-show', function (e) {
		var id = $(this).data('id');
		var date = $('.storting-month-picker').val();
		window.location.href = '/sigaka/storting/riwayat?month=' + date + '&id=' + id;
	});

	$(document).on('click', '.storting-riwayat-kembali', function (e) {
		window.location.href = '/sigaka/storting';
	});

	// $(document).on('change', '.storting-riwayat-month-picker', function (e) {
	// 	var date = $(this).val();
	// 	var id = $('.data-storting-riwayat').data('id');
	// 	window.location.href = '/sigaka/storting/riwayat?month=' + date + '&id=' + id;
	// })

	$(document).on('click', '.storting-riwayat-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var return_id_karyawan = $('.data-storting-riwayat').data('id');
		var return_date = $('.storting-riwayat-month-picker').val();
		var getUrl = root + 'storting/edit/' + id;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.data.storting_id);
					$('#return_date_riwayat_storting').val(return_date);
					$('#return_id_karyawan_riwayat_storting').val(return_id_karyawan);
					$('#edit-storting-riwayat-status').val(response.data.storting_status).trigger('change');
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.storting-riwayat-validasi-semua', function (e) {
		e.preventDefault();
		var return_id_karyawan = $('.data-storting-riwayat').data('id');
		var return_date = $('.storting-riwayat-month-picker').val();
		var getUrl = root + 'storting/riwayat/update/semua?date=' + return_date + '&id_karyawan=' + return_id_karyawan;

		Swal.fire({
			title: 'Apakah kamu yakin?',
			text: 'Anda akan memvalidasi semua data storting bulan ini!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, lakukan!',
			cancelButtonText: 'Tidak, batalkan'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = getUrl;
			}
		});
	});

	$(document).on('click', '.btn-validasi-status-kemacetan', function (e) {
		e.preventDefault();
		var return_id_karyawan = $('.data-storting-riwayat').data('id');
		var return_date = $('.storting-riwayat-month-picker').val();
		Swal.fire({
			title: 'Konfirmasi Status?',
			text: 'Anda akan ubah status total kemacetan ini!',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ubah ke validasi',
			cancelButtonText: 'Kembali',
			reverseButtons: true,
			showCloseButton: true,
			showDenyButton: true,
			denyButtonText: 'Ubah ke pending',
		}).then((result) => {
			if (result.isConfirmed) {
				var getUrl = root + 'storting/riwayat/update/status/validasi?date=' + return_date + '&id_karyawan=' + return_id_karyawan;
				window.location.href = getUrl;
			} else if (result.isDenied) {
				var getUrl = root + 'storting/riwayat/update/status/pending?date=' + return_date + '&id_karyawan=' + return_id_karyawan;
				window.location.href = getUrl;
			}
		});

	});

	// ------------------------------------------------------------------------------------------
	// End JS Storting
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Gaji
	// ------------------------------------------------------------------------------------------

	$(document).on('change', '.gaji-month-picker', function (e) {
		var url = $(this).val();
		window.location.href = '/sigaka/gaji?month=' + url;
	});

	$(document).on('click', '.gaji-hitung', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var status_karyawan = $(this).data('status');
		var month = $('.gaji-month-picker').val();
		var getUrl = root + 'gaji/hitung/' + id + "/" + month + "/" + status_karyawan;
		$('#rekap').hide();
		$('.rekap_tetap').hide();
		$('#lapangan').hide();
		$('.lapangan_tetap').hide();
		$(".gaji_opsi_pinjaman_ambil").empty().trigger("change");
		$(".gaji_opsi_pinjaman_bayar").empty().trigger("change");
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#hitung_karyawan_id').val(response.karyawan_id);
					$('#text_name_karyawan').text(response.karyawan_nama + ' Bulan ' + response.bulan + ' Tahun ' + response.tahun);
					$('#hitung_karyawan_status').val(response.karyawan_status);
					$('#hitung_month_set').val(response.month_set);
					if (response.karyawan_status == 'rekap training' || response.karyawan_status == 'rekap tetap' || response.karyawan_status == 'kasir') {
						$('#rekap').show();
						$('#hitung_status_karyawan').val(response.karyawan_status);
						$('#hitung_rekap_total_pinjam').val(response.pinjaman_bayar);
						$('#hitung_rekap_gaji_pokok').val(response.gaji_pokok);
						$('#hitung_rekap_jumlah_kehadiran').val(response.jumlah_kehadiran);
						$('#hitung_rekap_uang_makan').val(response.uang_makan);
						$('#hitung_rekap_uang_transport').val(response.uang_transport);
						if (response.karyawan_status == 'rekap tetap' || response.karyawan_status == 'kasir') {
							$('#hitung_rekap_tabungan_saat_ini').val(response.tabungan_saat_ini);
							$('.rekap_tetap').show();
						}
					} else {
						$('#lapangan').show();
						$('#hitung_lapangan_status_karyawan').val(response.karyawan_status);
						$('#hitung_lapangan_kehadiran').val(response.jumlah_kehadiran);
						$('#hitung_lapangan_pinjaman').val(response.total_pinjaman);
						$('#hitung_lapangan_angsuran').val(response.total_angsuran);
						$('#hitung_lapangan_angsuran_hutang').val(response.total_angsuran_hutang);
						$('#hitung_lapangan_kemacetan').val(response.total_kemacetan);
						$('#hitung_lapangan_index').val(response.index);
						$('#hitung_lapangan_gaji_pokok').val(response.gaji_pokok);
						$('#hitung_lapangan_gaji_bonus').val(response.gaji_bonus);
						$('#hitung_lapangan_gaji_potongan').val(response.potongan);
						$('#hitung_lapangan_gaji_potongan_kemacetan').val(response.potongan_kemacetan);
						$('#hitung_lapangan_gaji_potongan_absen').val(response.potongan_absen);
						$('#hitung_lapangan_gaji_potongan_bon').val(response.pinjaman_bayar);
						$('#hitung_lapangan_gaji_kemacetan_bulan').text(response.potongan_kemacetan_bulan);
						if (response.karyawan_status == 'lapangan tetap') {
							$('#hitung_lapangan_tabungan_saat_ini').val(response.tabungan_saat_ini);
							$('.lapangan_tetap').show();
						}
					}
					$('#hitung').modal('show');
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.gaji-edit', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var month = $('.gaji-month-picker').val();
		var getUrl = root + 'gaji/edit/' + id + "/" + month;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#id').val(response.gaji_id);
					$('#edit_status_gaji').val(response.gaji_status).trigger('change');
					$('#edit_text_name_karyawan').text(response.karyawan_nama + ' Bulan ' + response.gaji_bulan_ke + ' Tahun ' + response.gaji_tahun_ke);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.gaji-show', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var month = $('.gaji-month-picker').val();
		var current_month = month ? month : 0;
		var getUrl = root + 'gaji/edit/' + id + "/" + current_month;
		$('#detail_gaji_rekap').hide();
		$('#detail_gaji_lapangan').hide();
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					if (response.karyawan_status == 'rekap tetap' || response.karyawan_status == 'rekap training') {
						$('#detail_text_name_karyawan').text(response.karyawan_nama + ' Bulan ' + response.gaji_bulan_ke + ' Tahun ' + response.gaji_tahun_ke);
						$('#show_rekap_gaji_status').val(response.gaji_status);
						$('#show_rekap_gaji_bulan').val(response.gaji_bulan_ke);
						$('#show_rekap_gaji_tahun').val(response.gaji_tahun_ke);
						$('#show_rekap_gaji_pokok').val(response.gaji_pokok);
						$('#show_rekap_gaji_uang_makan').val(response.gaji_uang_makan);
						$('#show_rekap_gaji_transport').val(response.gaji_transport);
						$('#show_rekap_gaji_pinjaman').val(response.gaji_pinjaman_bayar ? response.gaji_pinjaman_bayar : 0);
						$('#show_rekap_gaji_tabungan_masuk').val(response.gaji_tabungan_masuk ? response.gaji_tabungan_masuk : 0);
						$('#show_rekap_gaji_tabungan_keluar').val(response.gaji_tabungan_keluar ? response.gaji_tabungan_keluar : 0);
						$('#show_rekap_gaji_total').val(response.gaji_total);
						$('#detail_gaji_rekap').show();
					} else {
						$('#show_lapangan_gaji_status').val(response.karyawan_status);
						$('#show_lapangan_gaji_bulan').val(response.gaji_bulan_ke);
						$('#show_lapangan_gaji_tahun').val(response.gaji_tahun_ke);
						$('#show_lapangan_gaji_pokok').val(response.gaji_pokok);
						$('#show_lapangan_gaji_bonus').val(response.gaji_bonus);
						$('#show_lapangan_gaji_potongan').val(response.gaji_potongan_total);
						$('#show_lapangan_gaji_potongan_kemacetan').val(response.gaji_potongan_kemacetan);
						$('#show_lapangan_gaji_potongan_absen').val(response.gaji_potongan_tidak_masuk);
						$('#show_lapangan_gaji_pinjaman').val(response.gaji_pinjaman_bayar ? response.gaji_pinjaman_bayar : 0);
						$('#show_lapangan_gaji_tabungan_masuk').val(response.gaji_tabungan_masuk ? response.gaji_tabungan_masuk : 0);
						$('#show_lapangan_gaji_tabungan_keluar').val(response.gaji_tabungan_keluar ? response.gaji_tabungan_keluar : 0);
						$('#show_lapangan_gaji_total').val(response.gaji_total);
						$('#detail_gaji_lapangan').show();
					}
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$(document).on('click', '.gaji-slip', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		var month = $('.gaji-month-picker').val();
		var getUrl = root + 'gaji/slip/' + id + "/" + month;
		$.ajax({
			url: getUrl,
			type: 'ajax',
			dataType: 'json',
			success: function (response) {
				if (response != null) {
					$('#slip_nama').text(response.slip_nama);
					$('#slip_jabatan').text(response.slip_jabatan);
					$('#slip_bulan').text(response.slip_bulan);
					$('#slip_hari').text(response.slip_hari);
					$('#slip_gaji_pokok').text(response.slip_gaji_pokok);
					$('#slip_gaji_bonus').text(response.slip_gaji_bonus);
					$('#slip_tabungan_keluar').text(response.slip_tabungan_keluar);
					$('#slip_total_atas').text(response.slip_total_atas);
					$('#slip_pinjam').text(response.slip_pinjam);
					$('#slip_tabungan').text(response.slip_tabungan);
					$('#slip_potongan').text(response.slip_potongan);
					$('#slip_kemacetan').text(response.slip_kemacetan);
					$('#slip_tidak_masuk').text(response.slip_tidak_masuk);
					$('#slip_total_bawah').text(response.slip_total_bawah);
					$('#slip_sisa_gaji').text(response.slip_sisa_gaji);
				}
			},
			error: function (response) {
				console.log(response.status + 'error');
			}
		});
	});

	$("#formHitungGaji").submit(function (event) {
		event.preventDefault();

		Swal.fire({
			title: 'Apakah kamu yakin?',
			text: 'Anda tidak dapat merubah data ini setelah generate/hitung gaji, pastikan data benar!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, lakukan!',
			cancelButtonText: 'Tidak, batalkan'
		}).then((result) => {
			if (result.isConfirmed) {
				event.currentTarget.submit();
			}
		});
	});

	$("#formEditGaji").submit(function (event) {
		event.preventDefault();

		Swal.fire({
			title: 'Apakah kamu yakin?',
			text: 'Anda tidak dapat merubah data ini setelah merubah status, pastikan data benar!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, lakukan!',
			cancelButtonText: 'Tidak, batalkan'
		}).then((result) => {
			if (result.isConfirmed) {
				event.currentTarget.submit();
			}
		});
	});

	// ------------------------------------------------------------------------------------------
	// End JS Gaji
	// ------------------------------------------------------------------------------------------

	// ------------------------------------------------------------------------------------------
	// JS Laporan
	// ------------------------------------------------------------------------------------------

	$(document).on('change', '.laporan-month-picker', function (e) {
		var url = $(this).val();
		window.location.href = '/sigaka/laporan?month=' + url;
	});

	$(document).on('click', '#cetak_laporan', function (e) {
		var url = $('.laporan-month-picker').val();
		// window.location.href = '/sigaka/laporan/cetak/' + url;
		window.open('/sigaka/laporan/cetak/' + url, "_blank");
	});

	// ------------------------------------------------------------------------------------------
	// End JS Laporan
	// ------------------------------------------------------------------------------------------

});

// ------------------------------------------------------------------------------------------
// Fungsi-fungsi
// ------------------------------------------------------------------------------------------

function formatRupiah(angka, prefix) {
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
		split = number_string.split(','),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function terbilang(s) {
	var bilangan = s;
	var kalimat = "";
	var angka = new Array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
	var kata = new Array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
	var tingkat = new Array('', 'Ribu', 'Juta', 'Milyar', 'Triliun');
	var panjang_bilangan = bilangan.length;

	/* pengujian panjang bilangan */
	if (panjang_bilangan > 15) {
		kalimat = "Diluar Batas";
	} else {
		/* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
		for (i = 1; i <= panjang_bilangan; i++) {
			angka[i] = bilangan.substr(-(i), 1);
		}

		var i = 1;
		var j = 0;

		/* mulai proses iterasi terhadap array angka */
		while (i <= panjang_bilangan) {
			subkalimat = "";
			kata1 = "";
			kata2 = "";
			kata3 = "";

			/* untuk Ratusan */
			if (angka[i + 2] != "0") {
				if (angka[i + 2] == "1") {
					kata1 = "Seratus";
				} else {
					kata1 = kata[angka[i + 2]] + " Ratus";
				}
			}

			/* untuk Puluhan atau Belasan */
			if (angka[i + 1] != "0") {
				if (angka[i + 1] == "1") {
					if (angka[i] == "0") {
						kata2 = "Sepuluh";
					} else if (angka[i] == "1") {
						kata2 = "Sebelas";
					} else {
						kata2 = kata[angka[i]] + " Belas";
					}
				} else {
					kata2 = kata[angka[i + 1]] + " Puluh";
				}
			}

			/* untuk Satuan */
			if (angka[i] != "0") {
				if (angka[i + 1] != "1") {
					kata3 = kata[angka[i]];
				}
			}

			/* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
			if ((angka[i] != "0") || (angka[i + 1] != "0") || (angka[i + 2] != "0")) {
				subkalimat = kata1 + " " + kata2 + " " + kata3 + " " + tingkat[j] + " ";
			}

			/* gabungkan variabe sub kalimat (untuk Satu blok 3 angka) ke variabel kalimat */
			kalimat = subkalimat + kalimat;
			i = i + 3;
			j = j + 1;
		}

		/* mengganti Satu Ribu jadi Seribu jika diperlukan */
		if ((angka[5] == "0") && (angka[6] == "0")) {
			kalimat = kalimat.replace("Satu Ribu", "Seribu");
		}
	}
	return kalimat;
}

function date_indo(s) {
	var string = s;
	var split = string.split('-');
	var tahun = split[0];
	var bulan = split[1];
	var tanggal = split[2];
	var bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

	return tanggal + ' ' + bulanArr[parseInt(bulan) - 1] + ' ' + tahun;
}

function days_between(StartDate, EndDate) {
	// Here are the two dates to compare
	var date1 = StartDate;
	var date2 = EndDate;

	// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
	date1 = date1.split('-');
	date2 = date2.split('-');

	// Now we convert the array to a Date object, which has several helpful methods
	date1 = new Date(date1[0], date1[1], date1[2]);
	date2 = new Date(date2[0], date2[1], date2[2]);

	// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
	date1_unixtime = parseInt(date1.getTime() / 1000);
	date2_unixtime = parseInt(date2.getTime() / 1000);

	// This is the calculated difference in seconds
	var timeDifference = date2_unixtime - date1_unixtime;

	// in Hours
	var timeDifferenceInHours = timeDifference / 60 / 60;

	// and finaly, in days :)
	var timeDifferenceInDays = timeDifferenceInHours / 24;

	return (timeDifferenceInDays);
}

function nama_bulan_indo(s) {
	var bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
	for (let index = 0; index < 12; index++) {
		if (s == bulanArr[index]) {
			return bulanArr[index];
			break;
		}
	}
}

function add_options(selectorElement, text, value) {
	// Get the Select2 element
	var selectElement = $(selectorElement);

	// Create the new option
	var newOption = new Option(text, value);

	// Append the new option to the Select2 element
	selectElement.append(newOption);
}
