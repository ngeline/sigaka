<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StortingController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}

		$this->load->library('form_validation');
		$this->load->model(['StortingModel', 'KemacetanModel', 'GajiModel']);

		$this->load->helper(['nominal_helper', 'tgl_indo_helper', 'main_helper']);
	}

	public function index()
	{
		$get_data = $this->input->get('month');
		$month_set = $get_data ? explode('-', $get_data) : explode('-', date('Y-m'));

		$data = array(
			'title' => 'Storting',
			'date_set' => "{$month_set[0]}-{$month_set[1]}",
			'month_set' => $month_set[1],
			'year_set' => $month_set[0]
		);

		if ($this->session->has_userdata('session_karyawan_id')) {
			$id_karyawan = $this->session->userdata('session_karyawan_id');
			$data['storting'] = $this->StortingModel->findAll($id_karyawan, $month_set);
			$data['kemacetan'] = $this->KemacetanModel->find_kemacetan_karyawan($id_karyawan, $month_set);
		} else {
			$data['storting'] = $this->StortingModel->findAll_by_karyawan($month_set);
		}

		$this->load->view('templates/header', $data);
		$this->load->view('backend/storting/index', $data);
		$this->load->view('templates/footer');
	}

	private function validation_form()
	{
		$array_validation = [
			[
				'field' => 'tanggal',
				'label' => 'Tanggal Storting',
				'rules' => 'required'
			],
			[
				'field' => 'pinjaman',
				'label' => 'Jumlah Pinjaman',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'angsuran',
				'label' => 'Jumlah Angsuran',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'angsuran_hutang',
				'label' => 'Jumlah Angsuran Hutang',
				'rules' => 'required|numeric'
			],
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

	public function kemacetanStore()
	{
		$data = $this->input->post();

		$month_set = explode('-', $data['kemacetan_date']);
		$karyawan_id = $this->session->userdata('session_karyawan_id');

		$array_data = [
			'kemacetan_karyawan_id' => $karyawan_id,
			'kemacetan_bulan_ke' => $month_set[1],
			'kemacetan_tahun_ke' => $month_set[0],
			'kemacetan_jumlah' => $data['kemacetan'],
			'kemacetan_date_updated' => current_datetime_indo(),
		];

		$find_kemacetan = $this->KemacetanModel->find_kemacetan_karyawan($karyawan_id, $month_set);

		if (!empty($find_kemacetan)) {
			$this->KemacetanModel->update($find_kemacetan->kemacetan_id, $array_data);
		} else {
			$array_data['kemacetan_id'] = 'KMT-' . substr(time(), 2);
			$this->KemacetanModel->insert($array_data);
		}

		$this->session->set_flashdata('alert', 'update');
		redirect('storting');
	}

	public function store()
	{
		$data = $this->input->post();
		$karyawan_id = $this->session->userdata('session_karyawan_id');

		$tahun = date('Y', strtotime($data['tanggal']));
		$bulan = date('m', strtotime($data['tanggal']));

		$this->validation_form($data);

		$array_data = [
			'storting_id' => 'STR-' . substr(time(), 2),
			'storting_karyawan_id' => $karyawan_id,
			'storting_tanggal' => $data['tanggal'],
			'storting_bulan_ke' => $bulan,
			'storting_tahun_ke' => $tahun,
			'storting_pinjaman' => $data['pinjaman'],
			'storting_angsuran' => $data['angsuran'],
			'storting_angsuran_hutang' => $data['angsuran_hutang'],
			'storting_status' => 'pending',
			'storting_date_updated' => current_datetime_indo(),
		];

		$checking_gaji = $this->GajiModel->find($karyawan_id, [0 => $tahun, 1 => $bulan]);

		if (!empty($checking_gaji['gaji_id'])) {
			$this->session->set_flashdata('alert', 'error');
			$this->session->set_flashdata('message', 'Anda tidak diperbolehkan menambahkan storting, karena gaji sudah dihitung!');
			redirect('storting');
		}

		$this->StortingModel->insert($array_data);

		$this->session->set_flashdata('alert', 'insert');
		redirect('storting');
	}

	public function edit($id)
	{
		$data = $this->StortingModel->find($id);

		$output = [
			'data' => $data,
		];

		echo json_encode($output);
	}

	public function update()
	{
		$data = $this->input->post();

		$tahun = date('Y', strtotime($data['tanggal']));
		$bulan = date('m', strtotime($data['tanggal']));

		$this->validation_form($data);

		$array_data = [
			'storting_tanggal' => $data['tanggal'],
			'storting_bulan_ke' => $bulan,
			'storting_tahun_ke' => $tahun,
			'storting_pinjaman' => $data['pinjaman'],
			'storting_angsuran' => $data['angsuran'],
			'storting_angsuran_hutang' => $data['angsuran_hutang'],
			'storting_date_updated' => current_datetime_indo(),
		];

		$this->StortingModel->update($data['id'], $array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect('storting');
	}
	
	public function delete($id)
	{
		$this->StortingModel->delete($id);
		
		$this->session->set_flashdata('alert', 'delete');
		redirect('storting');
	}

	public function indexRiwayat()
	{
		$get_data = $this->input->get('month');
		$get_id_karyawan = $this->input->get('id');
		$month_set = $get_data ? explode('-', $get_data) : explode('-', date('Y-m'));

		$data = array(
			'storting' => $this->StortingModel->findAll($get_id_karyawan, $month_set),
			'kemacetan' => $this->KemacetanModel->find_kemacetan_karyawan($get_id_karyawan, $month_set),
			'title' => 'Storting Riwayat',
			'date_set' => "{$month_set[0]}-{$month_set[1]}",
			'month_set' => $month_set[1],
			'year_set' => $month_set[0],
			'id_karyawan' => $get_id_karyawan
		);

		$this->load->view('templates/header', $data);
		$this->load->view('backend/storting/riwayat/index', $data);
		$this->load->view('templates/footer');
	}

	public function updateRiwayat()
	{
		$data = $this->input->post();

		$array_data = [
			'storting_status' => $data['status'],
			'storting_date_updated' => current_datetime_indo(),
		];

		$this->StortingModel->update($data['id'], $array_data);

		$this->session->set_flashdata('alert', 'update');
		redirect("storting/riwayat?month={$data['return_date']}&id={$data['return_id_karyawan']}");
	}

	public function updateRiwayatSemua()
	{
		$date = $this->input->get('date');
		$id_karyawan = $this->input->get('id_karyawan');

		$date_array = explode('-', $date);

		$array_data = [
			'storting_status' =>'terverifikasi',
			'storting_date_updated' => current_datetime_indo(),
		];

		$this->StortingModel->updateSemua($id_karyawan, $array_data, $date_array);

		$this->session->set_flashdata('alert', 'update');
		redirect("storting/riwayat?month={$date}&id={$id_karyawan}");
	}

	public function updateRiwayatStatusValidasi()
	{
		$date = $this->input->get('date');
		$id_karyawan = $this->input->get('id_karyawan');

		$date_array = explode('-', $date);

		$array_data = [
			'kemacetan_status' =>'tervalidasi',
			'kemacetan_date_updated' => current_datetime_indo(),
		];

		$this->KemacetanModel->updateStatusByBulan($id_karyawan, $array_data, $date_array);

		$this->session->set_flashdata('alert', 'update');
		redirect("storting/riwayat?month={$date}&id={$id_karyawan}");
	}

	public function updateRiwayatStatusPending()
	{
		$date = $this->input->get('date');
		$id_karyawan = $this->input->get('id_karyawan');

		$date_array = explode('-', $date);

		$array_data = [
			'kemacetan_status' =>'pending',
			'kemacetan_date_updated' => current_datetime_indo(),
		];

		$this->KemacetanModel->updateStatusByBulan($id_karyawan, $array_data, $date_array);

		$this->session->set_flashdata('alert', 'update');
		redirect("storting/riwayat?month={$date}&id={$id_karyawan}");
	}
}
