<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SS_daya_tampung extends CI_Model
{
	protected $table = 'views_daya_tampung'; // Nama Tabel
	protected $column_order = array(
		"ids_daya_tampung",
		null, // Placeholder for ordering
		"date_created",
		"jenjang",
		"kode_jurusan",
		"jurusan",
		"fakultas",
		"kelas",
		"dt_awal",
		"daya_tampung",
		"afirmasi",
		"kuota",
		"grade",
		"nilai_min",
		"nilai_max",
		"status",
	); // set column field database for datatable orderable

	protected $column_search = array(
		"kode_jurusan",
		"jurusan",
		"fakultas",
		"kelas",
		"dt_awal",
		"daya_tampung",
		"afirmasi",
		"kuota",
		"grade",
		"nilai_min",
		"nilai_max",
		"status",
	); // set column field database for datatable searchable

	protected $order = array('daya_tampung' => 'ASC'); // default order

	/**
	 * Get the datatables query with filters and ordering.
	 */
	private function _get_datatables_query()
	{
		$post = $this->input->post();

		// Custom filters
		$this->_apply_filter('tahun', 'YEAR(date_created)', $post, 'where');
		$this->_apply_filter('ids_fakultas', 'ids_fakultas', $post, 'where');
		$this->_apply_filter('kode_jurusan', 'kode_jurusan', $post, 'where');
		$this->_apply_filter('dt_awal', 'dt_awal', $post, 'where');
		$this->_apply_filter('daya_tampung', 'daya_tampung', $post, 'where');
		$this->_apply_filter('afirmasi', 'afirmasi', $post, 'where');
		$this->_apply_filter('kuota', 'kuota', $post, 'where');
		$this->_apply_filter('grade', 'grade', $post, 'where');
		$this->_apply_filter('nilai_min', 'nilai_min', $post, 'where');
		$this->_apply_filter('nilai_max', 'nilai_max', $post, 'where');
		$this->_apply_filter('jenjang', 'jenjang', $post, 'where');

		$this->db->from($this->table);

		// Search functionality
		if (!empty($post['search']['value'])) {
			$this->db->group_start(); // Open bracket for OR conditions
			foreach ($this->column_search as $index => $item) {
				if ($index === 0) {
					$this->db->like($item, $post['search']['value']);
				} else {
					$this->db->or_like($item, $post['search']['value']);
				}
			}
			$this->db->group_end(); // Close bracket
		}

		// Order functionality
		if (isset($post['order'])) {
			$order_column = $this->column_order[$post['order']['0']['column']] ?? null;
			$order_dir = $post['order']['0']['dir'] ?? 'asc';
			if ($order_column) {
				$this->db->order_by($order_column, $order_dir);
			}
		} else if (isset($this->order)) {
			$order_key = key($this->order);
			$this->db->order_by($order_key, $this->order[$order_key]);
		}
	}

	/**
	 * Apply filter based on the type (like, where, etc.).
	 */
	private function _apply_filter($field, $db_field, $post, $type)
	{
		if (!empty($post[$field])) {
			if ($type === 'like') {
				$this->db->like($db_field, $post[$field]);
			} else if ($type === 'where') {
				$this->db->where($db_field, $post[$field]);
			}
		}
	}

	/**
	 * Fetch the datatables results.
	 */
	public function get_datatables()
	{
		$this->_get_datatables_query();
		$length = $this->input->post('length');
		$start = $this->input->post('start');
		if ($length != -1) {
			$this->db->limit($length, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Count the filtered results.
	 */
	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Count all results in the table.
	 */
	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
