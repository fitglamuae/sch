<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rating_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_teacher_list($class_id){
           
       
        $this->db->select('S.teacher_id');
        $this->db->from('subjects AS S');
        $this->db->where('S.class_id', $class_id);
        $subjects = $this->db->get()->result();   
         
        $teacher_ids = array();
        if(isset($subjects) && !empty($subjects)){
            foreach($subjects as $obj){
              $teacher_ids[] = $obj->teacher_id;  
            }
        }
        
        
        $this->db->select('T.*, D.title AS department, U.email, U.role_id');
        $this->db->from('teachers AS T');
        $this->db->join('departments AS D', 'D.id = T.department_id', 'left');        
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        $this->db->where_in('T.id', $teacher_ids);
        $this->db->order_by('T.display_order', 'ASC');
        return $this->db->get()->result();        
    }
    
    
    public function get_teacher_rating_list($techer_id = null){
        
        $this->db->select('R.*, S.name AS student_name, T.name AS teacher, T.photo, D.title AS department, U.email');
        $this->db->from('ratings AS R');
        $this->db->join('students AS S', 'S.id = R.student_id', 'left');
        $this->db->join('teachers AS T', 'T.id = R.teacher_id', 'left');
        $this->db->join('departments AS D', 'D.id = T.department_id', 'left'); 
        $this->db->join('users AS U', 'U.id = T.user_id', 'left');
        
        if($techer_id){        
            $this->db->where('R.teacher_id', $techer_id);
        }        
            $this->db->where('R.academic_year_id', $this->academic_year_id);
        
        return $this->db->get()->result();
    }
}
