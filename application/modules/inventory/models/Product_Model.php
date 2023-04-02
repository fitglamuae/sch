<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_product_list() {

        $this->db->select('P.*, C.name AS category, W.name AS warehouse_name');
        $this->db->from('item_products AS P');
        $this->db->join('item_categories AS C', 'C.id=P.category_id', 'left');
        $this->db->join('item_warehouses AS W', 'W.id=P.warehouse_id', 'left');
        $this->db->order_by('P.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_single_product($id) {

        $this->db->select('P.*, C.name AS category, W.name AS warehouse_name');
        $this->db->from('item_products AS P');
        $this->db->join('item_categories AS C', 'C.id = P.category_id', 'left');
        $this->db->join('item_warehouses AS W', 'W.id=P.warehouse_id', 'left');
        $this->db->where('P.id', $id);
        return $this->db->get()->row();
    }

    function duplicate_check($name, $category_id,  $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('category_id', $category_id);
        $this->db->where('name', $name);
        return $this->db->get('item_products')->num_rows();
    }

}
