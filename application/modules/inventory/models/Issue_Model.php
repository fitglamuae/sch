<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Issue_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_issue_list() {

        $user_id = '';
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != ADMIN){
            $user_id = $this->session->userdata('user_id');
        }
        
        $this->db->select('I.*, C.name AS category, P.name AS product, R.name AS role_name');
        $this->db->from('item_issues AS I');
        $this->db->join('item_categories AS C', 'C.id = I.category_id', 'left');
        $this->db->join('item_products AS P', 'P.id = I.product_id', 'left');
        $this->db->join('classes AS CL', 'CL.id = I.class_id', 'left');
        $this->db->join('roles AS R', 'R.id = I.role_id', 'left');
        $this->db->join('users AS U', 'U.id = I.user_id', 'left');
        
        if($user_id){
            $this->db->where('U.id', $user_id);
        }
        
        $this->db->order_by('I.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_single_issue($id) {

        $this->db->select('I.*, C.name AS category, P.name AS product, R.name AS role_name');
        $this->db->from('item_issues AS I');
        $this->db->join('item_categories AS C', 'C.id = I.category_id', 'left');
        $this->db->join('item_products AS P', 'P.id = I.product_id', 'left');
        $this->db->join('classes AS CL', 'CL.id = I.class_id', 'left');
        $this->db->join('roles AS R', 'R.id = I.role_id', 'left');
        $this->db->join('users AS U', 'U.id = I.user_id', 'left');
        $this->db->where('I.id', $id);
        return $this->db->get()->row();
    }
    
}
