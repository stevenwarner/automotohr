<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ip_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert data
     *
     * @param $table_name String
     * @param $data_array Array
     *
     * @return Array|Bool
     */
    function _insert($table_name, $data_array)
    {
        if (!sizeof($data_array)) return false;
        $insert = $this->db->insert($table_name, $data_array);
        if (!$insert) return false;
        return $this->db->insert_id();
    }

    /**
     * Check IP address
     *
     * @param $ip_address String
     *
     * @return Array|Bool
     */
    function check_ip($ip_address)
    {
        return (bool)$this->db
            ->from('blocked_ips')
            ->where('ip_address', $ip_address)
            ->count_all_results();
    }

    /**
     * Get blocked ip records
     *
     * @param $insert Integer
     * @param $offset Integer
     *
     * @return Array|Bool
     */
    function get_ips($inset, $offset)
    {
        //
        $result = $this
            ->db
            ->select('
            blocked_ips.ip_address,
            blocked_ips.created_at,
            blocked_ips.is_block,
            CONCAT(administrator_users.first_name, " ", administrator_users.last_name) AS admin_name
        ')
            ->from('blocked_ips')
            ->join('administrator_users', 'administrator_users.id = blocked_ips.admin_sid', 'left')
            ->limit($offset, $inset)
            ->get();
        // ;
        // _e($this->db->get_compiled_select(), true);
        //
        $ids_array = $result->result_array();
        $result   = $result->free_result();
        //
        if (!sizeof($ids_array)) return false;
        // Create a subquery
        $totalRecords = $this
            ->db
            ->from('blocked_ips')
            ->count_all_results();

        return array(
            'TotalRecords' => $totalRecords,
            // 'TotalRecords' => 50,
            'Records' => $ids_array
        );
    }
}
