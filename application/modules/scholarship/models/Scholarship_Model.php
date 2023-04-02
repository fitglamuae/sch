<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scholarship_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_scholarship_list($class_id = null){
         
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
        
        $this->db->select('S.*, ST.name AS student_name, CL.name AS class_name, SE.name AS section, E.roll_no');
        $this->db->from('ss_scholarships AS S');
        $this->db->join('ss_candidates AS C', 'C.id = S.candidate_id', 'left');
        $this->db->join('students AS ST', 'ST.id = C.student_id', 'left');
        $this->db->join('classes AS CL', 'CL.id = C.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = C.section_id', 'left');
        $this->db->join('enrollments AS E', 'E.student_id = C.student_id', 'left');
        $this->db->where('E.academic_year_id', $this->academic_year_id);      
        
        if($class_id){
            $this->db->where('E.class_id', $class_id);
        }
        
        $this->db->order_by('S.id', 'DESC');         
        return $this->db->get()->result();        
    }
    
    public function get_single_scholarship($id){
        
        $this->db->select('S.*, ST.name AS student_name, C.class_id, C.section_id, C.student_id, CL.name AS class_name, SE.name AS section, E.roll_no');
        $this->db->from('ss_scholarships AS S');
        $this->db->join('ss_candidates AS C', 'C.id = S.candidate_id', 'left');
        $this->db->join('students AS ST', 'ST.id = C.student_id', 'left');
        $this->db->join('classes AS CL', 'CL.id = C.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = C.section_id', 'left');
        $this->db->join('enrollments AS E', 'E.student_id = C.student_id', 'left');
        $this->db->where('E.academic_year_id', $this->academic_year_id);  
        $this->db->where('S.id', $id);
        return $this->db->get()->row();        
    } 
    
    
    
    
    public function get_candidate_list(){
         
        $this->db->select('C.*, ST.name AS student_name');
        $this->db->from('ss_candidates AS C');
        $this->db->join('students AS ST', 'ST.id = C.student_id', 'left');
        $this->db->where('C.academic_year_id',$this->academic_year_id);     
        $this->db->order_by('C.id', 'DESC');         
        return $this->db->get()->result();        
    }
    
    
    function duplicate_check($candidate_id, $id = null){           
        
            $data = array(
                'candidate_id' => $candidate_id,
                'academic_year_id' => $this->academic_year_id
            );
            
            if($id){
                $this->db->where_not_in('id', $id);
            }
            
            $this->db->where($data);
            return $this->db->get('ss_scholarships')->num_rows();            
    }
    
    
}
