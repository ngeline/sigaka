<?php

defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanController extends CI_Controller
{
	private $array_validation;

	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['KaryawanModel', 'PenggunaModel', 'JabatanModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);


		$this->array_validation = [
			[
				'field' => 'username',
				'label' => 'Username Pengguna',
				'rules' => 'required|callback_check_unique'
			],
			[
				'field' => 'password',
				'label' => 'Password Potongan',
				'rules' => 'required|min_length[8]'
			],
			[
				'field' => 'nama',
				'label' => 'Nama Karyawan',
				'rules' => 'required'
			],
			[
				'field' => 'tempat_lahir',
				'label' => 'Tempat Lahir',
				'rules' => 'required'
			],
			[
				'field' => 'tanggal_lahir',
				'label' => 'Tanggal Lahir',
				'rules' => 'required'
			],
			[
				'field' => 'alamat',
				'label' => 'Alamat Karyawan',
				'rules' => 'required'
			],
			[
				'field' => 'nomor_hp',
				'label' => 'Nomor HP Karyawan',
				'rules' => 'required|numeric|min_length[11]'
			],
			[
				'field' => 'tanggal_gabung',
				'label' => 'Tanggal Bergabung',
				'rules' => 'required'
			],
			[
				'field' => 'status',
				'label' => 'Status Karyawan',
				'rules' => 'required'
			],
		];
	}

	public function index()
	{
		$data = array(
			'karyawan' => $this->KaryawanModel->findAll(),
			// 'jabatan' => $this->JabatanModel->lihat_jabatan(),
			'title' => 'Karyawan'
		);
		$this->load->view('templates/header', $data);
		$this->load->view('backend/karyawan/index', $data);
		$this->load->view('templates/footer');
	}

	public function check_unique($value)
	{
		$data = $this->PenggunaModel->find_username($value);
		if ($data > 0) {
			$this->form_validation->set_message('check_unique', 'Username sudah digunakan');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	private function validation_form()
	{
		$this->form_validation->set_rules($this->array_validation);

		if ($this->form_validation->run() === FALSE) {
			$errors = $this->form_validation->error_array();
			$errorString = implode("<br>", $errors);

			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', $errorString);
			redirect('karyawan');
		}

		return true;
	}

	public function store()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data_pengguna = [
			'pengguna_username' => $data['username'],
			'pengguna_password' => md5($data['password']),
			'pengguna_nama' => $data['nama'],
			'pengguna_hak_akses' => 'karyawan',
		];

		$pengguna_id = $this->PenggunaModel->insert($array_data_pengguna);

		$array_data = [
			'karyawan_id' => 'PTG-' . substr(time(), 2),
			'karyawan_nama' => $data['nama'],
			'karyawan_tempat_lahir' => $data['tempat_lahir'],
			'karyawan_tanggal_lahir' => $data['tanggal_lahir'],
			'karyawan_alamat' => $data['alamat'],
			'karyawan_nomor_hp' => $data['nomor_hp'],
			'karyawan_tanggal_gabung' => $data['tanggal_gabung'],
			'karyawan_status' => $data['status'],
			'karyawan_pengguna_id' => $pengguna_id,
			// 'karyawan_jabatan_id' => $data['jabatan'],
			'karyawan_date_updated' => current_datetime_indo(),
		];

		$this->KaryawanModel->insert($array_data);

		$this->session->set_flashdata('alert', 'insert');
		redirect('karyawan');
	}

	public function show($id)
	{
		$this->PenggunaModel->update('1', ['pengguna_password' => md5('12345678')]);
		$data = $this->KaryawanModel->find($id);
		echo json_encode($data);
	}

	public function update()
	{
		$data = $this->input->post();

		$data_username = $this->PenggunaModel->find_username($data['username']);
		if ($data_username > 0) {
			unset($this->array_validation[0]);
		}

		if (empty($data['password'])) {
			unset($this->array_validation[1]);
		}

		$this->validation_form($data);

		$array_data_pengguna = [
			'pengguna_username' => $data['username'],
			'pengguna_nama' => $data['nama'],
			'pengguna_hak_akses' => 'karyawan',
		];

		if (!empty($data['password'])) {
			$array_data_pengguna['pengguna_password'] = md5($data['password']);
		}

		$this->PenggunaModel->update($data['id_pengguna'], $array_data_pengguna);

		$array_data = [
			'karyawan_nama' => $data['nama'],
			'karyawan_tempat_lahir' => $data['tempat_lahir'],
			'karyawan_tanggal_lahir' => $data['tanggal_lahir'],
			'karyawan_alamat' => $data['alamat'],
			'karyawan_nomor_hp' => $data['nomor_hp'],
			'karyawan_tanggal_gabung' => $data['tanggal_gabung'],
			'karyawan_status' => $data['status'],
			// 'karyawan_jabatan_id' => $data['jabatan'],
			'karyawan_date_updated' => current_datetime_indo(),
		];

		$this->KaryawanModel->update($data['id_karyawan'], $array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect('karyawan');
	}

	public function delete($id_karyawan, $id_pengguna)
	{
		$array_data = [
			'karyawan_date_deleted' => current_datetime_indo(),
		];

		$this->KaryawanModel->update($id_karyawan, $array_data);
		$this->PenggunaModel->delete($id_pengguna);

		$this->session->set_flashdata('alert', 'delete');
		redirect('karyawan');
	}

	public function ajaxIndex()
	{
		echo json_encode($this->KaryawanModel->lihat_karyawan());
	}
}
