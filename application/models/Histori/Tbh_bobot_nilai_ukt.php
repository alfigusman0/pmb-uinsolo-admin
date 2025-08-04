<?php

/**
 * PHP Models for Codeigniter
 *
 * @package         CodeIgniter
 * @subpackage      models
 * @category        models
 * @porting author  alfi.gusman.9f@gmail.com
 * @original author http://alfi-gusman.web.id
 * @updated         2025-06-19 16:16
 *
 * @version         4.2.1
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Tbh_bobot_nilai_ukt extends CI_Model
{
    protected $table = "tbh_bobot_nilai_ukt";

    /**
     * Create a new record
     *
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        if ($this->db->insert($this->table, $data)) {
            return array(
                'status' => false,
                'message' => '',
                'id' => $this->db->insert_id(),
            );
        } else {
            $error = $this->db->error();
            return array(
                'status' => true,
                'message' => $error['message'] ?? 'Database error',
            );
        }
    }

    /**
     * Insert multiple records using insert_batch with transaction support
     *
     * @param array $data
     * @return array
     * Example:
    $data = array(
        array(
            'column1' => 'value1',
            'column2' => 'value2',
            // ...
        ),
        array(
            'column1' => 'value3',
            'column2' => 'value4',
            // ...
        ),
        // ...
    );
     * how to use:
    $this->model->create_bulk($data);
     */
    public function create_bulk(array $data)
    {
        if (empty($data)) {
            return array(
                'status' => true,
                'message' => 'Tidak ada data yang diberikan untuk insert bulk',
            );
        }

        $this->db->trans_start();
        $this->db->insert_batch($this->table, $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $error = $this->db->error();
            return array(
                'status' => true,
                'message' => $error['message'] ?? 'Terjadi kesalahan saat bulk insert',
            );
        } else {
            return array(
                'status' => false,
                'message' => '',
                'inserted_rows' => count($data),
            );
        }
    }

    /**
     * Read records based on rules
     *
     * @param array $rules
     * @return CI_DB_result
     * Example:
    $rules = array(
        'database' => 'default',
        'select' => null,
        'order' => null,
        'limit' => null,
        'group_by' => null,
    );
     */
    public function read(array $rules)
    {
        $DB = $this->load->database($rules['database'] ?? 'default', TRUE);

        if (!empty($rules['select'])) {
            $DB->select($rules['select']);
        }
        if (!empty($rules['order'])) {
            $DB->order_by($rules['order']);
        }
        if (!empty($rules['limit'])) {
            if (is_array($rules['limit'])) {
                $DB->limit($rules['limit']['akhir'], $rules['limit']['awal']);
            } else {
                $DB->limit($rules['limit']);
            }
        }
        if (!empty($rules['group_by'])) {
            $DB->group_by($rules['group_by']);
        }

        return $DB->get($this->table);
    }

    /**
     * Update records based on rules
     *
     * @param array $rules
     * @return array
     * Example:
    $rules = array(
        'where' => null,
        'or_where' => null,
        'like' => null,
        'or_like' => null,
        'data' => null,
    );
     */
    public function update(array $rules)
    {
        if (!empty($rules['where'])) {
            $this->db->where($rules['where']);
        }
        if (!empty($rules['or_where'])) {
            $this->db->or_where($rules['or_where']);
        }
        if (!empty($rules['like'])) {
            $this->db->like($rules['like']);
        }
        if (!empty($rules['or_like'])) {
            $this->db->or_like($rules['or_like']);
        }

        if ($this->db->update($this->table, $rules['data'])) {
            return array(
                'status' => false,
                'message' => '',
            );
        } else {
            $error = $this->db->error();
            return array(
                'status' => true,
                'message' => $error['message'] ?? 'Database error',
            );
        }
    }

    /**
     * Update multiple records using update_batch with transaction support
     *
     * @param array $data
     * @param string $key_column
     * @return array
     * Example:
    $data = array(
        array(
            'id' => 1,
            'column1' => 'new_value1',
            'column2' => 'new_value2',
            // ...
        ),
        array(
            'id' => 2,
            'column1' => 'new_value3',
            'column2' => 'new_value4',
            // ...
        ),
        // ...
    );
     * how to use:
    $key_column = 'id'; // The column used as the key for the update
     * $this->model->update_bulk($data, $key_column);
     */
    public function update_bulk(array $data, string $key_column)
    {
        if (empty($data)) {
            return array(
                'status' => true,
                'message' => 'Tidak ada data untuk di-update',
            );
        }

        $this->db->trans_start();
        $this->db->update_batch($this->table, $data, $key_column);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $error = $this->db->error();
            return array(
                'status' => true,
                'message' => $error['message'] ?? 'Terjadi kesalahan saat bulk update',
            );
        } else {
            return array(
                'status' => false,
                'message' => '',
                'updated_rows' => count($data),
            );
        }
    }

    /**
     * Delete records based on rules
     *
     * @param array $rules
     * @return array
     * Example:
    $rules = array(
        'where' => null,
        'or_where' => null,
        'like' => null,
        'or_like' => null,
    );
     */
    public function delete(array $rules)
    {
        if (!empty($rules['where'])) {
            $this->db->where($rules['where']);
        }
        if (!empty($rules['or_where'])) {
            $this->db->or_where($rules['or_where']);
        }
        if (!empty($rules['like'])) {
            $this->db->like($rules['like']);
        }
        if (!empty($rules['or_like'])) {
            $this->db->or_like($rules['or_like']);
        }

        if ($this->db->delete($this->table)) {
            return array(
                'status' => false,
                'message' => '',
            );
        } else {
            $error = $this->db->error();
            return array(
                'status' => true,
                'message' => $error['message'] ?? 'Database error',
            );
        }
    }

    /**
     * Search records based on rules
     *
     * @param array $rules
     * @return CI_DB_result
     * Example:
    $rules = array(
        'database' => 'default',
        'select' => null,
        'where' => null,
        'or_where' => null,
        'like' => null,
        'or_like' => null,
        'order' => null,
        'limit' => null,
        'group_by' => null,
    );
     */
    public function search(array $rules)
    {
        $DB = $this->load->database($rules['database'] ?? 'default', TRUE);

        if (!empty($rules['select'])) {
            $DB->select($rules['select']);
        }
        if (!empty($rules['where'])) {
            $DB->where($rules['where']);
        }
        if (!empty($rules['or_where'])) {
            $DB->or_where($rules['or_where']);
        }
        if (!empty($rules['like'])) {
            $DB->like($rules['like']);
        }
        if (!empty($rules['or_like'])) {
            $DB->or_like($rules['or_like']);
        }
        if (!empty($rules['order'])) {
            $DB->order_by($rules['order']);
        }
        if (!empty($rules['limit'])) {
            if (is_array($rules['limit'])) {
                $DB->limit($rules['limit']['akhir'], $rules['limit']['awal']);
            } else {
                $DB->limit($rules['limit']);
            }
        }
        if (!empty($rules['group_by'])) {
            $DB->group_by($rules['group_by']);
        }

        return $DB->get($this->table);
    }

    /**
     * Get distinct records based on rules
     *
     * @param array $rules
     * @return CI_DB_result
     * Example:
    $rules = array(
        'database' => 'default',
        'select' => null,
        'where' => null,
        'or_where' => null,
        'like' => null,
        'or_like' => null,
        'order' => null,
        'group_by' => null,
    );
     */
    public function distinct(array $rules)
    {
        $DB = $this->load->database($rules['database'] ?? 'default', TRUE);

        $DB->distinct();
        if (!empty($rules['select'])) {
            $DB->select($rules['select']);
        }
        if (!empty($rules['where'])) {
            $DB->where($rules['where']);
        }
        if (!empty($rules['or_where'])) {
            $DB->or_where($rules['or_where']);
        }
        if (!empty($rules['like'])) {
            $DB->like($rules['like']);
        }
        if (!empty($rules['or_like'])) {
            $DB->or_like($rules['or_like']);
        }
        if (!empty($rules['order'])) {
            $DB->order_by($rules['order']);
        }
        if (!empty($rules['group_by'])) {
            $DB->group_by($rules['group_by']);
        }

        return $DB->get($this->table);
    }
}
