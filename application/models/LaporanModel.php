<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanModel extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findLaporan($bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji as sg');
		$this->db->join('sigaka_karyawan as sk', "sg.gaji_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('gaji_bulan_ke', $bulan[1]);
		$this->db->where('gaji_tahun_ke', $bulan[0]);
		$this->db->order_by('gaji_date_created', 'DESC');
		$this->db->order_by('gaji_date_updated', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}
}
