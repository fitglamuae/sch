<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    
   
        
     public function get_department_list(){
        
        $this->db->select('d.*');
        $this->db->from('departments AS d');
        $this->db->order_by('d.id', 'DESC');
        return $this->db->get()->result();
        
    }
    

    
     function duplicate_check($title, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('title', $title);
        return $this->db->get('departments')->num_rows();            
    }

}
