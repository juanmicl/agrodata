<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prices_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_prices_by_product_id($product_id, $date = null)
    {
        if ($date == null) {
            $sql = "SELECT auction_prices.*, auctions.*, auctioneers.name auctioneer_name, auctioneers.sub_name auctioneer_sub_name, auctioneers.img auctioneer_img, products.name product_name, products.img product_img FROM auction_prices INNER JOIN auctions ON auction_prices.auction_id = auctions.id INNER JOIN products ON auction_prices.product_id = products.id INNER JOIN auctioneers ON auctions.auctioneer_id = auctioneers.id WHERE auction_prices.product_id = ? AND auctions.created_at = (SELECT MAX(created_at) FROM auctions INNER JOIN auction_prices ON auctions.id = auction_prices.auction_id WHERE auction_prices.product_id = ?)";
            return $this->db->query($sql, [$product_id, $product_id])->result();
        } else {
            $sql = "SELECT auction_prices.*, auctions.*, auctioneers.name auctioneer_name, auctioneers.sub_name auctioneer_sub_name, auctioneers.img auctioneer_img, products.name product_name, products.img product_img FROM auction_prices INNER JOIN auctions ON auction_prices.auction_id = auctions.id INNER JOIN products ON auction_prices.product_id = products.id INNER JOIN auctioneers ON auctions.auctioneer_id = auctioneers.id WHERE auction_prices.product_id = ? AND auctions.created_at = ?";
            return $this->db->query($sql, [$product_id, $date])->result();
        }
    }

    public function get_prices_by_auctioneer_id($auctioneer_id, $date = null)
    {
        if ($date == null) {
            $sql = "SELECT auction_prices.*, auctions.*, auctioneers.name auctioneer_name, auctioneers.sub_name auctioneer_sub_name, auctioneers.img auctioneer_img, products.name product_name, products.img product_img, products.category_id product_category_id FROM auction_prices INNER JOIN auctions ON auction_prices.auction_id = auctions.id INNER JOIN products ON auction_prices.product_id = products.id INNER JOIN auctioneers ON auctions.auctioneer_id = auctioneers.id WHERE auctions.auctioneer_id = ? AND auctions.created_at = (SELECT MAX(created_at) FROM auctions WHERE auctions.auctioneer_id = ?)";
            return $this->db->query($sql, [$auctioneer_id, $auctioneer_id])->result();
        } else {
            $sql = "SELECT auction_prices.*, auctions.*, auctioneers.name auctioneer_name, auctioneers.sub_name auctioneer_sub_name, auctioneers.img auctioneer_img, products.name product_name, products.img product_img, products.category_id product_category_id FROM auction_prices INNER JOIN auctions ON auction_prices.auction_id = auctions.id INNER JOIN products ON auction_prices.product_id = products.id INNER JOIN auctioneers ON auctions.auctioneer_id = auctioneers.id WHERE auctions.auctioneer_id = ? AND auctions.created_at = ?";
            return $this->db->query($sql, [$auctioneer_id, $date])->result();
        }
    }

    public function get_auctioneer_by_sub_name($auctioneer, $sub_name)
    {
        $sql = "SELECT * FROM auctioneers WHERE `name` = ? AND `sub_name` = ?";
        return $this->db->query($sql, [$auctioneer, $sub_name])->result()[0];
    }

    public function get_auctioneers()
    {
        $sql = "SELECT * FROM auctioneers";
        return $this->db->query($sql, []);
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