<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GajiController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$model = array('GajiModel', 'PinjamanModel', 'KaryawanModel', 'TabunganModel', 'AbsensiModel', 'PotonganGajiModel', 'KemacetanModel');
		$helper = array('tgl_indo', 'nominal', 'main_helper');
		$this->load->model($model);
		$this->load->helper($helper);
		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}
	}

	// rekap + kasir
	private function hitung_gaji_rekap($id_karyawan, $month)
	{
		$month_set = explode('-', $month);

		$data_karyawan = $this->GajiModel->hitungGajiRekap($id_karyawan, $month_set);
		$data_pinjaman = $this->GajiModel->pinjamanBayarByIdKaryawan($id_karyawan);

		switch ($data_karyawan['karyawan_status']) {
			case 'rekap training':
				$gaji_pokok = 1000000;
				break;

			case 'rekap tetap':
				$gaji_pokok = 1150000;
				break;

			case 'kasir':
				$gaji_pokok = 1400000;
				break;

			default:
				$gaji_pokok = 0;
				break;
		}

		$data_rekap = array(
			'karyawan_id' => $id_karyawan,
			'karyawan_nama' => $data_karyawan['karyawan_nama'],
			'karyawan_status' => $data_karyawan['karyawan_status'],
			'bulan' => $month_set[1],
			'tahun' => $month_set[0],
			'month_set' => $month,
			'gaji_pokok' => $gaji_pokok,
			'jumlah_kehadiran' => $data_karyawan['absensi_kehadiran'] ? $data_karyawan['absensi_kehadiran'] : 0,
			'uang_makan' => hitung_uang_makan($data_karyawan['absensi_kehadiran']),
			'uang_transport' => 150000,
			'tabungan_saat_ini' => $data_karyawan['tabungan_jumlah'] ? $data_karyawan['tabungan_jumlah'] : 0,
			'tabungan_id' => $data_karyawan['tabungan_id'],
			'pinjaman_bayar' => $data_pinjaman ? $data_pinjaman['pinjaman_jumlah'] : 0,
			'pinjaman_id' => $data_pinjaman ? $data_pinjaman['pinjaman_id'] : ''
		);

		return $data_rekap;
	}

	private function hitung_gaji_lapangan($id_karyawan, $month)
	{
		// $tanggal = strtotime($month); // Konversi nilai menjadi timestamp
		// $dua_bulan_sebelum = strtotime('-2 months', $tanggal); // Mengurangi 2 bulan dari tanggal
		// $hasil = date('Y-m', $dua_bulan_sebelum);
		// $hasil_explode = explode('-', $hasil);
		$hasil_explode = explode('-', $month);

		$month_set = explode('-', $month);

		$data_karyawan = $this->GajiModel->hitungGajiLapangan($id_karyawan, $month_set);
		$data_pinjaman = $this->GajiModel->pinjamanBayarByIdKaryawan($id_karyawan);
		$data_storting = $this->GajiModel->hitungStorting($id_karyawan, $month_set);
		$data_kemacetan = $this->KemacetanModel->find_kemacetan_karyawan($id_karyawan, $hasil_explode);

		$jumlah_kehadiran = $data_karyawan['absensi_kehadiran'] ? $data_karyawan['absensi_kehadiran'] : 0;
		$total_pinjaman = $data_storting['pinjaman'] ? $data_storting['pinjaman'] : 0;
		$total_angsuran = $data_storting['angsuran'] ? $data_storting['angsuran'] : 0;
		$total_angsuran_hutang = $data_storting['angsuran_hutang'] ? $data_storting['angsuran_hutang'] : 0;
		$total_kemacetan = $data_kemacetan ? $data_kemacetan->kemacetan_jumlah : 0;
		$potongan_kemacetan = $data_kemacetan ? round(($data_kemacetan->kemacetan_jumlah - $data_storting['angsuran_hutang']) / $jumlah_kehadiran) : 0;
		$bon = $data_pinjaman ? $data_pinjaman['pinjaman_jumlah'] : 0;
		$tabungan = $data_karyawan['tabungan_jumlah'] ? $data_karyawan['tabungan_jumlah'] : 0;

		$data_index = 0;
		if ($total_angsuran > 0 && $total_pinjaman > 0) {
			$bagi_angsuran_dgn_pinjam = intval($total_angsuran) / intval($total_pinjaman);
			$data_index = intval(($bagi_angsuran_dgn_pinjam * 100) % 100);
		}

		$data_sum_potongan = $this->PotonganGajiModel->sumPotongan();
		$rata_rata_angsuran = 0;
		if ($total_angsuran > 0) {
			$rata_rata_angsuran = round($total_angsuran / $jumlah_kehadiran);
		}

		switch ($data_karyawan['karyawan_status']) {
			case 'lapangan training':
				$gaji_pokok = 1000000;
				$gaji_bonus = 0;
				break;
			case 'lapangan tetap':
				if (round($data_index) < 15) {
					$gaji_pokok = $rata_rata_angsuran;
					$gaji_bonus = 0;
				} elseif (round($data_index) >= 15 && round($data_index) <= 19) {
					$gaji_pokok = round(intval($rata_rata_angsuran * 1.2));
					$gaji_bonus = 0;
				} elseif (round($data_index) >= 20) {
					$gaji_pokok = round(intval($rata_rata_angsuran * 1.4));
					if ($data_index == 21) {
						$gaji_bonus = 10000;
					} else if ($data_index == 22) {
						$gaji_bonus = 20000;
					} else if ($data_index == 23) {
						$gaji_bonus = 30000;
					} else if ($data_index == 24) {
						$gaji_bonus = 40000;
					} else if ($data_index >= 25) {
						$gaji_bonus = 50000;
					} else {
						$gaji_bonus = 0;
					}
				}
				break;
			default:
				$gaji_pokok = 0;
				$gaji_bonus = 0;
				break;
		}

		$potongan_absensi = 0;
		if ($data_karyawan['absensi_ketidakhadiran'] > 0) {
			$potongan_absensi = round(intval(($gaji_pokok + $gaji_bonus) / $data_karyawan['absensi_kehadiran']), -3);
		}

		$data_lapangan = array(
			'karyawan_id' => $id_karyawan,
			'karyawan_nama' => $data_karyawan['karyawan_nama'],
			'karyawan_status' => $data_karyawan['karyawan_status'],
			'bulan' => $month_set[1],
			'tahun' => $month_set[0],
			'month_set' => $month,
			'jumlah_kehadiran' => $jumlah_kehadiran,
			'total_pinjaman' => $total_pinjaman,
			'total_angsuran' => $total_angsuran,
			'total_angsuran_hutang' => $total_angsuran_hutang,
			'total_kemacetan' => $total_kemacetan,
			'index' => $data_index,
			'gaji_pokok' => $gaji_pokok,
			'gaji_bonus' => $gaji_bonus,
			'potongan' => $data_sum_potongan->total,
			'potongan_kemacetan' => $data_karyawan['karyawan_status'] == 'lapangan tetap' ? $potongan_kemacetan : 0,
			'potongan_kemacetan_bulan' => bulan_with_zero($hasil_explode[1]),
			'potongan_absen' => $potongan_absensi,
			'pinjaman_bayar' => $bon,
			'pinjaman_id' => $data_pinjaman ? $data_pinjaman['pinjaman_id'] : '',
			'tabungan_saat_ini' => $tabungan,
			'tabungan_id' => $data_karyawan['tabungan_id']
		);

		return $data_lapangan;
	}

	public function index()
	{
		$get_data = $this->input->get('month');
		$month_set = $get_data ? explode('-', $get_data) : explode('-', date('Y-m'));

		$id_karyawan = '';
		if ($this->session->userdata('session_karyawan_status') != 'kasir' && $this->session->userdata('session_karyawan_status') != null) {
			$id_karyawan = $this->session->userdata('session_karyawan_id');
		}

		$data = array(
			'gaji' => $this->GajiModel->findAll($id_karyawan, $month_set),
			'title' => 'Gaji',
			'date_set' => "{$month_set[0]}-{$month_set[1]}",
			'month_set' => $month_set[1],
			'year_set' => $month_set[0]
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/gaji/index', $data);
		$this->load->view('templates/footer');
	}

	public function hitung($id_karyawan, $month, $status)
	{
		if ($status == 'rekap%20training' || $status == 'rekap%20tetap' || $status == 'kasir') {
			$data = $this->hitung_gaji_rekap($id_karyawan, $month);
		} elseif ($status == 'lapangan%20training' || $status == 'lapangan%20tetap') {
			$data = $this->hitung_gaji_lapangan($id_karyawan, $month);
		}

		echo json_encode($data);
	}

	public function store_hitung()
	{
		$data_get = $this->input->post();

		$month_split = explode('-', $data_get['month_set']);

		$this->db->trans_start();

		try {

			if ($data_get['karyawan_status'] == 'rekap training' || $data_get['karyawan_status'] == 'rekap tetap' || $data_get['karyawan_status'] == 'kasir') {
				$data_hitung_rekap = $this->hitung_gaji_rekap($data_get['karyawan_id'], $data_get['month_set']);
				$values_add = [$data_hitung_rekap['gaji_pokok'], $data_hitung_rekap['uang_makan'], $data_hitung_rekap['uang_transport']];
				$values_subtract = [];

				$data = array(
					'gaji_bulan_ke' => $month_split[1],
					'gaji_tahun_ke' => $month_split[0],
					'gaji_pokok' => $data_hitung_rekap['gaji_pokok'],
					'gaji_uang_makan' => $data_hitung_rekap['uang_makan'],
					'gaji_transport' => $data_hitung_rekap['uang_transport'],
					'gaji_status' => 'pending',
					'gaji_date_updated' => current_datetime_indo(),
				);

				// Jika Post Pinjam Bayar Tidak Kosong
				if ($data_hitung_rekap['pinjaman_bayar'] > 0 && !empty($data_hitung_rekap['pinjaman_id'])) {
					$this->PinjamanModel->update($data_hitung_rekap['pinjaman_id'], ['pinjaman_status' => 'lunas']);

					$data['gaji_pinjaman_bayar'] = $data_hitung_rekap['pinjaman_bayar'];
					array_push($values_subtract, intval($data_hitung_rekap['pinjaman_bayar']));
				}

				// Jika Post Tabungan Masuk Tidak Kosong
				if (!empty($data_get['hitung_rekap_tabungan_masuk'])) {
					array_push($values_subtract, intval($data_get['hitung_rekap_tabungan_masuk']));

					$data['gaji_tabungan_masuk'] = intval($data_get['hitung_rekap_tabungan_masuk']);

					if (!empty($data_hitung_rekap['tabungan_id'])) {
						$arr_data_tabungan = array(
							'tabungan_jumlah' => intval($data_hitung_rekap['tabungan_saat_ini']) + intval($data_get['hitung_rekap_tabungan_masuk']),
							'tabungan_date_updated' => current_datetime_indo(),
						);

						$arr_data_riwayat_tabungan = array(
							'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
							'riwayat_id_tabungan' => $data_hitung_rekap['tabungan_id'],
							'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_masuk'],
							'riwayat_tabungan_status' => 'masuk'
						);

						$this->TabunganModel->update($data_hitung_rekap['tabungan_id'], $arr_data_tabungan);
						$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
					} else {
						$kode_tabungan = 'TB-' . substr(time(), 4) . rand(11, 99);
						$arr_data_tabungan = array(
							'tabungan_id' => $kode_tabungan,
							'tabungan_karyawan_id' => $data_get['karyawan_id'],
							'tabungan_jumlah' => $data_get['hitung_rekap_tabungan_masuk'],
							'tabungan_date_updated' => current_datetime_indo(),
						);

						$arr_data_riwayat_tabungan = array(
							'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
							'riwayat_id_tabungan' => $kode_tabungan,
							'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_masuk'],
							'riwayat_tabungan_status' => 'masuk'
						);

						$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
						// $arr_data_riwayat_tabungan['riwayat_id_tabungan'] = $data_tabungan_id;
						$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
					}
				}

				// Jika Post Tabungan Masuk Tidak Kosong
				if (!empty($data_get['hitung_rekap_tabungan_keluar'])) {
					if (intval($data_get['hitung_rekap_tabungan_keluar']) > intval($data_hitung_rekap['tabungan_saat_ini'])) {
						$this->session->set_flashdata('alert', 'error');
						$this->session->set_flashdata('message', 'Saldo tabungan tidak cukup');
						redirect("gaji?month={$data_get['month_set']}");
					} else {
						array_push($values_add, $data_get['hitung_rekap_tabungan_keluar']);

						$data['gaji_tabungan_keluar'] = $data_get['hitung_rekap_tabungan_keluar'];

						if (!empty($data_hitung_rekap['tabungan_id'])) {
							$arr_data_tabungan = array(
								'tabungan_jumlah' => intval($data_hitung_rekap['tabungan_saat_ini']) - intval($data_get['hitung_rekap_tabungan_keluar']),
								'tabungan_date_updated' => current_datetime_indo(),
							);

							$arr_data_riwayat_tabungan = array(
								'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
								'riwayat_id_tabungan' => $data_hitung_rekap['tabungan_id'],
								'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_keluar'],
								'riwayat_tabungan_status' => 'keluar'
							);

							$this->TabunganModel->update($data_hitung_rekap['tabungan_id'], $arr_data_tabungan);
							$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
						} else {
							$kode_tabungan = 'TB-' . substr(time(), 4) . rand(11, 99);
							$arr_data_tabungan = array(
								'tabungan_id' => $kode_tabungan,
								'tabungan_karyawan_id' => $data_get['karyawan_id'],
								'tabungan_jumlah' => intval($data_hitung_rekap['tabungan_saat_ini']) - intval($data_get['hitung_rekap_tabungan_keluar']),
								'tabungan_date_updated' => current_datetime_indo(),
							);

							$arr_data_riwayat_tabungan = array(
								'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
								'riwayat_id_tabungan' => $kode_tabungan,
								'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_keluar'],
								'riwayat_tabungan_status' => 'keluar'
							);

							$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
							// $arr_data_riwayat_tabungan['riwayat_id_tabungan'] = $data_tabungan_id;
							$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
						}
					}
				}

				$values = [$values_add, $values_subtract];
				$operations = ['add', 'subtract'];
				$data['gaji_total'] = hitung_total($values, $operations);
				$check_record = $this->GajiModel->find($data_get['karyawan_id'], $month_split);

				if (!empty($check_record)) {
					$this->GajiModel->update($check_record['gaji_id'], $data);
				} else {
					$data['gaji_id'] = 'GJ-' . substr(time(), 2);
					$data['gaji_karyawan_id'] = $data_get['karyawan_id'];
					$this->GajiModel->insert($data);
				}
			} else {
				$data_hitung_lapangan = $this->hitung_gaji_lapangan($data_get['karyawan_id'], $data_get['month_set']);
				$values_add = [$data_hitung_lapangan['gaji_pokok'], $data_hitung_lapangan['gaji_bonus']];
				$values_subtract = [$data_hitung_lapangan['potongan'], $data_hitung_lapangan['potongan_kemacetan'], $data_hitung_lapangan['potongan_absen']];

				$data = array(
					'gaji_bulan_ke' => $month_split[1],
					'gaji_tahun_ke' => $month_split[0],
					'gaji_status' => 'pending',
					'gaji_pokok' => $data_hitung_lapangan['gaji_pokok'],
					'gaji_bonus' => $data_hitung_lapangan['gaji_bonus'],
					'gaji_potongan_total' => $data_hitung_lapangan['potongan'],
					'gaji_potongan_kemacetan' => $data_hitung_lapangan['potongan_kemacetan'],
					'gaji_potongan_tidak_masuk' => $data_hitung_lapangan['potongan_absen'],
					'gaji_date_updated' => current_datetime_indo(),
				);

				// Jika Post Pinjam Bayar Tidak Kosong
				if ($data_hitung_lapangan['pinjaman_bayar'] > 0 && !empty($data_hitung_lapangan['pinjaman_id'])) {
					$this->PinjamanModel->update($data_hitung_lapangan['pinjaman_id'], ['pinjaman_status' => 'lunas']);

					$data['gaji_pinjaman_bayar'] = $data_hitung_lapangan['pinjaman_bayar'];
					array_push($values_subtract, intval($data_hitung_lapangan['pinjaman_bayar']));
				}

				// Jika Post Tabungan Masuk Tidak Kosong
				if (!empty($data_get['hitung_lapangan_tabungan_masuk'])) {
					array_push($values_subtract, intval($data_get['hitung_lapangan_tabungan_masuk']));

					$data['gaji_tabungan_masuk'] = intval($data_get['hitung_lapangan_tabungan_masuk']);

					if (!empty($data_hitung_lapangan['tabungan_id'])) {
						$arr_data_tabungan = array(
							'tabungan_jumlah' => intval($data_hitung_lapangan['tabungan_saat_ini']) + intval($data_get['hitung_lapangan_tabungan_masuk']),
							'tabungan_date_updated' => current_datetime_indo(),
						);

						$arr_data_riwayat_tabungan = array(
							'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
							'riwayat_id_tabungan' => $data_hitung_lapangan['tabungan_id'],
							'riwayat_tabungan_jumlah' => $data_get['hitung_lapangan_tabungan_masuk'],
							'riwayat_tabungan_status' => 'masuk'
						);

						$this->TabunganModel->update($data_hitung_lapangan['tabungan_id'], $arr_data_tabungan);
						$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
					} else {
						$kode_tabungan = 'TB-' . substr(time(), 4) . rand(11, 99);
						$arr_data_tabungan = array(
							'tabungan_id' => $kode_tabungan,
							'tabungan_karyawan_id' => $data_get['karyawan_id'],
							'tabungan_jumlah' => $data_get['hitung_lapangan_tabungan_masuk'],
							'tabungan_date_updated' => current_datetime_indo(),
						);

						$arr_data_riwayat_tabungan = array(
							'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
							'riwayat_id_tabungan' => $kode_tabungan,
							'riwayat_tabungan_jumlah' => $data_get['hitung_lapangan_tabungan_masuk'],
							'riwayat_tabungan_status' => 'masuk'
						);

						$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
						$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
					}
				}

				// Jika Post Tabungan Masuk Tidak Kosong
				if (!empty($data_get['hitung_lapangan_tabungan_keluar'])) {
					if (intval($data_get['hitung_lapangan_tabungan_keluar']) > intval($data_hitung_lapangan['tabungan_saat_ini'])) {
						$this->session->set_flashdata('alert', 'error');
						$this->session->set_flashdata('message', 'Saldo tabungan tidak cukup');
						redirect("gaji?month={$data_get['month_set']}");
					} else {
						array_push($values_add, $data_get['hitung_lapangan_tabungan_keluar']);

						$data['gaji_tabungan_keluar'] = $data_get['hitung_lapangan_tabungan_keluar'];

						if (!empty($data_hitung_lapangan['tabungan_id'])) {
							$arr_data_tabungan = array(
								'tabungan_jumlah' => intval($data_hitung_lapangan['tabungan_saat_ini']) - intval($data_get['hitung_lapangan_tabungan_keluar']),
								'tabungan_date_updated' => current_datetime_indo(),
							);

							$arr_data_riwayat_tabungan = array(
								'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
								'riwayat_id_tabungan' => $data_hitung_lapangan['tabungan_id'],
								'riwayat_tabungan_jumlah' => $data_get['hitung_lapangan_tabungan_keluar'],
								'riwayat_tabungan_status' => 'keluar'
							);

							$this->TabunganModel->update($data_hitung_lapangan['tabungan_id'], $arr_data_tabungan);
							$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
						} else {
							$kode_tabungan = 'TB-' . substr(time(), 4) . rand(11, 99);
							$arr_data_tabungan = array(
								'tabungan_id' => $kode_tabungan,
								'tabungan_karyawan_id' => $data_get['karyawan_id'],
								'tabungan_jumlah' => intval($data_hitung_lapangan['tabungan_saat_ini']) - intval($data_get['hitung_lapangan_tabungan_keluar']),
								'tabungan_date_updated' => current_datetime_indo(),
							);

							$arr_data_riwayat_tabungan = array(
								'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
								'riwayat_id_tabungan' => $kode_tabungan,
								'riwayat_tabungan_jumlah' => $data_get['hitung_lapangan_tabungan_keluar'],
								'riwayat_tabungan_status' => 'keluar'
							);

							$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
							// $arr_data_riwayat_tabungan['riwayat_id_tabungan'] = $data_tabungan_id;
							$this->TabunganModel->insert_riwayat($arr_data_riwayat_tabungan);
						}
					}
				}

				$values = [$values_add, $values_subtract];
				$operations = ['add', 'subtract'];
				$data['gaji_total'] = hitung_total($values, $operations);
				$check_record = $this->GajiModel->find($data_get['karyawan_id'], $month_split);

				if (!empty($check_record)) {
					$this->GajiModel->update($check_record['gaji_id'], $data);
				} else {
					$data['gaji_id'] = 'GJ-' . substr(time(), 2);
					$data['gaji_karyawan_id'] = $data_get['karyawan_id'];
					$this->GajiModel->insert($data);
				}
			}

			$this->db->trans_commit();
			$this->session->set_flashdata('alert', 'update');
			redirect("gaji?month={$data_get['month_set']}");
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$error_msg = "Database Error: " . $e->getMessage();
			$this->session->set_flashdata('message', $error_msg);
			redirect("gaji?month={$data_get['month_set']}");
		}
	}

	public function edit($id, $month)
	{
		$month_set = explode('-', $month);
		if ($month == 0) {
			$month_set = [0];
		}

		$data = $this->GajiModel->find($id, $month_set);
		echo json_encode($data);
	}

	public function slip($id, $month)
	{
		$month_set = explode('-', $month);

		$data = $this->GajiModel->find($id, $month_set);

		$data_absensi = $this->AbsensiModel->find($id, $month_set);

		$data_sum_potongan = $this->PotonganGajiModel->sumPotongan();
		$total_potongan = $data['karyawan_status'] == 'lapangan tetap' || $data['karyawan_status'] == 'lapangan training' ? $data_sum_potongan->total : 0;

		$lain_atas = 0;
		$data['gaji_tabungan_keluar'] ? $lain_atas += intval($data['gaji_tabungan_keluar']) : $lain_atas += 0;
		$data['gaji_uang_makan'] ? $lain_atas += intval($data['gaji_uang_makan']) : $lain_atas += 0;
		$data['gaji_transport'] ? $lain_atas += intval($data['gaji_transport']) : $lain_atas += 0;

		$total_atas = intval($data['gaji_pokok']) + intval($data['gaji_bonus']) + intval($lain_atas);
		$total_bawah = intval($data['gaji_pinjaman_bayar']) + intval($data['gaji_tabungan_masuk']) + intval($total_potongan) + intval($data['gaji_potongan_kemacetan']) + intval($data['gaji_potongan_tidak_masuk']);

		$output = [
			'slip_nama' => $data['karyawan_nama'],
			'slip_jabatan' => $data['karyawan_status'],
			'slip_bulan' => "{$data['gaji_bulan_ke']} - {$data['gaji_tahun_ke']}",
			'slip_hari' => $data_absensi->absensi_kehadiran . ' hari',
			'slip_gaji_pokok' => nominal($data['gaji_pokok']),
			'slip_gaji_bonus' => $data['gaji_bonus'] ? nominal($data['gaji_bonus']) : nominal(0),
			'slip_tabungan_keluar' => nominal($lain_atas),
			'slip_total_atas' => nominal($total_atas),
			'slip_pinjam' => $data['gaji_pinjaman_bayar'] ? nominal($data['gaji_pinjaman_bayar']) : nominal(0),
			'slip_tabungan' => $data['gaji_tabungan_masuk'] ? nominal($data['gaji_tabungan_masuk']) : nominal(0),
			'slip_potongan' => nominal($total_potongan),
			'slip_kemacetan' => $data['gaji_potongan_kemacetan'] ? nominal($data['gaji_potongan_kemacetan']) : nominal(0),
			'slip_tidak_masuk' => $data['gaji_potongan_tidak_masuk'] ? nominal($data['gaji_potongan_tidak_masuk']) : nominal(0),
			'slip_total_bawah' => nominal($total_bawah),
			'slip_sisa_gaji' => nominal($data['gaji_total'])
		];

		echo json_encode($output);
	}

	public function update()
	{
		$data_get = $this->input->post();

		$data_array = [
			'gaji_status' => $data_get['status_gaji']
		];

		$this->GajiModel->update($data_get['id'], $data_array);

		$this->session->set_flashdata('alert', 'update');
		redirect("gaji");
	}

	private function dd($data)
	{
		var_dump($data);
		die;
	}
}
