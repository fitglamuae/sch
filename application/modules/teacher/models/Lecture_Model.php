<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lecture_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_lecture_list( $class_id = null){
         
        $this->db->select('L.*, T.name AS teacher, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('video_lectures AS L');
        $this->db->join('teachers AS T', 'T.id = L.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = L.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        
        $this->db->where('L.academic_year_id', $this->academic_year_id);
        if($class_id){
             $this->db->where('L.class_id', $class_id);
        } 
	
	if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('L.teacher_id', $this->session->userdata('profile_id'));
        }        
        return $this->db->get()->result();        
    }
    
    
    public function get_single_lecture($id){
        
        $this->db->select('L.*, T.name AS teacher, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('video_lectures AS L');
        $this->db->join('teachers AS T', 'T.id = L.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = L.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        $this->db->where('L.id', $id);
        return $this->db->get()->row();    
        
    } 
    
    
    public function get_teacher_by_subject( $class_id = null){
        
        $this->db->select('T.*');
        $this->db->from('teachers AS T');
        $this->db->join('subjects AS S', 'S.teacher_id = T.id', 'left');                
        if($class_id){
            $this->db->where('S.class_id', $class_id);
        }        
        $this->db->order_by('T.id', 'DESC');        
        
        return $this->db->get()->result();
    }
}
