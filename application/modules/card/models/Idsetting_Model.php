<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Idsetting_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
     
     public function get_single_id_setting($setting_id){
        
        $this->db->select('CS.*');
        $this->db->from('id_card_settings AS CS');    
        $this->db->where('CS.id', $setting_id);
        return $this->db->get()->row();
        
    }

}
