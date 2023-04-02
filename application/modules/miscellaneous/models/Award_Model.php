<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Award_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_award_list(){
         
        $this->db->select('A.*, R.name AS role_name, AY.session_year');
        $this->db->from('awards AS A');
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
             
        $this->db->order_by('A.id', 'DESC');         
        return $this->db->get()->result();
        
    }
    
    public function get_single_award($id){
        
        $this->db->select('A.*, R.name AS role_name, AY.session_year');
        $this->db->from('awards AS A');
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.id', $id);
        return $this->db->get()->row();        
    } 
}
