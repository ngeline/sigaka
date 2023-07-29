<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AbsensiModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll($id, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		
		if (!empty($id)) {
			$this->db->join('sigaka_absensi as sa', "sk.karyawan_id = sa.absensi_karyawan_id", 'left');
			$this->db->where('karyawan_id', $id);
		} else {
			$this->db->join('sigaka_absensi as sa', "sk.karyawan_id = sa.absensi_karyawan_id AND sa.absensi_tahun_ke = {$bulan[0]} AND sa.absensi_bulan_ke = {$bulan[1]}", 'left');
		}
		
		$this->db->where('karyawan_date_deleted', null);
		$this->db->where("DATE_FORMAT(karyawan_tanggal_gabung, '%Y-%m') <=", "{$bulan[0]}-{$bulan[1]}");
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function find($id, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_absensi as sa', "sk.karyawan_id = sa.absensi_karyawan_id AND sa.absensi_tahun_ke = {$bulan[0]} AND sa.absensi_bulan_ke = {$bulan[1]}", 'left');
		$this->db->where('karyawan_id', $id);
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->row();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_absensi', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('absensi_id', $id);
		$this->db->update('sigaka_absensi', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('absensi_id', $id);
		$this->db->delete('sigaka_absensi');
		return $this->db->affected_rows();
	}
}
