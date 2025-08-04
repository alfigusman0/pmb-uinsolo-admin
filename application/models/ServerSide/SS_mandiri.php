<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SS_mandiri extends CI_Model
{
	protected $table = 'viewp_formulir'; // Nama Tabel
	protected $column_order = array(
		"idp_formulir",
		null, // Placeholder untuk duplikat
		null, // Placeholder untuk duplikat
		"nomor_peserta",
		"nama",
		"program",
		"tipe_ujian",
		"formulir",
		"pembayaran",
	); // set column field database for datatable orderable

	protected $column_search = array(
		"idp_formulir",
		"nomor_peserta",
		"nama",
		"program",
		"tipe_ujian",
		"formulir",
		"pembayaran",
	); // set column field database for datatable searchable

	protected $order = array('date_created' => 'DESC'); // default order

	/**
	 * Get the datatables query with filters and ordering.
	 */
	private function _get_datatables_query()
	{
		$post = $this->input->post();

		// Custom filters
		$this->_apply_filter('idp_formulir', 'idp_formulir', $post, 'like', function ($value) {
			return substr($value, 4); // Custom processing for idp_formulir
		});
		$this->_apply_filter('nomor_peserta', 'nomor_peserta', $post, 'like');
		$this->_apply_filter('nama', 'nama', $post, 'like');
		$this->_apply_filter('ids_program', 'ids_program', $post, 'where');
		$this->_apply_filter('ids_tipe_ujian', 'ids_tipe_ujian', $post, 'where');
		$this->_apply_filter('formulir', 'formulir', $post, 'where');
		$this->_apply_filter('pembayaran', 'pembayaran', $post, 'where');
		$this->_apply_filter('tahun', 'YEAR(date_created)', $post, 'where');

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
	private function _apply_filter($field, $db_field, $post, $type, $callback = null)
	{
		if (!empty($post[$field])) {
			$value = $post[$field];
			if ($callback) {
				$value = $callback($value); // Apply custom processing
			}
			if ($type === 'like') {
				$this->db->like($db_field, $value);
			} else if ($type === 'where') {
				$this->db->where($db_field, $value);
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
