<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Instruction_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
        
    public function get_instruction_list(){
        
        $this->db->select('I.*');
        $this->db->from('exam_instructions AS I');
        $this->db->order_by('I.id', 'DESC');
        return $this->db->get()->result();        
    }
    

    
     function duplicate_check($title, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('title', $title);
        return $this->db->get('exam_instructions')->num_rows();            
    }

}