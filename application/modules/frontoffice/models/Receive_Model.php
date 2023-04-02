<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receive_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_receive(){        
        
        $this->db->select('R.*');
        $this->db->from('postal_receives AS R');  
        $this->db->order_by('R.id', 'DESC');        
        return $this->db->get()->result();
    } 
    
    public function get_single_receive($receive_id){        
        
        $this->db->select('R.*');
        $this->db->from('postal_receives AS R');            
        $this->db->where('R.id', $receive_id);          
        return $this->db->get()->row();
    } 
}
