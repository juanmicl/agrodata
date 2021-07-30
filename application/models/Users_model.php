<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_user_rank($username)
    {
        $sql = "SELECT `rank` FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->row_array()['rank'];
    }

    public function get_user($token)
    {
        $sql = "SELECT * FROM users WHERE token = ?";
        return $this->db->query($sql, [$token])->result()[0];
    }

    public function get_user_by_username($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->row_array();
    }

    public function get_user_ban($id)
    {
        $sql = "SELECT * FROM bans WHERE `user_id` = ?";
        return $this->db->query($sql, [$id])->row_array();
    }

    public function get_username($id)
    {
        $sql = "SELECT username n FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])->row()->n;
    }

    public function get_password($username)
    {
        $sql = "SELECT `password` p FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->row()->p;
    }

    public function get_api_key($id)
    {
        $sql = "SELECT api_key k FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])->row()->k;
    }

    public function insert_user($username, $hash, $token, $email, $ip)
    {
        $sql = "INSERT INTO `users` (`id`, `username`, `password`, `token`, `mail`, `mail_verified`, `ip_register`, `ip_current`) VALUES 
        (NULL, ?, ?, ?, ?, '0', ?, ?);";
        $this->db->query($sql, [$username, $hash, $token, $email, $ip, $ip]);
        return $this->db->insert_id();
    }

    public function set_token($uid, $token)
    {
        $sql = "UPDATE `users` SET `token` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$token, $uid]);
    }

    public function set_api_key($uid, $api_key)
    {
        $sql = "UPDATE `users` SET `api_key` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$api_key, $uid]);
    }

    public function update_password($uid, $pass)
    {
        $sql = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$pass, $uid]);
    }

    public function count_user($username)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `username` = ?";
        return $this->db->query($sql, [$username])->row()->c;
    }

    public function count_banned($token)
    {
        $sql = "SELECT COUNT(*) c FROM `bans` INNER JOIN `users` ON bans.user_id = users.id WHERE users.token = ? AND bans.ban_expire > UNIX_TIMESTAMP()";
        return $this->db->query($sql, [$token])->row()->c;
    }

    public function count_email($email)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `mail` = ?";
        return $this->db->query($sql, [$email])->row()->c;
    }

    public function count_token($token)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `token` = ?";
        return $this->db->query($sql, [$token])->row()->c;
    }

    public function count_api_key($api_key)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `api_key` = ?";
        return $this->db->query($sql, [$api_key])->row()->c;
    }
}