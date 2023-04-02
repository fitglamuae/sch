<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Welcome.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Welcome
 * @description     : This is default class of the application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Welcome extends CI_Controller {
    /*     * **************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : this function load login view page            
     * @param           : null; 
     * @return          : null 
     * ********************************************************** */

    public $gsms_setting = array();
    
    public function index() {
            
        if (logged_in_user_id()) {
            redirect('dashboard');
        }
        
        $gsms_setting = $this->db->get_where('settings',array('status'=>1))->row();
        if($gsms_setting){           
            $this->gsms_setting = $gsms_setting;            
            date_default_timezone_set($this->gsms_setting->default_time_zone);
        }
        
        $this->load->view('login', array('login'));
    }
}
