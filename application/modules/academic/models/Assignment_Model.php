<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assignment_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_assignment_list($class_id = null ){
         
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
        
        $this->db->select('A.*, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('assignments AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = A.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.academic_year_id', $this->academic_year_id);
        
        if($class_id){
             $this->db->where('A.class_id', $class_id);
        }        
        $this->db->order_by('A.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_assignment($id){
        
       $this->db->select('A.*, T.name AS teacher, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('assignments AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = A.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->where('A.academic_year_id', $this->academic_year_id);
        $this->db->where('A.id', $id);
        return $this->db->get()->row();        
    }
    
    public function get_student_list($class_id, $section_id){
        
        $this->db->select('S.*, G.name as g_name, G.phone AS g_phone, UG.email AS g_email, E.roll_no, E.class_id, E.section_id, E.academic_year_id, U.email, U.role_id, R.name AS role,  C.name AS class_name, SE.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->join('guardians AS G', 'G.id = S.guardian_id', 'left');
        $this->db->join('users AS UG', 'UG.id = G.user_id', 'left');
                
        
        $this->db->where('E.class_id', $class_id);
        $this->db->where('E.section_id', $section_id);
        $this->db->where('E.academic_year_id', $this->academic_year_id);
       return $this->db->get()->result();
        
    }
}
