<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ebook_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_ebook_list($class_id = null){
        
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
         
        $this->db->select('B.*, C.name AS class_name, S.name AS subject');
        $this->db->from('ebooks AS B');
        $this->db->join('classes AS C', 'C.id = B .class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = B.subject_id', 'left');
        
        if($this->session->userdata('role_id') == TEACHER){
            $this->db->where('S.teacher_id', $this->session->userdata('profile_id'));
        }
        
        if($class_id){
            $this->db->where('B.class_id', $class_id);
        }
            
        return $this->db->get()->result();
        
    }
    
    public function get_single_ebook($id){
        
        $this->db->select('B.*, C.name AS class_name, S.name AS subject');
        $this->db->from('ebooks AS B');
        $this->db->join('classes AS C', 'C.id = B .class_id', 'left');
        $this->db->join('subjects AS S', 'S.id = B.subject_id', 'left');
        $this->db->where('B.id', $id);
        return $this->db->get()->row();
        
    } 
}
