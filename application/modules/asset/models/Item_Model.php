<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Item_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_item_list() {

        $this->db->select('I.*, S.name AS store_name, C.name AS cat_name');
        $this->db->from('asset_items AS I');
        $this->db->join('asset_stores AS S', 'S.id = I.store_id', 'left');
        $this->db->join('asset_categories AS C', 'C.id = I.category_id', 'left');
        $this->db->order_by('I.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_single_item($id) {
        
        $this->db->select('I.*, S.name AS store_name, C.name AS cat_name');
        $this->db->from('asset_items AS I');
        $this->db->join('asset_stores AS S', 'S.id = I.store_id', 'left');
        $this->db->join('asset_categories AS C', 'C.id = I.category_id', 'left');
        $this->db->where('I.id', $id);
        return $this->db->get()->row();
    }
    
    
    function duplicate_check($name, $category_id, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }

        $this->db->where('category_id', $category_id);
        $this->db->where('name', $name);
        return $this->db->get('asset_items')->num_rows();
    }

}
