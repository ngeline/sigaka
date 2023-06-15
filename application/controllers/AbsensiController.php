<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AbsensiController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['AbsensiModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);
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
			'absensi' => $this->AbsensiModel->findAll($id_karyawan, $month_set),
			'title' => 'Absensi',
			'date_set' => "{$month_set[0]}-{$month_set[1]}",
			'month_set' => $month_set[1],
			'year_set' => $month_set[0]
		);


		$this->load->view('templates/header', $data);
		$this->load->view('backend/absensi/index', $data);
		$this->load->view('templates/footer');
	}

	private function validation_form()
	{
		$array_validation = [
			[
				'field' => 'kehadiran',
				'label' => 'Jumlah Hadir',
				'rules' => 'numeric|less_than[30]'
			],
			[
				'field' => 'ketidakhadiran',
				'label' => 'Jumlah Sakit',
				'rules' => 'numeric|less_than[30]'
			],
		];

		$this->form_validation->set_rules($array_validation);

		if ($this->form_validation->run() === FALSE) {
			$errors = $this->form_validation->error_array();
			$errorString = implode("<br>", $errors);

			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', $errorString);
			redirect('absensi');
		}

		return true;
	}

	public function edit($id, $month)
	{
		$month_set = explode('-', $month);

		$data = $this->AbsensiModel->find($id, $month_set);

		$output = [
			'data' => $data,
			'month_set' => $month_set[1],
			'year_set' => $month_set[0]
		];

		echo json_encode($output);
	}

	public function update()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data = [
			'absensi_bulan_ke' => $data['bulan'],
			'absensi_tahun_ke' => $data['tahun'],
			'absensi_kehadiran' => $data['kehadiran'],
			'absensi_ketidakhadiran' => $data['ketidakhadiran'],
			'absensi_date_updated' => current_datetime_indo(),
		];

		if ($data['id'] == 0) {
			$array_data['absensi_id'] = 'ABS-' . substr(time(), 2);
			$array_data['absensi_karyawan_id'] = $data['karyawan'];
			$this->AbsensiModel->insert($array_data);
		} else {
			$this->AbsensiModel->update($data['id'], $array_data);
		}

		$this->session->set_flashdata('alert', 'update');
		redirect('absensi');
	}
}
