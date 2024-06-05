<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
	public $db;
	public function __construct()
	{
		$db = \Config\Database::connect();
		$this->db = $db;
	}


	public function insert_data($data = array())
	{
		$this->db->table($this->table)->insert($data);
		return $this->db->insertID();
	}



	public function get_all_details($table, $array)
	{

		$builder = $this->db->table($table);
		$builder->select('*');
		$builder->where($array);
		$query = $builder->get();
		$result = $query->getResultArray();
		return $result;


	}


	public function get_selected_fields($table, $array, $fields)
	{

		$builder = $this->db->table($table);
		$builder->select($fields);
		$builder->where($array);
		$query = $builder->get();
		$result = $query->getResultArray();
		return $result;


	}


	public function get_all_counts($table, $array)
	{

		$builder = $this->db->table($table);
		$builder->select('*');
		$builder->where($array);
		$query = $builder->get();
		$result = $builder->countAllResults();
		return !empty($result) ? $result : 0;


	}


	public function update_data($table, $data = null, $whereCondition = null): bool
	{

		$builder = $this->db->table($table)->update($data, $whereCondition);

		return $builder;
	}

	public function get_distinct_data($table, $array, $fields)
	{

		$builder = $this->db->table($table);
		$builder->select($fields);
		$builder->distinct();
		$builder->where($array);
		$query = $builder->get();
		$result = $query->getResultArray();
		return $result;


	}



}

