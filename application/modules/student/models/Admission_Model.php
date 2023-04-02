<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admission_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_admission_list($class_id = null){
                
        $this->db->select('A.*, T.type, G.group, C.name AS class_name');
        $this->db->from('admissions AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('student_types AS T', 'T.id = A.type_id', 'left');
        $this->db->join('student_groups AS G', 'G.id = A.group_id', 'left');
      
        if($class_id){
            $this->db->where('A.class_id', $class_id);
        }        
        $this->db->order_by('A.id', 'DESC');
       
        return $this->db->get()->result();
        
    }
    
    
    public function get_single_admission($admission_id){
        
        $this->db->select('A.*, T.type, G.group, C.name AS class_name');
        $this->db->from('admissions AS A');
        $this->db->join('classes AS C', 'C.id = A.class_id', 'left');
        $this->db->join('student_types AS T', 'T.id = A.type_id', 'left');
        $this->db->join('student_groups AS G', 'G.id = A.group_id', 'left');  
        $this->db->where('A.id', $admission_id);        
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
    
    function duplicate_check($email, $id = null) {

        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('email', $email);
        return $this->db->get('users')->num_rows();
    }
}
