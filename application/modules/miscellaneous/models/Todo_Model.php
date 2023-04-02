<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Todo_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_todo_list(){
         
        $user_id = '';
        if($this->session->userdata('role_id') != SUPER_ADMIN && $this->session->userdata('role_id') != ADMIN){
            $user_id = $this->session->userdata('user_id');
        }
        
        $this->db->select('T.*, R.name AS role_name, AY.session_year');
        $this->db->from('todos AS T');
        $this->db->join('roles AS R', 'R.id = T.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
        
        if($user_id){
            $this->db->where('T.user_id', $user_id);
        }
        
        $this->db->order_by('T.id', 'DESC');         
        return $this->db->get()->result();
        
    }
    
    public function get_single_todo($id){
        
        $this->db->select('T.*, R.name AS role_name, AY.session_year');
        $this->db->from('todos AS T');
        $this->db->join('roles AS R', 'R.id = T.role_id', 'left');
        $this->db->join('academic_years AS AY', 'AY.id = T.academic_year_id', 'left');
        $this->db->where('T.id', $id);
        return $this->db->get()->row();        
    } 
}
