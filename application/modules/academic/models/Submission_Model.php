<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Submission_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    
    public function get_submission_list($class_id = null , $section_id = null){
         
        
        $this->db->select('ASU.*, SU.teacher_id, E.roll_no, ST.name AS student_name,  A.title, A.assigment_date, A.submission_date, C.name AS class_name, SE.name AS section, SU.name AS subject, AY.session_year');
        $this->db->from('assignment_submissions AS ASU');
        $this->db->join('enrollments AS E', 'E.student_id = ASU.student_id', 'left');
        $this->db->join('assignments AS A', 'A.id = ASU.assignment_id', 'left');
        $this->db->join('classes AS C', 'C.id = ASU.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = ASU.section_id', 'left');
        $this->db->join('subjects AS SU', 'SU.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = ASU.academic_year_id', 'left');
        $this->db->join('students AS ST', 'ST.id = ASU.student_id', 'left');
         
        $this->db->where('E.academic_year_id', $this->academic_year_id);
        
        if($class_id > 0){
             $this->db->where('ASU.class_id', $class_id);
        }
        
        if($section_id > 0){
             $this->db->where('ASU.section_id', $section_id);
        }
             
        if($this->session->userdata('role_id') == STUDENT){
            $this->db->where('ASU.student_id', $this->session->userdata('profile_id'));
        }        
       
        $this->db->order_by('ASU.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_submission($id){
        
        $this->db->select('ASU.*, SU.teacher_id, E.roll_no, ST.name  AS student_name,  A.title AS assignment, A.assigment_date, A.submission_date, C.name AS class_name, SE.name AS section, SU.name AS subject, AY.session_year');
        $this->db->from('assignment_submissions AS ASU');
        $this->db->join('enrollments AS E', 'E.student_id = ASU.student_id', 'left');
        $this->db->join('assignments AS A', 'A.id = ASU.assignment_id', 'left');
        $this->db->join('classes AS C', 'C.id = ASU.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = ASU.section_id', 'left');
        $this->db->join('subjects AS SU', 'SU.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = ASU.academic_year_id', 'left');
        $this->db->join('students AS ST', 'ST.id = ASU.student_id', 'left');
        $this->db->where('ASU.id', $id); 
        return $this->db->get()->row();        
    }
    
    public function get_submission_by_assignment($assignment_id = null){
         
        
        $this->db->select('ASU.*, ST.name AS student_name, A.title, A.assigment_date, A.submission_date, C.name AS class_name, SE.name AS section, SU.name AS subject, AY.session_year');
        $this->db->from('assignment_submissions AS ASU');
        $this->db->join('assignments AS A', 'A.id = ASU.assignment_id', 'left');
        $this->db->join('classes AS C', 'C.id = ASU.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = ASU.section_id', 'left');
        $this->db->join('subjects AS SU', 'SU.id = A.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = ASU.academic_year_id', 'left');
        $this->db->join('students AS ST', 'ST.id = ASU.student_id', 'left');
        $this->db->where('ASU.assignment_id', $assignment_id);
        $this->db->order_by('ASU.id', 'DESC');
        
        return $this->db->get()->result();
        
    }
    
    function duplicate_check($assignment_id, $student_id, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('assignment_id', $assignment_id);
        $this->db->where('student_id', $student_id);
        return $this->db->get('assignment_submissions')->num_rows();            
    }
    
   public function get_single_teacher($id){
        
        $this->db->select('T.*, U.email, D.title, U.role_id, R.name AS role, SG.grade_name');
        $this->db->from('teachers AS T');
        $this->db->join('departments AS D', 'D.id = T.department_id', 'left');
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->join('roles AS R', 'R.id = U.role_id', 'left');
        $this->db->join('salary_grades AS SG', 'SG.id = T.salary_grade_id', 'left');
        $this->db->where('T.id', $id);
        return $this->db->get()->row();
        
    }
    
}
