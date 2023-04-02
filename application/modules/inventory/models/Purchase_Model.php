<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
        
     public function get_purchase_list(){
        
        $this->db->select('P.*, S.company, C.name AS category, IP.name AS product, E.name AS employee');
        $this->db->from('item_purchases AS P');
        $this->db->join('item_suppliers AS S','S.id = P.supplier_id','left');
        $this->db->join('item_categories AS C','C.id = P.category_id','left');
        $this->db->join('item_products AS IP','IP.id = P.product_id','left');
        $this->db->join('employees AS E','E.id = P.employee_id','left');
        $this->db->order_by('P.id', 'DESC');
        return $this->db->get()->result();
        
    }
    

       public function get_single_purchase($id){
        
        $this->db->select('P.*, S.company, C.name AS category, IP.name AS product, E.name AS employee');
        $this->db->from('item_purchases AS P');
        $this->db->join('item_suppliers AS S','S.id = P.supplier_id','left');
        $this->db->join('item_categories AS C','C.id = P.category_id','left');
        $this->db->join('item_products AS IP','IP.id = P.product_id','left');
        $this->db->join('employees AS E','E.id = P.employee_id','left');
        $this->db->where('P.id', $id);
        return $this->db->get()->row();        
    } 
    
    
}
