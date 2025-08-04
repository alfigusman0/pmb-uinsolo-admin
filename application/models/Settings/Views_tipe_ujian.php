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

class Views_tipe_ujian extends CI_Model
{
    protected $table = "views_tipe_ujian";

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
