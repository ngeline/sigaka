<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GajiController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$model = array('GajiModel', 'PinjamanModel', 'KaryawanModel', 'TabunganModel', 'AbsensiModel', 'PotonganGajiModel');
		$helper = array('tgl_indo', 'nominal', 'main_helper');
		$this->load->model($model);
		$this->load->helper($helper);
		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}
	}

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

	public function index()
	{
		$get_data = $this->input->get('month');
		$month_set = $get_data ? explode('-', $get_data) : explode('-', date('Y-m'));

		$id_karyawan = '';
		if ($this->session->has_userdata('session_karyawan_id')) {
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
		if ($status == 'rekap training' || $status == 'rekap tetap') {
			$data = $this->hitung_gaji_rekap($id_karyawan, $month);
		} else {
			$data = $this->hitung_gaji_rekap($id_karyawan, $month);
		}

		echo json_encode($data);
	}

	public function store_hitung()
	{
		$data_get = $this->input->post();

		$month_split = explode('-', $data_get['month_set']);

		$this->db->trans_start();

		try {

			if ($data_get['karyawan_status'] == 'rekap training' || $data_get['karyawan_status'] == 'rekap tetap') {
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
						$arr_data_tabungan = array(
							'tabungan_id' => 'TB-' . substr(time(), 4) . rand(11, 99),
							'tabungan_karyawan_id' => $data_get['karyawan_id'],
							'tabungan_jumlah' => $data_get['hitung_rekap_tabungan_masuk'],
							'tabungan_date_updated' => current_datetime_indo(),
						);

						$arr_data_riwayat_tabungan = array(
							'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
							'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_masuk'],
							'riwayat_tabungan_status' => 'masuk'
						);

						$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
						$arr_data_riwayat_tabungan['riwayat_id_tabungan'] = $data_tabungan_id;
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
							$arr_data_tabungan = array(
								'tabungan_id' => 'TB-' . substr(time(), 4) . rand(11, 99),
								'tabungan_karyawan_id' => $data_get['karyawan_id'],
								'tabungan_jumlah' => intval($data_hitung_rekap['tabungan_saat_ini']) - intval($data_get['hitung_rekap_tabungan_keluar']),
								'tabungan_date_updated' => current_datetime_indo(),
							);

							$arr_data_riwayat_tabungan = array(
								'riwayat_tabungan_id' => 'RTB-' . substr(time(), 4) . rand(11, 99),
								'riwayat_id_tabungan' => $data_hitung_rekap['tabungan_id'],
								'riwayat_tabungan_jumlah' => $data_get['hitung_rekap_tabungan_keluar'],
								'riwayat_tabungan_status' => 'keluar'
							);

							$data_tabungan_id = $this->TabunganModel->insert($arr_data_tabungan);
							$arr_data_riwayat_tabungan['riwayat_id_tabungan'] = $data_tabungan_id;
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

				// $total = hitung_total($values, $operations);
				// $this->dd(($data_get['hitung_rekap_pinjam_ambil']));
			} else {
				$this->dd('pepek');
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

		$data = $this->GajiModel->find($id, $month_set);

		echo json_encode($data);
	}

	public function slip($id, $month)
	{
		$month_set = explode('-', $month);

		$data = $this->GajiModel->find($id, $month_set);

		$data_absensi = $this->AbsensiModel->find($id, $month_set);

		$data_sum_potongan = $this->PotonganGajiModel->sumPotongan();
		$total_potongan = $data['karyawan_status'] == 'lapangan tetap' || $data['karyawan_status'] == 'lapangan_training' ? $data_sum_potongan->total : 0;

		$lain_atas = 0;
		$data['gaji_tabungan_keluar'] ? $lain_atas += intval($data['gaji_tabungan_keluar']) : $lain_atas += 0;
		$data['gaji_uang_makan'] ? $lain_atas += intval($data['gaji_uang_makan']) : $lain_atas += 0;
		$data['gaji_transport'] ? $lain_atas += intval($data['gaji_transport']) : $lain_atas += 0;

		$total_atas = intval($data['gaji_pokok']) + intval($data['gaji_bonus']) + intval($lain_atas);
		$total_bawah = intval($data['gaji_tabungan_masuk']) + intval($total_potongan) + intval($data['gaji_potongan_kemacetan']) + intval($data['gaji_potongan_tidak_masuk']);

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

	// public function detail($id)
	// {
	// 	$data = array(
	// 		'gaji' => $this->GajiModel->lihat_gaji_perorang($id),
	// 		'title' => 'Gaji'
	// 	);

	// 	// var_dump($id);
	// 	// die;

	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('backend/gaji/detail', $data);
	// 	$this->load->view('templates/footer');
	// }

	// public function lihat($id)
	// {
	// 	$data = $this->GajiModel->lihat_satu_gaji_by_id($id);
	// 	echo json_encode($data);
	// }

	// public function pinjam($id)
	// {
	// 	$data = $this->GajiModel->lihat_satu_gaji_pinjam($id);
	// 	echo json_encode($data);
	// }

	// public function bayar($id)
	// {
	// 	$data = array(
	// 		'gaji_status' => 'sudah'
	// 	);

	// 	$save = $this->GajiModel->update_gaji($id, $data);

	// 	if ($save > 0) {
	// 		$gaji = $this->GajiModel->lihat_satu_gaji_pinjam($id);
	// 		if ($gaji != null) {
	// 			if (($gaji['pinjam_jumlah'] - $gaji['pinjam_bayar']) > 500000) {
	// 				$dataGaji = array(
	// 					'gaji_bayar_pinjaman' => 500000
	// 				);
	// 				$this->GajiModel->update_gaji($id, $dataGaji);
	// 				$dataPinjam = array(
	// 					'pinjam_bayar' => $gaji['pinjam_bayar'] + 500000
	// 				);
	// 				$this->PinjamanModel->update_pinjaman($gaji['pinjam_id'], $dataPinjam);
	// 			} else {
	// 				$bayar = $gaji['pinjam_jumlah'] - $gaji['pinjam_bayar'];
	// 				$dataGaji = array(
	// 					'gaji_bayar_pinjaman' => $bayar
	// 				);
	// 				$this->GajiModel->update_gaji($id, $dataGaji);
	// 				$dataPinjam = array(
	// 					'pinjam_bayar' => $gaji['pinjam_bayar'] + $bayar
	// 				);
	// 				$this->PinjamanModel->update_pinjaman($gaji['pinjam_id'], $dataPinjam);
	// 			}
	// 		}
	// 		$this->session->set_flashdata('alert', 'update_gaji');
	// 		redirect('gaji');
	// 	} else {
	// 		redirect('gaji');
	// 	}
	// }
}
