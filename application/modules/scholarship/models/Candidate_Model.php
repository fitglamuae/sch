<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Candidate_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_candidate_list($class_id = null){
         
        
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
        
        $this->db->select('C.*, CL.name AS class_name, S.name AS section_name, ST.name AS student_name, AY.session_year');
        $this->db->from('ss_candidates AS C');
        $this->db->join('classes AS CL', 'CL.id = C.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = C.section_id', 'left');
        $this->db->join('students AS ST', 'ST.id = C.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = C.academic_year_id', 'left');
        
        if($class_id){
            $this->db->where('C.class_id', $class_id);
        }
             
        $this->db->order_by('C.id', 'DESC');         
        return $this->db->get()->result();
        
    }
    
    public function get_single_candidate($id){
        
        $this->db->select('C.*, CL.name AS class_name, S.name AS section_name, ST.name AS student_name, AY.session_year');
        $this->db->from('ss_candidates AS C');
        $this->db->join('classes AS CL', 'CL.id = C.class_id', 'left');
        $this->db->join('sections AS S', 'S.id = C.section_id', 'left');
        $this->db->join('students AS ST', 'ST.id = C.student_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = C.academic_year_id', 'left');
        $this->db->where('C.id', $id);
        return $this->db->get()->row();        
    } 
    
    
    function duplicate_check($student_id, $id = null){           
        
            $data = array(
                'student_id' => $student_id,
                'academic_year_id' => $this->academic_year_id
            );
            
            if($id){
                $this->db->where_not_in('id', $id);
            }
            
            $this->db->where($data);
            return $this->db->get('ss_candidates')->num_rows();            
    }
   
}
