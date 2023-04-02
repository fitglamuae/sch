<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Onlineexam_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
        
    public function get_online_exam_list($class_id, $subject_id){
        
        
        if($this->session->userdata('role_id') == STUDENT){
            $class_id = $this->session->userdata('class_id');
        }
        
        if($class_id == ''){
            return;
        }
        
        $this->db->select('OE.*, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year');
        $this->db->from('exam_online_exams AS OE');
        $this->db->join('classes AS C', 'C.id = OE.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = OE.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = OE.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = OE.academic_year_id', 'left');
        $this->db->join('exam_instructions AS EI', 'EI.id = OE.instruction_id', 'left');
        
        $this->db->where('OE.academic_year_id', $this->academic_year_id);
        if($class_id){
             $this->db->where('OE.class_id', $class_id);
        } 
        if($subject_id){
             $this->db->where('OE.subject_id', $subject_id);
        } 
	       
        return $this->db->get()->result();     
    }  
    
    public function get_single_online_exam($id){
        
        $this->db->select('OE.*, C.name AS class_name, SE.name AS section, S.name AS subject, AY.session_year, EI.instruction, EI.title AS ins_title');
        $this->db->from('exam_online_exams AS OE');
        $this->db->join('classes AS C', 'C.id = OE.class_id', 'left');
        $this->db->join('sections AS SE', 'SE.id = OE.section_id', 'left');
        $this->db->join('subjects AS S', 'S.id = OE.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = OE.academic_year_id', 'left');
        $this->db->join('exam_instructions AS EI', 'EI.id = OE.instruction_id', 'left');
        
        $this->db->where('OE.id', $id);
        return $this->db->get()->row();     
    }    

    
    function duplicate_check($title, $class_id, $subject_id, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('title', $title);
        $this->db->where('class_id', $class_id);
        $this->db->where('subject_id', $subject_id);
        return $this->db->get('exam_online_exams')->num_rows();            
    }
}