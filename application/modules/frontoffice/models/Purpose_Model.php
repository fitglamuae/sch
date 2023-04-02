<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth_Model
 *
 * @author 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purpose_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_purpose(){        
        
        $this->db->select('P.*');
        $this->db->from('visitor_purposes AS P');               
        $this->db->order_by('P.id', 'ASC');
        
        return $this->db->get()->result();
    }
    
    
    function duplicate_check($purpose, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('purpose', $purpose);
        return $this->db->get('visitor_purposes')->num_rows();            
    }
}
