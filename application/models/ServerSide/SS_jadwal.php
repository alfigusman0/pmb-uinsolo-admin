<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SS_jadwal extends CI_Model
{
	protected $table = 'views_jadwal'; // Nama Tabel
	protected $column_order = array(
		"ids_jadwal",
		null, // Placeholder for ordering
		"tipe_ujian",
		"tanggal",
		"jam_awal",
		"area",
		"gedung",
		"ruangan",
		"quota",
		"status",
	); // set column field database for datatable orderable

	protected $column_search = array(
		"tipe_ujian",
		"tanggal",
		"jam_awal",
		"jam_akhir",
		"area",
		"gedung",
		"ruangan",
		"quota",
		"status",
	); // set column field database for datatable searchable

	protected $order = array('tanggal' => 'DESC', 'jam_awal' => 'ASC', 'jam_akhir' => 'ASC', 'ruangan' => 'ASC'); // default order

	/**
	 * Get the datatables query with filters and ordering.
	 */
	private function _get_datatables_query()
	{
		$post = $this->input->post();

		// Custom filters
		$this->_apply_filter('ids_program', 'ids_program', $post, 'where');
		$this->_apply_filter('ids_tipe_ujian', 'ids_tipe_ujian', $post, 'where');
		$this->_apply_filter('tanggal', 'tanggal', $post, 'where');
		$this->_apply_filter('jam_awal', 'jam_awal', $post, 'where');
		$this->_apply_filter('jam_akhir', 'jam_akhir', $post, 'where');
		$this->_apply_filter('ids_area', 'ids_area', $post, 'where');
		$this->_apply_filter('ids_gedung', 'ids_gedung', $post, 'where');
		$this->_apply_filter('ids_ruangan', 'ids_ruangan', $post, 'where');
		$this->_apply_filter('status', 'status', $post, 'where');

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
