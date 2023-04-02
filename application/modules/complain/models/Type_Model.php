<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Type_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_type(){        
        
        $this->db->select('T.*');
        $this->db->from('complain_types AS T');       
               
        $this->db->order_by('T.id', 'DESC');
        
        return $this->db->get()->result();
    }
    
    
    function duplicate_check( $type, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('type', $type);
        return $this->db->get('complain_types')->num_rows();            
    }
}
