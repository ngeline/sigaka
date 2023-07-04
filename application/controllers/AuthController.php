<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$model = array('PenggunaModel', 'KaryawanModel');
		$this->load->model($model);
	}

	public function index()
	{
		$this->load->view('backend/auth/login');
	}

	public function login()
	{
		if ($this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'sudah_login');
			redirect(base_url('dashboard'));
		}
		if (isset($_POST['login'])) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$data = array(
				'pengguna_username' => $username,
				'pengguna_password' => md5($password)
			);
			$pengguna = $this->PenggunaModel->get_user_account($data);
			if ($pengguna != null) {
				$session = array(
					'session_id' => $pengguna['pengguna_id'],
					'session_username' => $pengguna['pengguna_username'],
					'session_nama' => $pengguna['pengguna_nama'],
					'session_foto' => $pengguna['pengguna_foto'],
					'session_hak_akses' => $pengguna['pengguna_hak_akses']
				);

				if ($pengguna['pengguna_hak_akses'] == 'karyawan') {
					$data_karyawan = $this->KaryawanModel->get_karyawan_id($pengguna['pengguna_id']);
					$session['session_karyawan_id'] = $data_karyawan['karyawan_id'];
					$session['session_karyawan_status'] = $data_karyawan['karyawan_status'];
				} else {
					$session['session_karyawan_id'] = null;
					$session['session_karyawan_status'] = null;
				}

				$this->session->set_flashdata('alert', 'login_sukses');
				$this->session->set_userdata($session);
				redirect(base_url('dashboard'));
			} else {
				$this->session->set_flashdata('alert', 'login_gagal');
				redirect(base_url('login'));
			}
		} else {
			$data = array(
				'title' => 'Login'
			);
			$this->load->view('backend/auth/login', $data);
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

	public function ubahPassword()
	{
		$data = $this->input->post();

		if ($data['password'] !== $data['confirm_password']) {
			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', 'Konfirmasi sandi tidak sama dengan sandi baru');
			redirect($_SERVER['HTTP_REFERER']);
		}
		
		$array_data_pengguna = [
			'pengguna_password' => md5($data['password']),
		];
		
		$this->PenggunaModel->update($this->session->userdata('session_id'), $array_data_pengguna);
		$this->session->set_flashdata('alert', 'update');
		redirect($_SERVER['HTTP_REFERER']);
	}
}
