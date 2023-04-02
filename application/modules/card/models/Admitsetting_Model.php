<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admitsetting_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }

    
     public function get_single_admit_setting($setting_id){
        
        $this->db->select('AC.*');
        $this->db->from('admit_card_settings AS AC');      
        $this->db->where('AC.id', $setting_id);
        return $this->db->get()->row();        
    } 
}
