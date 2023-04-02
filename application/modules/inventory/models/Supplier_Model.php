<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_supplier_list() {

        $this->db->select('S.*');
        $this->db->from('item_suppliers AS S');
        $this->db->order_by('S.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_single_supplier($id) {

        $this->db->select('S.*');
        $this->db->from('item_suppliers AS S');
        $this->db->order_by('S.id', 'DESC');
        return $this->db->get()->row();
    }

    function duplicate_check($company, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('company', $company);
        return $this->db->get('item_suppliers')->num_rows();
    }

}
