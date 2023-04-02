<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_group(){        
        
        $this->db->select('G.*');
        $this->db->from('student_groups AS G');               
        $this->db->order_by('G.id', 'ASC');        
        return $this->db->get()->result();
    }
    
    
    function duplicate_check( $group, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('group', $group);
        return $this->db->get('student_groups')->num_rows();            
    }
}
