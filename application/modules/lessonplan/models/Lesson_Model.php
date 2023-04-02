<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lesson_Model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    // ok
    public function get_lesson_list($class_id = null, $subject_id = null) {

        $this->db->select('L.*, C.name AS class_name, S.name AS subject, S.teacher_id, AY.session_year');
        $this->db->from('lp_lessons AS L');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        
        if($class_id){
        $this->db->where('L.class_id', $class_id);
        }
        if($subject_id){
        $this->db->where('L.subject_id', $subject_id);
        }
        
        $this->db->where('L.academic_year_id', $this->academic_year_id);
        $this->db->order_by('L.id', 'ASC');
        return $this->db->get()->result();
    }

    //ok
    public function get_single_lesson($id) {

        $this->db->select('L.*, C.name AS class_name, S.name AS subject, AY.session_year');
        $this->db->from('lp_lessons AS L');
        $this->db->join('classes AS C', 'C.id = L.class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = L.subject_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = L.academic_year_id', 'left');
        $this->db->where('L.id', $id);
        return $this->db->get()->row();
    }  
   
}