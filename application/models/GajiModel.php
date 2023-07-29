<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GajiModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll($id_karyawan, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');

		if (!empty($id_karyawan)) {
			$this->db->join('sigaka_gaji as sg', "sk.karyawan_id = sg.gaji_karyawan_id", 'left');
			$this->db->where('karyawan_id', $id_karyawan);
		} else {
			$this->db->join('sigaka_gaji as sg', "sk.karyawan_id = sg.gaji_karyawan_id AND sg.gaji_bulan_ke = {$bulan[1]} AND sg.gaji_tahun_ke = {$bulan[0]}", 'left');
		}

		$this->db->where('karyawan_date_deleted', null);
		$this->db->where("DATE_FORMAT(karyawan_tanggal_gabung, '%Y-%m') <=", "{$bulan[0]}-{$bulan[1]}");
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function hitungGajiRekap($id_karyawan, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_absensi as sa', "sk.karyawan_id = sa.absensi_karyawan_id AND sa.absensi_bulan_ke = {$bulan[1]} AND sa.absensi_tahun_ke = {$bulan[0]}", 'left');
		$this->db->join('sigaka_tabungan as st', "sk.karyawan_id = st.tabungan_karyawan_id", 'left');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->where('karyawan_id', $id_karyawan);
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();

		return $query->row_array();
	}

	public function hitungGajiLapangan($id_karyawan, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_absensi as sa', "sk.karyawan_id = sa.absensi_karyawan_id AND sa.absensi_bulan_ke = {$bulan[1]} AND sa.absensi_tahun_ke = {$bulan[0]}", 'left');
		$this->db->join('sigaka_tabungan as st', "sk.karyawan_id = st.tabungan_karyawan_id", 'left');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->where('karyawan_id', $id_karyawan);
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();

		return $query->row_array();
	}

	public function pinjamanBayarByIdKaryawan($id_karyawan)
	{
		$this->db->select('pinjaman_id, pinjaman_jumlah, pinjaman_date_created');
		$this->db->from('sigaka_pinjaman');
		$this->db->where('pinjaman_karyawan_id', $id_karyawan);
		$this->db->where('pinjaman_status', 'terhutang');
		$this->db->order_by('pinjaman_date_created', 'DESC');

		$query = $this->db->get();

		return $query->row_array();
	}

	public function find($id, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji as sg');
		$this->db->join('sigaka_karyawan as sk', "sg.gaji_karyawan_id = sk.karyawan_id", 'left');

		if (count($bulan) > 1) {
			$this->db->where('gaji_bulan_ke', $bulan[1]);
			$this->db->where('gaji_tahun_ke', $bulan[0]);
			$this->db->where('gaji_karyawan_id', $id);
		} else {
			$this->db->where('gaji_id', $id);
		}

		$query = $this->db->get();

		return $query->row_array();
	}

	public function hitungStorting($id_karyawan, $bulan = [])
	{
		$this->db->select("sum(storting_pinjaman) as pinjaman, sum(storting_angsuran) as angsuran, sum(storting_angsuran_hutang) as angsuran_hutang, AVG(storting_angsuran) AS avg_angsuran");
		$this->db->from('sigaka_storting');
		$this->db->where('storting_bulan_ke', $bulan[1]);
		$this->db->where('storting_tahun_ke', $bulan[0]);
		$this->db->where('storting_karyawan_id', $id_karyawan);
		$this->db->where('storting_status', 'terverifikasi');

		$query = $this->db->get();

		return $query->row_array();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_gaji', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('gaji_id', $id);
		$this->db->update('sigaka_gaji', $data);
		return $this->db->affected_rows();
	}
}
