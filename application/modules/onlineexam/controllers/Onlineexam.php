<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* ******************Onlineexam Onlineexam.php***************************
 * @exam title      : Global School Management System Pro
 * @type            : Class
 * @class name      : Onlineexam Onlineexams
 * @description     : Manage school academic exam exam.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ***********************************************************/

class Onlineexam extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Onlineexam_Model', 'online_exam', true); 
        $this->data['instructions']    = $this->online_exam->get_list('exam_instructions',array('status'=>1), '','', '', 'id', 'ASC');
        $this->data['classes']    = $this->online_exam->get_list('classes',array('status'=>1), '','', '', 'id', 'ASC');
        if($this->session->userdata('role_id') == STUDENT){
            $this->data['subjects']  = $this->online_exam->get_list('subjects',array('status'=>1, 'class_id'=>$this->session->userdata('class_id')), '','', '', 'id', 'ASC'); 
        }
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Onlineexam List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);  
        
        
        $this->data['class_list'] = $this->data['classes'];         
        $class_id = '';
        $subject_id = '';
        
        if($_POST){
            
            $class_id = $this->input->post('class_id');
            $subject_id = $this->input->post('subject_id');
        }
        
        $this->data['filter_class_id'] = $class_id;
        $this->data['filter_subject_id'] = $subject_id;
        
        
        $this->data['online_exams'] = $this->online_exam->get_online_exam_list($class_id, $subject_id);
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_onlime_exam') . ' | ' . SMS);
        $this->layout->view('online_exam/index', $this->data);
        
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Onlineexam" user interface                 
    *                    and process to store "Onlineexams" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            
            $this->_prepare_online_exam_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_online_exam_data();

                $insert_id = $this->online_exam->insert('exam_online_exams', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a Online exam : '.$data['title']); 
                    success($this->lang->line('insert_success'));                    
                    redirect('onlineexam/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('onlineexam/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }
        
        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }        

        $this->data['class_list'] = $this->data['classes'];
         
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;
        $this->data['online_exams'] = $this->online_exam->get_online_exam_list($class_id, '');  
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('online_exam/index', $this->data);
        
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Onlineexam" user interface                 
    *                    with populated "Onlineexam" value 
    *                    and process update "Onlineexam" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('onlineexam/index');
        }
        
        if ($_POST) {
            
            $this->_prepare_online_exam_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_online_exam_data();
                $updated = $this->online_exam->update('exam_online_exams', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a exam exam : '.$data['title']);
                    success($this->lang->line('update_success'));
                    redirect('onlineexam/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('onlineexam/edit/' . $this->input->post('id'));
                }
            } else {
                
                $this->data['online_exam'] = $this->online_exam->get_single_online_exam($this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['online_exam'] = $this->online_exam->get_single_online_exam($id);

            if (!$this->data['online_exam']) {
                redirect('onlineexam/index');
            }
        }
        
        
        $this->data['class_list'] = $this->data['classes'];                        
                        
        $this->data['class_id'] = $this->data['online_exam']->class_id;
        $this->data['filter_class_id'] = $this->data['online_exam']->class_id;

        $this->data['online_exams'] = $this->online_exam->get_online_exam_list($this->data['online_exam']->class_id, $this->data['online_exam']->subject_id);   
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('online_exam/index', $this->data);
    }

      
           
     /*****************Function get_single_exam**********************************
     * @type            : Function
     * @function name   : get_single_exam
     * @description     : "Load single exam exam information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_single_online_exam(){
        
       $online_exam_id = $this->input->post('online_exam_id');   
       $this->data['online_exam'] = $this->online_exam->get_single_online_exam($online_exam_id);
       echo $this->load->view('online_exam/get-single-online-exam', $this->data);
    }

    
        
    /*****************Function _prepare_exam_validation**********************************
    * @type            : Function
    * @function name   : _prepare_exam_validation
    * @description     : Process "Onlineexam" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_online_exam_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('title',' ' . $this->lang->line('title'), 'trim|required|alpha_numeric_spaces|callback_title');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required');
        $this->form_validation->set_rules('instruction_id', $this->lang->line('instruction'), 'trim|required');
        $this->form_validation->set_rules('duration', $this->lang->line('duration'), 'trim|required');
        $this->form_validation->set_rules('start_date', $this->lang->line('start_date'), 'trim|required');
        $this->form_validation->set_rules('end_date', $this->lang->line('end_date'), 'trim|required');
        $this->form_validation->set_rules('mark_type', $this->lang->line('mark_type'), 'trim|required');
        $this->form_validation->set_rules('pass_mark', $this->lang->line('pass_mark'), 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('exam_limit', $this->lang->line('exam_limit_per_student'), 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('is_publish', $this->lang->line('is_publish'), 'trim|required');
    }

    
    /*****************Function title**********************************
    * @type            : Function
    * @function name   : title
    * @description     : Unique check for "Onlineexam title" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function title() {
        if ($this->input->post('id') == '') {
            $exam = $this->online_exam->duplicate_check($this->input->post('title'), $this->input->post('class_id'), $this->input->post('subject_id'));
            if ($exam) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $exam = $this->online_exam->duplicate_check($this->input->post('title'), $this->input->post('class_id'), $this->input->post('subject_id'), $this->input->post('id'));
            if ($exam) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function _get_posted_exam_data**********************************
    * @type            : Function
    * @function name   : _get_posted_exam_data
    * @description     : Prepare "Onlineexam Onlineexams" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_online_exam_data() {

        $items = array();
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'subject_id';
        $items[] = 'instruction_id';
        $items[] = 'title';
        $items[] = 'duration';
        $items[] = 'mark_type';
        $items[] = 'pass_mark';
        $items[] = 'exam_limit';
        $items[] = 'note';
        $items[] = 'is_publish';
        
        $data = elements($items, $_POST);
        
        $data['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date')));
        $data['end_date'] = date('Y-m-d', strtotime($this->input->post('end_date')));


        if ($this->input->post('id')) {
            $data['status'] = $this->input->post('status');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {            
            $data['academic_year_id'] = $this->academic_year_id;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        }

        return $data;
    }

    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Onlineexams" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('onlineexam/index');
        }
        
        $exam = $this->online_exam->get_single('exam_online_exams', array('id' => $id));
        
        if ($this->online_exam->delete('exam_online_exams', array('id' => $id))) {
            
            create_log('Has been deleted a exam exam : '.$exam->title);   
            success($this->lang->line('delete_sccess'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
       redirect('onlineexam/index');
    }
    
    
        /*****************Function addquestion**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Onlineexam add question" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function addquestion($online_exam_id = null) {

        check_permission(ADD);  
        
        if(!is_numeric($online_exam_id)){
            error($this->lang->line('unexpected_error'));
            redirect('onlineexam/index');
        }
    
        $this->data['online_exam'] = $this->online_exam->get_single_online_exam($online_exam_id);        
        $this->data['online_exams'] = $this->online_exam->get_online_exam_list($this->data['online_exam']->class_id, $this->data['online_exam']->subject_id);
        
        $this->data['class_id'] = $this->data['online_exam']->class_id;
        
        // Exam question class wise
        $this->data['questions'] =  $this->online_exam->get_list('exam_questions', array('status'=>1, 'class_id'=>$this->data['class_id'], 'subject_id'=>$this->data['online_exam']->subject_id), '','', '', 'id', 'ASC');
        
        // Exam wise question id's
        $this->data['exam_questions'] =  $this->online_exam->get_list('exam_to_questions', array('status'=>1, 'online_exam_id'=>$online_exam_id), '','', '', 'id', 'ASC');
        
        $this->data['online_exam_id'] = $online_exam_id;
        
        $this->data['add_question'] = TRUE;
        $this->layout->title($this->lang->line('manage_onlime_exam') . ' | ' . SMS);
        $this->layout->view('online_exam/add_question', $this->data);
        
    }
    
    
    public function add_question_to_exam(){
        
       $online_exam_id = $this->input->post('online_exam_id');
       $question_id =  $this->input->post('question_id');
    
       // first need to check already added or not
       $exist = $this->online_exam->get_single('exam_to_questions', array('online_exam_id' => $online_exam_id, 'question_id'=>$question_id));
       
       $str = '';
       
       if(empty($exist)){
           
            $data = array();         
            $data['online_exam_id'] = $online_exam_id;
            $data['question_id'] = $question_id;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $insert_id = $this->online_exam->insert('exam_to_questions', $data);
            
            // now making html for show data
            if($insert_id){
                
                $exam_questions = $this->online_exam->get_list('exam_to_questions', array('status'=>1, 'online_exam_id'=>$online_exam_id), '','', '', 'id', 'ASC');
                $str =  get_question_detail($online_exam_id, $question_id, count($exam_questions));
                
            }else{
               $str = 1; 
            }
       }else{
           $str = 2;
       }
       
       echo $str;       
       
    }
    
    
    public function remove_question_from_exam(){
        
        $online_exam_id = $this->input->post('online_exam_id');
        $question_id =  $this->input->post('question_id');
        if($this->online_exam->delete('exam_to_questions', array('online_exam_id' => $online_exam_id, 'question_id'=>$question_id))){
            echo TRUE;
        }else{
            echo FALSE;
        }
        
    }
}
