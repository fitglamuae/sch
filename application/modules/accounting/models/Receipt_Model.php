<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receipt_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_invoice_item($invoice_id, $invoice_type = null){   
        
        
         if($invoice_type == 'sale'){
            $this->db->select('IS.*, IH.title, C.name AS category, P.name as product');
            $this->db->from('item_sales AS IS');        
            $this->db->join('income_heads AS IH', 'IH.id = IS.income_head_id', 'left');
            $this->db->join('item_categories AS C', 'C.id = IS.category_id', 'left');
            $this->db->join('item_products AS P', 'P.id = IS.product_id', 'left');
            $this->db->where('IS.invoice_id', $invoice_id);
        }else{
            $this->db->select('ID.*, IH.title');
            $this->db->from('invoice_detail AS ID');        
            $this->db->join('income_heads AS IH', 'IH.id = ID.income_head_id', 'left');
            $this->db->where('ID.invoice_id', $invoice_id);
        }
        return $this->db->get()->result();
        
    } 
    
    public function get_due_receipt_list($role_id = null, $class_id = null, $user_id = null){
        
        $this->db->select('I.*, I.id as inv_id, S.name AS student_name, S.present_address, S.phone, C.name AS class_name');
        $this->db->from('invoices AS I');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.user_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');

        $this->db->where('I.invoice_type !=', 'income');
        $this->db->where('I.paid_status !=', 'paid');
      
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
        } 
        
        $this->db->where('I.academic_year_id', $this->academic_year_id); 
        if ($class_id) {
            $this->db->where('I.class_id', $class_id);
        }
        
        if ($user_id) {
            $this->db->where('I.user_id', $user_id);
        }

        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.user_id', logged_in_user_id());
        }
        
        
        
        $this->db->order_by('I.id', 'DESC');
        return $this->db->get()->result();
       
    }
    
    
     public function get_single_due_receipt($inv_id){
     
        $this->db->select('I.*, I.id as inv_id, I.discount AS inv_discount, S.id AS student_id,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');   
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.user_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');
        $this->db->where('I.id', $inv_id);
        return $this->db->get()->row();    
        
    }
    
        
    public function get_paid_receipt_list($role_id = null, $class_id = null, $user_id = null){
        
       
        $this->db->select('I.*, I.id as inv_id, I.discount AS inv_discount, T.*, T.id AS txn_id, AY.session_year , C.name AS class_name');
        $this->db->from('transactions AS T');        
        $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.user_id', 'left');        
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
        
        $this->db->where('I.invoice_type !=', 'income');  
        $this->db->where('I.paid_status', 'paid');  
        
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
        }
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('I.user_id', logged_in_user_id());
        }
        
        if ($class_id) {
            $this->db->where('I.class_id', $class_id);
        }        
        if ($user_id) {
            $this->db->where('I.user_id', $user_id);
        }
        
        $this->db->where('T.academic_year_id', $this->academic_year_id); 
               
        $this->db->order_by('T.id', 'DESC');  
        return $this->db->get()->result();         
        
    }
    
    public function get_single_paid_receipt( $txn_id){
                
        $this->db->select('I.*, I.id as inv_id, I.discount AS inv_discount, T.*, T.id AS txn_id, S.name AS student_name, S.present_address, S.phone, AY.session_year, C.name AS class_name');
        $this->db->from('transactions AS T');        
        $this->db->join('invoices AS I', 'I.id = T.invoice_id', 'left');
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.user_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
            
        $this->db->where('T.id', $txn_id);
        return $this->db->get()->row();  
        
    } 
    
    
    public function get_single_student($class_id = null,  $user_id = null, $academic_year_id = null){
                
        $this->db->select(' S.name AS student_name, S.present_address, S.phone, AY.session_year, C.name AS class_name, SE.name AS section');
        $this->db->from('enrollments AS E');        
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
                
        if($this->session->userdata('role_id') == GUARDIAN){
            $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));  
        } 
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('E.student_id', $this->session->userdata('profile_id'));
        }         
       
        if($class_id){
           $this->db->where('E.class_id', $class_id);
        }   
        
        if($user_id){
            $this->db->where('E.student_id', $user_id);
        } 
        if($academic_year_id){
            $this->db->where('E.academic_year_id', $academic_year_id); 
        }
       
        $this->db->where('S.id', $user_id);
        return $this->db->get()->row();  
        
    } 
    
}