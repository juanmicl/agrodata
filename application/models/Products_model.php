<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_products()
    {
        $sql = "SELECT * FROM products";
        return $this->db->query($sql)->result();
    }

    public function get_product($product_id)
    {
        $sql = "SELECT * FROM products WHERE `id` = ?";
        return $this->db->query($sql, [$product_id])->result()[0];
    }

    public function get_product_by_name($product)
    {
        $sql = "SELECT * FROM products WHERE `name` = ?";
        return $this->db->query($sql, [$product])->result()[0];
    }

    public function count_product($product_id)
    {
        $sql = "SELECT COUNT(*) c FROM `products` WHERE `id` = ?";
        return $this->db->query($sql, [$product_id])->row()->c;
    }

    public function count_product_by_name($product)
    {
        $sql = "SELECT COUNT(*) c FROM `products` WHERE `name` = ?";
        return $this->db->query($sql, [$product])->row()->c;
    }

}