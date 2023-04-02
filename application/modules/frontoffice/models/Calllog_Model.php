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

class Calllog_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_calllog(){        
        
        $this->db->select('L.*');
        $this->db->from('phone_call_logs AS L');     
               
        $this->db->order_by('L.id', 'DESC');
        
        return $this->db->get()->result();
    } 
    
    public function get_single_calllog($log_id){        
        
        $this->db->select('L.*');
        $this->db->from('phone_call_logs AS L');           
        $this->db->where('L.id', $log_id);           
        return $this->db->get()->row();
    } 
}
