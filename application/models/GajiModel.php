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
		$this->db->join('sigaka_gaji as sg', "sk.karyawan_id = sg.gaji_karyawan_id AND sg.gaji_bulan_ke = {$bulan[1]} AND sg.gaji_tahun_ke = {$bulan[0]}", 'left');
		$this->db->where('karyawan_date_deleted', null);

		if (!empty($id_karyawan)) {
			$this->db->where('karyawan_id', $id_karyawan);
		}

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

	public function find($id_karyawan, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji as sg');
		$this->db->join('sigaka_karyawan as sk', "sg.gaji_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('gaji_bulan_ke', $bulan[1]);
		$this->db->where('gaji_tahun_ke', $bulan[0]);
		$this->db->where('gaji_karyawan_id', $id_karyawan);

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





	public function lihat_gaji()
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan');
		// $this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->order_by('karyawan_nama', 'ASC');
		$query = $this->db->get();

		return $query->result_array();
	}
	public function lihat_gaji_perorang($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_gaji as sg', 'sk.karyawan_id = sg.gaji_karyawan_id', 'left');
		// $this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->where('karyawan_id', $id);
		$this->db->order_by('gaji_bulan_ke', 'ASC');
		$query = $this->db->get();

		return $query->result_array();
	}

	public function lihat_satu_gaji($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji');
		$this->db->join('sigaka_karyawan', 'sigaka_karyawan.karyawan_id = sigaka_gaji.gaji_karyawan_id');
		$this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('gaji_karyawan_id', $id);
		$this->db->order_by('gaji_bulan_ke', 'DESC');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function lihat_satu_gaji_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji');
		$this->db->join('sigaka_karyawan', 'sigaka_karyawan.karyawan_id = sigaka_gaji.gaji_karyawan_id');
		$this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		//		$this->db->join('sigaka_pinjam', 'sigaka_pinjam.pinjam_karyawan_id = sigaka_gaji.gaji_karyawan_id');
		$this->db->where('gaji_id', $id);
		$this->db->order_by('gaji_bulan_ke', 'DESC');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function lihat_satu_gaji_pinjam($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_gaji');
		$this->db->join('sigaka_karyawan', 'sigaka_karyawan.karyawan_id = sigaka_gaji.gaji_karyawan_id');
		$this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('gaji_id', $id);
		$this->db->order_by('gaji_bulan_ke', 'DESC');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function tambah_gaji($data)
	{
		$this->db->insert('sigaka_gaji', $data);
		return $this->db->affected_rows();
	}

	public function update_gaji($id, $data)
	{
		$this->db->where('gaji_id', $id);
		$this->db->update('sigaka_gaji', $data);
		return $this->db->affected_rows();
	}
}
