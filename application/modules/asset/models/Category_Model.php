<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_category_list() {

        $this->db->select('C.*');
        $this->db->from('asset_categories AS C');
        $this->db->order_by('C.id', 'DESC');
        return $this->db->get()->result();
    }

    function duplicate_check($name, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }

        $this->db->where('name', $name);
        return $this->db->get('asset_categories')->num_rows();
    }

}
