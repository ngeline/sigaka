<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PenggunaModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
	}

	public function get_user_account($user)
	{
		$query = $this->db->get_where('sigaka_pengguna', $user);
		return $query->row_array();
	}

	public function insert($data)
	{
		$this->db->insert('sigaka_pengguna', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->db->where('pengguna_id', $id);
		$this->db->update('sigaka_pengguna', $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('pengguna_id', $id);
		$this->db->delete('sigaka_pengguna');
		return $this->db->affected_rows();
	}

	public function find_username($username)
	{
		$this->db->select('*');
		$this->db->from('sigaka_pengguna');
		$this->db->where('pengguna_username', $username);
		$query = $this->db->get();
		return $query->num_rows();
	}
}
