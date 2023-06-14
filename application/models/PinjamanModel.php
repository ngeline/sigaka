<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PinjamanModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from('sigaka_pinjaman as sp');
		$this->db->join('sigaka_karyawan as sk', "sp.pinjaman_karyawan_id = sk.karyawan_id", 'left');
		$this->db->order_by('pinjaman_date_created', 'DESC');
		$this->db->order_by('pinjaman_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function find($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_pinjaman as sp');
		$this->db->join('sigaka_karyawan as sk', "sp.pinjaman_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('pinjaman_id', $id);
		$this->db->order_by('pinjaman_date_created', 'DESC');
		$this->db->order_by('pinjaman_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->row();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_pinjaman', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('pinjaman_id', $id);
		$this->db->update('sigaka_pinjaman', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('pinjaman_id', $id);
		$this->db->delete('sigaka_pinjaman');
		return $this->db->affected_rows();
	}
}
