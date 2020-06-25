<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alldata extends CI_Model {

	public function gatData($table,$where)
	{
		$query = $this->db
						->where($where)
						->get($table);
		return $query->result_array();
	}

	public function updateData($table,$data,$where)
	{
		return $query = $this->db
						->where($where)
						->update($table,$data);
	}

}

/* End of file Alldata.php */
/* Location: .//C/Users/lenovo/AppData/Local/Temp/fz3temp-2/Alldata.php */