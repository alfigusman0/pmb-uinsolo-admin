<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SS_daftar extends CI_Model
{
	protected $table = 'viewd_kelulusan'; // Nama Tabel
	protected $column_order = array(
		"id_user",
		null, // Placeholder for ordering
		"nomor_peserta",
		"nim",
		"nama",
		"alias_jalur_masuk",
		"jurusan",
		"fakultas",
		"tahun",
		"daftar",
		"submit",
		"pembayaran",
		"pemberkasan",
	); // set column field database for datatable orderable

	protected $column_search = array(
		"nomor_peserta",
		"nim",
		"nama",
		"alias_jalur_masuk",
		"jurusan",
		"fakultas",
		"tahun",
		"daftar",
		"submit",
		"pembayaran",
		"pemberkasan",
	); // set column field database for datatable searchable

	protected $order = array('tahun' => 'DESC', 'id_user' => 'ASC'); // default order

	/**
	 * Get the datatables query with filters and ordering.
	 */
	private function _get_datatables_query()
	{
		$post = $this->input->post();

		// Custom filters
		$this->_apply_filter('nomor_peserta', 'like', $post);
		$this->_apply_filter('nim', 'like', $post);
		$this->_apply_filter('nama', 'like', $post);
		$this->_apply_filter('ids_jalur_masuk', 'where', $post);
		$this->_apply_filter('ids_fakultas', 'where', $post);
		$this->_apply_filter('kode_jurusan', 'where', $post);
		$this->_apply_filter('tahun', 'where', $post);
		$this->_apply_filter('daftar', 'where', $post);
		$this->_apply_filter('submit', 'where', $post);
		$this->_apply_filter('pembayaran', 'where', $post);
		$this->_apply_filter('pemberkasan', 'where', $post);

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
	private function _apply_filter($field, $type, $post)
	{
		if (!empty($post[$field])) {
			if ($type === 'like') {
				$this->db->like($field, $post[$field]);
			} else if ($type === 'where') {
				$this->db->where($field, $post[$field]);
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
