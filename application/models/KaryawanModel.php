<?php

defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan');
		$this->db->join('sigaka_pengguna', 'sigaka_pengguna.pengguna_id = sigaka_karyawan.karyawan_pengguna_id');
		// $this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function findAllByStatusTetap($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan');
		$this->db->where('karyawan_date_deleted', null);

		if (!empty($id)) {
			$this->db->where('karyawan_id', $id);
		} else {
			$this->db->where('karyawan_status', 'rekap tetap');
			$this->db->or_where('karyawan_status', 'lapangan tetap');
		}

		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function find($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan');
		$this->db->join('sigaka_pengguna', 'sigaka_pengguna.pengguna_id = sigaka_karyawan.karyawan_pengguna_id');
		// $this->db->join('sigaka_jabatan', 'sigaka_jabatan.jabatan_id = sigaka_karyawan.karyawan_jabatan_id');
		$this->db->where('karyawan_id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_karyawan', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('karyawan_id', $id);
		$this->db->update('sigaka_karyawan', $data);
		return $this->db->affected_rows();
	}

	public function delete($id, $data)
	{
		$this->db->where('karyawan_id', $id);
		$this->db->update('sigaka_karyawan', $data);
		return $this->db->affected_rows();
	}

	public function get_karyawan_id($id_pengguna)
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan');
		$this->db->where('karyawan_pengguna_id', $id_pengguna);

		$query = $this->db->get();
		return $query->row_array();
	}
}
