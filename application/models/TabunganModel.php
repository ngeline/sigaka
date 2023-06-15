<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TabunganModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_tabungan as st', "sk.karyawan_id = st.tabungan_karyawan_id", 'left');
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
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_tabungan as st', "sk.karyawan_id = st.tabungan_karyawan_id", 'left');
		$this->db->where('karyawan_id', $id);
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->row();
	}

	public function find_riwayat($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_tabungan_riwayat as st');
		$this->db->where('riwayat_id_tabungan', $id);
		$this->db->order_by('riwayat_tabungan_date_created', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_tabungan', $data);
		return $this->db->insert_id();
	}

	public function insert_riwayat($data)
	{
		$this->db->insert('sigaka_tabungan_riwayat', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('tabungan_id', $id);
		$this->db->update('sigaka_tabungan', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('tabungan_id', $id);
		$this->db->delete('sigaka_tabungan');
		return $this->db->affected_rows();
	}
}
