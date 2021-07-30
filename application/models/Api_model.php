<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_logs($api_key)
    {
        $sql = "SELECT n_requests FROM api_logs WHERE api_key = ?";
        return $this->db->query($api_key)->result();
    }

    public function get_n_requests($api_key, $date)
    {
        $sql = "SELECT n_requests n FROM api_logs WHERE api_key = ? AND `date` = ?";
        return $this->db->query($sql, [$api_key, $date])->row()->n;
    }

    public function get_n_requests_today($api_key)
    {
        $sql = "SELECT n_requests n FROM api_logs WHERE api_key = ? AND `date` = CURRENT_DATE()";
        return $this->db->query($sql, [$api_key])->row()->n;
    }

    public function set_n_requests($api_key, $n)
    {
        $sql = "UPDATE api_logs SET n_requests = ? WHERE api_key = ?";
        return $this->db->query($sql, [$n, $api_key]);
    }

    public function update_api_key($new_api_key, $old_api_key)
    {
        $sql = "UPDATE api_logs SET api_key = ? WHERE api_key = ?";
        return $this->db->query($sql, [$new_api_key, $old_api_key]);
    }

    public function add_request($api_key)
    {
        $sql = "UPDATE api_logs SET n_requests = n_requests +1 WHERE api_key = ? AND `date` = CURRENT_DATE()";
        return $this->db->query($sql, [$api_key]);
    }

    public function insert_log($api_key, $ip, $date)
    {
        $sql = "INSERT INTO `api_logs` (`id`, `api_key`, `ip`, `n_requests`, `date`) VALUES 
        (NULL, ?, ?, 0, ?);";
        $this->db->query($sql, [$api_key, $ip, $date]);
        return $this->db->insert_id();
    }

    public function count_api_key($api_key, $date)
    {
        $sql = "SELECT COUNT(*) c FROM api_logs WHERE `api_key` = ? AND `date` = ?";
        return $this->db->query($sql, [$api_key, $date])->row()->c;
    }
}