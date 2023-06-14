<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TabunganController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['TabunganModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);
	}

	public function index()
	{
		$data = array(
			'tabungan' => $this->TabunganModel->findAll(),
			'title' => 'Tabungan',
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/tabungan/index', $data);
		$this->load->view('templates/footer');
	}

	public function show($id)
	{
		$data_riwayat = $this->TabunganModel->find_riwayat($id);

		$data = [];
		$no = 1;

		foreach ($data_riwayat as $value) {
			$row = [];

			$row[] = $no;
			$row[] = mediumdate_indo($value['riwayat_tabungan_date_created']);
			$row[] = $value['riwayat_tabungan_status'];
			$row[] = "Rp. {$value['riwayat_tabungan_jumlah']}";
			$data[] = $row;

			$no++;
		}

		$output = array(
            "data" => $data,
        );

		echo json_encode($output);
	}
}
