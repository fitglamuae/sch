<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Student.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Generate
 * @description     : Manage all type of system student listing.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Admit extends MY_Controller {

    public $data = array();
      
   public function __construct() {
        parent::__construct();
                
        $this->load->model('Student_Model', 'student', true);                
    }



    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load user filtering interface                 
     *                      
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index(){
        
        
        check_permission(VIEW);
        
        $this->data['students'] = '';
        
         if ($_POST) {
             
            $exam_id = $this->input->post('exam_id');
            $class_id = $this->input->post('class_id'); 
            $section_id = $this->input->post('section_id'); 
            $student_id = $this->input->post('student_id');
                
            $this->data['cards'] = $this->student->get_student_list($class_id, $section_id, $student_id);
            $this->data['admit_setting'] = $this->student->get_single('admit_card_settings', array('status'=>1));
            $this->data['exam'] = $this->student->get_single('exams', array('id'=>$exam_id));
            $this->data['subjects'] = $this->student->get_list('subjects', array('class_id'=>$class_id, 'status'=>1));
           
            $this->data['exam_id'] = $exam_id;
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['student_id'] = $student_id;
            
         }
             
        $this->data['classes'] = $this->student->get_list('classes', array('status'=>1), '','', '', 'id', 'ASC');
        $this->data['exams'] = $this->student->get_list('exams', array('status'=>1, 'academic_year_id'=> $this->academic_year_id), '','', '', 'id', 'ASC');
                
        $this->layout->title($this->lang->line('generate_student_admit_card') .' | ' . SMS);
        $this->layout->view('student/admit', $this->data);         
    }    

}
