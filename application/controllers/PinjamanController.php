<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PinjamanController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['PinjamanModel','KaryawanModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);
	}

	public function index()
	{
		$id_karyawan = '';
		if ($this->session->has_userdata('session_karyawan_id')) {
			$id_karyawan = $this->session->userdata('session_karyawan_id');
		}

		$data = array(
			'pinjaman' => $this->PinjamanModel->findAll($id_karyawan),
			'karyawan' => $this->KaryawanModel->findAllByStatusTetap($id_karyawan),
			'title' => 'Pinjaman',
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/pinjaman/index', $data);
		$this->load->view('templates/footer');
	}

	private function validation_form()
	{
		$array_validation = [
			[
				'field' => 'karyawan',
				'label' => 'Nama Karyawan',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah',
				'label' => 'Jumlah Pinjaman',
				'rules' => 'required|numeric|less_than_equal_to[300000]'
			],
			[
				'field' => 'deskripsi',
				'label' => 'Deskripsi Pinjaman',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($array_validation);

		if ($this->form_validation->run() === FALSE) {
			$errors = $this->form_validation->error_array();
			$errorString = implode("<br>", $errors);

			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', $errorString);
			redirect('pinjaman');
		}

		return true;
	}

	public function store()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data = [
			'pinjaman_id' => 'PJ-' . substr(time(), 2),
			'pinjaman_karyawan_id' => $data['karyawan'],
			'pinjaman_jumlah' => $data['jumlah'],
			// 'pinjaman_status' => 'terhutang',
			'pinjaman_deskripsi' => $data['deskripsi'],
			'pinjaman_date_updated' => current_datetime_indo(),
		];

		$check_record = $this->PinjamanModel->findByKaryawan($data['karyawan']);

		if (!empty($check_record)) {
			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', 'Sudah ada pinjaman di bulan ini');
			redirect('pinjaman');	
		}

		$this->PinjamanModel->insert($array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect('pinjaman');
	}

	public function edit($id)
	{
		$data = $this->PinjamanModel->find($id);

		$output = [
			'data' => $data,
		];

		echo json_encode($output);
	}

	public function update()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data = [
			'pinjaman_karyawan_id' => $data['karyawan'],
			'pinjaman_jumlah' => $data['jumlah'],
			'pinjaman_deskripsi' => $data['deskripsi'],
			'pinjaman_date_updated' => current_datetime_indo(),
		];

		$this->PinjamanModel->update($data['id'], $array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect('pinjaman');
	}

	public function validasi()
	{
		$data_get = $this->input->post();

		$this->PinjamanModel->update($data_get['id'], ['pinjaman_status' => $data_get['validasi_status_peminjaman']]);

		$this->session->set_flashdata('alert', 'update');
		redirect('pinjaman');
	}
}
