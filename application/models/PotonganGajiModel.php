<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PotonganGajiModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll()
	{
		$this->db->select('*');
		$this->db->from('sigaka_potongan');
		$this->db->where('potongan_date_deleted', null);
		$this->db->order_by('potongan_date_created', 'DESC');
		$this->db->order_by('potongan_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function find($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_potongan');
		$this->db->where('potongan_id', $id);

		$query = $this->db->get();
		return $query->row();
	}

	public function sumPotongan()
	{
		$this->db->select('sum(potongan_jumlah) as total');
		$this->db->from('sigaka_potongan');

		$query = $this->db->get();
		return $query->row();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_potongan', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('potongan_id', $id);
		$this->db->update('sigaka_potongan', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('potongan_id', $id);
		$this->db->delete('sigaka_potongan');
		return $this->db->affected_rows();
	}
}
