<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sale_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }
    
     public function get_fee_type(){
    
        $this->db->select('IH.*');
        $this->db->from('income_heads AS IH'); 
        $this->db->where('IH.head_type', 'sale'); 
        return $this->db->get()->result(); 
    }

    public function get_sale_list() {

        $this->db->select('I.*, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');        
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');        
        $this->db->where('I.invoice_type', 'sale'); 
             
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.user_id', $this->session->userdata('profile_id'));
        } 
        
        $this->db->where('I.academic_year_id', $this->academic_year_id);       
        $this->db->order_by('I.id', 'DESC');  
        
        return $this->db->get()->result(); 
        
    }

    public function get_single_sale($id) {

        $this->db->select('I.*, I.discount AS inv_discount, I.id AS inv_id, AY.session_year');
        $this->db->from('invoices AS I');        
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');       
        $this->db->where('I.id', $id);       
       
        return $this->db->get()->row();    
    }
    
     public function get_sale_item($sale_id){                
        $this->db->select('IS.*, IH.title, C.name AS category, P.name as product');
        $this->db->from('item_sales AS IS');        
        $this->db->join('income_heads AS IH', 'IH.id = IS.income_head_id', 'left');
        $this->db->join('item_categories AS C', 'C.id = IS.category_id', 'left');
        $this->db->join('item_products AS P', 'P.id = IS.product_id', 'left');
        $this->db->where('IS.invoice_id', $sale_id);
        return $this->db->get()->result();
    }
    
     public function get_invoice_amount($sale_id){
        $this->db->select('I.*, SUM(T.amount) AS paid_amount');
        $this->db->from('invoices AS I');        
        $this->db->join('transactions AS T', 'T.invoice_id = I.id', 'left');
        $this->db->where('I.id', $sale_id);         
        return $this->db->get()->row(); 
    }
    
}
