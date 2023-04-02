<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Store_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_store_list() {

        $this->db->select('S.*');
        $this->db->from('asset_stores AS S');
        $this->db->order_by('S.id', 'DESC');
        return $this->db->get()->result();
    }


    function duplicate_check($name, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }

        $this->db->where('name', $name);
        return $this->db->get('asset_stores')->num_rows();
    }

}
