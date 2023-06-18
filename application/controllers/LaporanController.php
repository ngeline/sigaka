<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LaporanController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$model = array('LaporanModel', 'GajiModel');
		$helper = array('tgl_indo', 'nominal', 'main_helper');
		$this->load->model($model);
		$this->load->helper($helper);
		$this->load->library('Pdfgenerator');
		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}
	}

	public function index()
	{
		$get_data = $this->input->get('month');
		$month_set = $get_data ? explode('-', $get_data) : explode('-', date('Y-m'));

		$data = array(
			'laporan' => $this->LaporanModel->findLaporan($month_set),
			'title' => 'Laporan',
			'date_set' => "{$month_set[0]}-{$month_set[1]}",
			'month_set' => $month_set[1],
			'year_set' => $month_set[0]
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/laporan/index', $data);
		$this->load->view('templates/footer');
	}

	public function cetak($bulan)
	{
		$month_set = explode('-', $bulan);

		$data = array(
			'title' => 'Primer Koperasi Pepabri',
			'sub_title' => 'Jln Pamenang 1 Katang Kabupaten Kediri',
			'laporan' => $this->LaporanModel->findLaporan($month_set),
			'bulan' => bulan($month_set[1]),
			'tahun' => $month_set[0]
		);

		// Membaca template atau view yang ingin diekspor ke PDF
		$html = $this->load->view('backend/laporan/pdf_template', $data, TRUE);

		// Generate PDF
		$this->pdfgenerator->generate($html, 'example', TRUE, 'A4', 'landscape');
	}
}
