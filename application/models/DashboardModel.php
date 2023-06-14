<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function jumlah_karyawan()
	{
		$this->db->from('sigaka_karyawan');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function jumlah_pinjaman()
	{
		$this->db->from('sigaka_pinjaman');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function jumlah_absen()
	{
		$this->db->from('sigaka_absensi');
		$this->db->like('absensi_date_created', date('Y-m-d'));
		$query = $this->db->get();
		return $query->num_rows();
	}
}
