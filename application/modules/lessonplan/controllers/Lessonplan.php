<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Lessonplan.php**********************************
 * @topic           : Global Single School Management System Express
 * @type            : Class
 * @class name      : Lessonplan
 * @description     : Manage :Lessonplan
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Lessonplan extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Lessonplan_Model', 'lesson_plan', true);
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('setting');
        }   
        
        $this->data['classes'] = $this->lesson_plan->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        if($this->session->userdata('role_id') == STUDENT){
            $this->data['subjects']  = $this->lesson_plan->get_list('subjects',array('status'=>1, 'class_id'=>$this->session->userdata('class_id')), '','', '', 'id', 'ASC'); 
        } 
        
    }

    
        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "topic List" user interface                 
    *                       
    * @param           : $class_id & $subject_id integer value
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);        
       
        $class_id = '';
        $subject_id = '';
        
        if($_POST){   
            $class_id  = $this->input->post('class_id');           
            $subject_id  = $this->input->post('subject_id');           
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['subject_id'] = $subject_id;
                
        $this->data['topics'] = $this->lesson_plan->get_topic_list($class_id, $subject_id);                       
        $this->data['lesson_info'] = $this->lesson_plan->get_lesson_info($class_id, $subject_id);                       
        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_lesson_plan'). ' | ' . SMS);
        $this->layout->view('lesson_plan/index', $this->data);
     
    }
}