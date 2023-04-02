<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_student_list($class_id = null, $end_year = null, $status_type = null){
        
        
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
        
        if(!$class_id){  return array();  }
        
           
        $this->db->select('S.*, SG.group, ST.type, E.roll_no, E.class_id, U.email, U.role_id,  C.name AS class_name, SE.name AS section, AY.session_year');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('student_groups AS SG', 'SG.id = S.group_id', 'left');
        $this->db->join('student_types AS ST', 'ST.id = S.type_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = E.academic_year_id', 'left');
        
        if($end_year){
           
           $this->db->where('AY.start_year >', $end_year); 
        }else{            
            $this->db->where('E.academic_year_id', $this->academic_year_id); 
        }
        
        $this->db->where('E.class_id', $class_id);
      
        if($this->session->userdata('role_id') == GUARDIAN){
           $this->db->where('S.guardian_id', $this->session->userdata('profile_id'));
        }
        
        if($status_type){
            $this->db->where('S.status_type', $status_type);
        }
       
        return $this->db->get()->result();
    }
    
    public function get_single_student($id, $adv = null){
        
        $this->db->select('S.*, SG.group, ST.type, D.amount, D.title AS discount_title, G.name as guardian, E.roll_no, E.class_id, E.section_id, E.academic_year_id, U.email, U.role_id, R.name AS role,  C.name AS class_name, SE.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('student_groups AS SG', 'SG.id = S.group_id', 'left');
        $this->db->join('student_types AS ST', 'ST.id = S.type_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->join('discounts AS D', 'D.id = S.discount_id', 'left');
        
        if(!$adv){
            $this->db->where('E.academic_year_id', $this->academic_year_id);
        }
        
        $this->db->where('S.id', $id);
        return $this->db->get()->row();
        
    }
    
    public function get_single_guardian($id){
        
        $this->db->select('G.*, U.email, U.role_id, R.name as role');
        $this->db->from('guardians AS G');
        $this->db->join('users AS U', 'U.id = G.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->where('G.id', $id);
        return $this->db->get()->row();
        
    }
    
    /*
    public function get_invoice_list($student_id, $due = null){

    $this->db->select('I.*, S.name AS student_name, AY.session_year, C.name AS class_name');
    $this->db->from('invoices AS I');        
    $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
    $this->db->join('students AS S', 'S.id = I.user_id', 'left');
    $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');        
    $this->db->where('I.invoice_type !=', 'income'); 
    $this->db->where('I.user_id', $student_id);        

    if($due){
        $this->db->where('I.paid_status !=', 'paid');  
    }        
    if($this->session->userdata('role_id') == STUDENT){
        $this->db->where('I.user_id', $this->session->userdata('profile_id'));
    }        
    $this->db->order_by('I.id', 'DESC');  
    return $this->db->get()->result();  

   // echo $this->db->last_query();
}     
*/
    
    
    public function get_invoice_list($student_id){

        $this->db->select('I.*,  S.name AS student_name, AY.session_year, C.name AS class_name');
        $this->db->from('invoices AS I');        
        $this->db->join('classes AS C', 'C.id = I.class_id', 'left');
        $this->db->join('students AS S', 'S.id = I.user_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = I.academic_year_id', 'left');        
        $this->db->where('I.invoice_type !=', 'income');         
        $this->db->where('I.user_id', $student_id);   
        $this->db->where('I.paid_status !=', 'paid');
        $this->db->order_by('I.id', 'DESC');  
        return $this->db->get()->result();      
   
    }  

    public function get_activity_list($student_id){
        
        $this->db->select('SA.*, ST.name AS student, ST.phone, C.name AS class_name, S.name as section, AY.session_year');
        $this->db->from('student_activities AS SA');
        $this->db->join('students AS ST', 'ST.id = SA.student_id', 'left');
        $this->db->join('classes AS C', 'C.id = SA.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = SA.section_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = SA.academic_year_id', 'left');
        $this->db->where('SA.student_id', $student_id);
        $this->db->where('SA.academic_year_id', $this->academic_year_id);
        return $this->db->get()->result();
    } 
    
    
    function duplicate_check($email, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('email', $email);
        return $this->db->get('users')->num_rows();
    }
}
