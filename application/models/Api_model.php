<?php
class Api_model extends CI_Model
{
	function fetch_all()
	{
		$this->db->order_by('id', 'DESC');
		return $this->db->get('tbl_sample');
	}

	function insert_api($data)
	{
		//print_r($data);die;
		$datas = $data[0];
		//print_r($datas);die;
		$this->db->insert('tbl_sample', $datas);
	}

	function fetch_single_user($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('tbl_sample');
		return $query->result_array();
	}

	function emailchecker($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get('tbl_sample');
		return $query->result_array();
	}

	function update_api($user_id, $data)
	{
		$datas = $data[0];
		//print_r($datas);die;
		//print_r($user_id);die;
		//print_r($datas);die;
		$this->db->where('id', $user_id);
		$this->db->update('tbl_sample', $datas);
	}

	function delete_single_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('tbl_sample');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>