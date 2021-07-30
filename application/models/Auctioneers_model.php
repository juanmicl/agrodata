<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auctioneers_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_auctioneer($auctioneer_id)
    {
        $sql = "SELECT * FROM auctioneers WHERE `id` = ?";
        return $this->db->query($sql, [$auctioneer_id])->result()[0];
    }

    public function get_auctioneer_by_name($auctioneer)
    {
        $sql = "SELECT * FROM auctioneers WHERE `name` = ?";
        return $this->db->query($sql, [$auctioneer])->result()[0];
    }

    public function get_auctioneer_by_sub_name($auctioneer, $sub_name)
    {
        $sql = "SELECT * FROM auctioneers WHERE `name` = ? AND `sub_name` = ?";
        return $this->db->query($sql, [$auctioneer, $sub_name])->result()[0];
    }

    public function get_auctioneers()
    {
        $sql = "SELECT * FROM auctioneers";
        return $this->db->query($sql, [])->result();
    }

    public function count_auctioneer($autioneer_id)
    {
        $sql = "SELECT COUNT(*) c FROM `auctioneers` WHERE `id` = ?";
        return $this->db->query($sql, [$autioneer_id])->row()->c;
    }

    public function count_auctioneer_by_name($autioneer)
    {
        $sql = "SELECT COUNT(*) c FROM `auctioneers` WHERE `name` = ?";
        return $this->db->query($sql, [$autioneer])->row()->c;
    }

    public function count_auctioneer_sub_name($autioneer, $sub_name)
    {
        $sql = "SELECT COUNT(*) c FROM `auctioneers` WHERE `name` = ? AND `sub_name` = ?";
        return $this->db->query($sql, [$autioneer, $sub_name])->row()->c;
    }
}