<?php

defined('BASEPATH') or exit('No direct script access allowed');

class KemacetanModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll($bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_kemacetan as ss');
		$this->db->join('sigaka_karyawan as sk', "ss.kemacetan_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('kemacetan_bulan_ke', $bulan[1]);
		$this->db->where('kemacetan_tahun_ke', $bulan[0]);
		$this->db->order_by('kemacetan_date_created', 'DESC');
		$this->db->order_by('kemacetan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function find_kemacetan_karyawan($id, $bulan = [])
	{
		$this->db->select('kemacetan_id, kemacetan_jumlah');
		$this->db->from('sigaka_kemacetan as ss');
		$this->db->join('sigaka_karyawan as sk', "ss.kemacetan_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('kemacetan_bulan_ke', $bulan[1]);
		$this->db->where('kemacetan_tahun_ke', $bulan[0]);
		$this->db->where('kemacetan_karyawan_id', $id);
		$this->db->order_by('kemacetan_date_created', 'DESC');
		$this->db->order_by('kemacetan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->row();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_kemacetan', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('kemacetan_id', $id);
		$this->db->update('sigaka_kemacetan', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('kemacetan_id', $id);
		$this->db->delete('sigaka_kemacetan');
		return $this->db->affected_rows();
	}
}
