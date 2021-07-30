<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_categories()
    {
        $sql = "SELECT * FROM categories";
        return $this->db->query($sql)->result();
    }

}