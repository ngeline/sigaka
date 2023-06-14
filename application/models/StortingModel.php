<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StortingModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function findAll($id_karyawan, $bulan = [])
	{
		$this->db->select('*');
		$this->db->from('sigaka_storting as ss');
		$this->db->join('sigaka_karyawan as sk', "ss.storting_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('storting_bulan_ke', $bulan[1]);
		$this->db->where('storting_tahun_ke', $bulan[0]);
		$this->db->where('storting_karyawan_id', $id_karyawan);
		$this->db->order_by('storting_date_created', 'DESC');
		$this->db->order_by('storting_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function find($id)
	{
		$this->db->select('*');
		$this->db->from('sigaka_storting as ss');
		$this->db->join('sigaka_karyawan as sk', "ss.storting_karyawan_id = sk.karyawan_id", 'left');
		$this->db->where('storting_id', $id);
		$this->db->order_by('storting_date_created', 'DESC');
		$this->db->order_by('storting_date_updated', 'DESC');

		$query = $this->db->get();
		return $query->row();
	}

	public function findAll_by_karyawan($bulan = [])
	{
		$this->db->select('karyawan_date_created, karyawan_date_updated, karyawan_id, karyawan_nama AS nama, storting_bulan_ke AS bulan, storting_tahun_ke AS tahun, SUM(storting_pinjaman) AS total_pinjaman, SUM(storting_angsuran) AS total_angsuran, SUM(storting_angsuran_hutang) AS total_angsuran_hutang, kemacetan_jumlah');
		$this->db->from('sigaka_karyawan as sk');
		$this->db->join('sigaka_storting as ss', "sk.karyawan_id = ss.storting_karyawan_id AND ss.storting_bulan_ke = {$bulan[1]} AND ss.storting_tahun_ke = {$bulan[0]}", 'left');
		$this->db->join('sigaka_kemacetan as skt', "sk.karyawan_id = skt.kemacetan_karyawan_id AND skt.kemacetan_bulan_ke = {$bulan[1]} AND skt.kemacetan_tahun_ke = {$bulan[0]}", 'left');
		$this->db->where('karyawan_status', 'lapangan tetap');
		$this->db->or_where('karyawan_status', 'lapangan training');
		$this->db->where('karyawan_date_deleted', null);
		$this->db->group_by('karyawan_nama, storting_bulan_ke, storting_tahun_ke, karyawan_date_created, karyawan_date_updated, kemacetan_jumlah, karyawan_id');
		$this->db->order_by('karyawan_date_created', 'DESC');
		$this->db->order_by('karyawan_date_updated', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_storting', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data)
	{
		$this->db->where('storting_id', $id);
		$this->db->update('sigaka_storting', $data);
		return $this->db->affected_rows();
	
	}
	public function updateSemua($id, $data, $bulan = [])
	{
		$this->db->where('storting_bulan_ke', $bulan[1]);
		$this->db->where('storting_tahun_ke', $bulan[0]);
		$this->db->where('storting_karyawan_id', $id);
		$this->db->update('sigaka_storting', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('storting_id', $id);
		$this->db->delete('sigaka_storting');
		return $this->db->affected_rows();
	}
}
