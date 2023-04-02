<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    
     public function get_vendor_list(){
        
        $this->db->select('V.*');
        $this->db->from('asset_vendors AS V');
        $this->db->order_by('V.id', 'DESC');
        return $this->db->get()->result();        
    }
    

      public function get_single_vendor($id){
        
        $this->db->select('V.*');
        $this->db->from('asset_vendors AS V');
        $this->db->order_by('V.id', 'DESC');
        return $this->db->get()->row();           
    } 
    
    
     function duplicate_check($name, $id = null ){           
           
        if($id){
            $this->db->where_not_in('id', $id);
        }
        
        $this->db->where('name', $name);
        return $this->db->get('asset_vendors')->num_rows();            
    }
}
