<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PotonganController extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('PotonganModel');
		$this->load->helper('nominal');
		if (!$this->session->has_userdata('session_id')) {
			$this->session->set_flashdata('alert', 'belum_login');
			redirect(base_url('login'));
		}
	}

	public function index(){
		$data = array(
			'potongan' => $this->PotonganModel->lihat_potongan(),
			'title' => 'potongan'
		);
		$this->load->view('templates/header',$data);
		$this->load->view('backend/potongan/index',$data);
		$this->load->view('templates/footer');
	}

	public function tambah(){
		if (isset($_POST['simpan'])){
			$generate = substr(time(), 5);
			$id = 'POT-' . $generate;
			$potongan = $this->input->post('potongan');
			$jumlah = $this->input->post('jumlah');
			$data = array(
				'potongan_id' => $id,
				'potongan_nama' => $potongan,
				'potongan_gaji' => $jumlah
			);
			$save = $this->PotonganModel->tambah_potongan($data);
			if ($save>0){
				$this->session->set_flashdata('alert', 'tambah_potongan');
				redirect('potongan');
			}
			else{
				redirect('potongan');
			}
		}
	}

	public function updateForm($id){
		$data = $this->PotonganModel->lihat_satu_potongan($id);
		echo json_encode($data);
	}

	public function update(){
		if (isset($_POST['update'])){
			$id = $this->input->post('id');
			$potongan = $this->input->post('potongan');
			$jumlah = $this->input->post('jumlah');
			$data = array(
				'potongan_nama' => $potongan,
				'potongan_jumlah' => $jumlah
			);
			$update = $this->PotonganModel->update_potongan($id,$data);
			if ($update > 0){
				$this->session->set_flashdata('alert', 'update_potongan');
				redirect('potongan');
			}
			else{
				redirect('potongan');
			}
		}
	}

	public function hapus($id){
		$hapus = $this->PotonganModel->hapus_potongan($id);
		if ($hapus > 0){
			$this->session->set_flashdata('alert', 'hapus_potongan');
			redirect('potongan');
		}else{
			redirect('potongan');
		}
	}
}
