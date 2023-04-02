<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Application_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_application_list($status = 0){
         
        $this->db->select('A.*, T.type, T.total_leave, R.name AS role_name, AY.session_year');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.leave_status', $status);          
             
        $this->db->order_by('A.id', 'DESC');         
        return $this->db->get()->result();
        
    }
    
    public function get_single_application($id){
        
        $this->db->select('A.*, T.type, T.total_leave,  R.name AS role_name, AY.session_year');
        $this->db->from('leave_applications AS A');
        $this->db->join('leave_types AS T', 'T.id = A.type_id', 'left'); 
        $this->db->join('roles AS R', 'R.id = A.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = A.academic_year_id', 'left');
        $this->db->where('A.id', $id);
        return $this->db->get()->row();        
    } 
}
