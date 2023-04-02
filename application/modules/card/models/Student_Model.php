<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    
    public function get_student_list($class_id = null, $section_id = null, $student_id = null) {

        $this->db->select('S.*, E.roll_no, U.email, U.role_id,  C.name AS class_name, SE.name AS section');
        $this->db->from('enrollments AS E');
        $this->db->join('students AS S', 'S.id = E.student_id', 'left');
        $this->db->join('users AS U', 'U.id = S.user_id', 'left');
        $this->db->join('classes AS C', 'C.id = E.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = E.section_id', 'left');
        $this->db->where('E.academic_year_id', $this->academic_year_id);       
        $this->db->where('E.class_id', $class_id);
        
        if($section_id){
            $this->db->where('E.section_id', $section_id);
        }
        
        $this->db->where('S.status', 1);
        if($student_id){
            $this->db->where('E.id', $student_id);
        } 
        
        $this->db->where('S.status_type', 'regular');
        
        $this->db->order_by('S.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
}
