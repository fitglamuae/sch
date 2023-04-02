<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatch_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_dispatch(){        
        
        $this->db->select('D.*');
        $this->db->from('postal_dispatches AS D');
        $this->db->order_by('D.id', 'DESC');        
        return $this->db->get()->result();
    } 
    
    public function get_single_dispatch($dispatch_id){        
        
        $this->db->select('D.*');
        $this->db->from('postal_dispatches AS D');         
        $this->db->where('D.id', $dispatch_id);          
        return $this->db->get()->row();
    } 
}
