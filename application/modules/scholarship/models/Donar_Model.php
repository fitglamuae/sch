<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Donar_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_donar_list(){
         
        $this->db->select('D.*, AY.session_year');
        $this->db->from('ss_donars AS D');
        $this->db->join('academic_years AS AY', 'AY.id = D.academic_year_id', 'left');
        $this->db->order_by('D.id', 'DESC');         
        return $this->db->get()->result();
        
    }
    
    public function get_single_donar($id){
        
        $this->db->select('D.*, AY.session_year');
        $this->db->from('ss_donars AS D');
        $this->db->join('academic_years AS AY', 'AY.id = D.academic_year_id', 'left');
        $this->db->where('D.id', $id);
        return $this->db->get()->row();        
    } 
}
