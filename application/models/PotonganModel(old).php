<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PotonganModel extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function lihat_potongan(){
		$this->db->select('*');
		$this->db->from('sigaka_potongan');
		$this->db->order_by('potongan_date','DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function tambah_potongan($data){
		$this->db->insert('sigaka_potongan', $data);
		return $this->db->affected_rows();
	}

	public function lihat_satu_potongan($id){
		$this->db->select('*');
		$this->db->from('sigaka_potongan');
		$this->db->where('potongan_id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function update_potongan($id,$data){
		$this->db->where('potongan_id',$id);
		$this->db->update('sigaka_potongan',$data);
		return $this->db->affected_rows();
	}

	public function hapus_potongan($id){
		$this->db->where('potongan_id', $id);
		$this->db->delete('sigaka_potongan');
		return $this->db->affected_rows();
	}


}
