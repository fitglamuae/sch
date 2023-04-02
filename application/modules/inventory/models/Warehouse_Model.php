<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Warehouse_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_warehouse_list() {

        $this->db->select('W.*');
        $this->db->from('item_warehouses AS W');
        $this->db->order_by('W.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_single_warehouse($id) {

        $this->db->select('W.*');
        $this->db->from('item_warehouses AS W');
        $this->db->where('W.id', $id);
        return $this->db->get()->row();
    }

    function duplicate_check($name, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        return $this->db->get('item_warehouses')->num_rows();
    }

}
