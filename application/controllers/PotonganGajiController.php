<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PotonganGajiController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['PotonganGajiModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);
	}

	public function index()
	{
		$data = array(
			'potongan' => $this->PotonganGajiModel->findAll(),
			'title' => 'Potongan Gaji'
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/potongan_gaji/index', $data);
		$this->load->view('templates/footer');
	}

	private function validation_form()
	{
		$array_validation = [
			[
				'field' => 'potongan',
				'label' => 'Nama Potongan',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah',
				'label' => 'Jumlah Potongan',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'keterangan',
				'label' => 'Keterangan Potongan',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($array_validation);

		if ($this->form_validation->run() === FALSE) {
			$errors = $this->form_validation->error_array();
			$errorString = implode("<br>", $errors);

			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', $errorString);
			redirect('potongan_gaji');
		}

		return true;
	}

	public function store()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data = [
			'potongan_id' => 'PTG-' . substr(time(), 2),
			'potongan_nama' => $data['potongan'],
			'potongan_jumlah' => $data['jumlah'],
			'potongan_keterangan' => $data['keterangan'],
			'potongan_date_updated' => current_datetime_indo(),
		];

		$this->PotonganGajiModel->insert($array_data);

		$this->session->set_flashdata('alert', 'insert');
		redirect('potongan_gaji');
	}

	public function edit($id)
	{
		$data = $this->PotonganGajiModel->find($id);

		echo json_encode($data);
	}

	public function update()
	{
		$data = $this->input->post();

		$this->validation_form($data);

		$array_data = [
			'potongan_nama' => $data['potongan'],
			'potongan_jumlah' => $data['jumlah'],
			'potongan_keterangan' => $data['keterangan'],
			'potongan_date_updated' => current_datetime_indo(),
		];

		$this->PotonganGajiModel->update($data['id'], $array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect('potongan_gaji');
	}

	public function delete($id)
	{
		$array_data = [
			'potongan_date_deleted' => current_datetime_indo(),
		];

		$this->PotonganGajiModel->update($id, $array_data);

		$this->session->set_flashdata('alert', 'delete');
		redirect('potongan_gaji');
	}
}
