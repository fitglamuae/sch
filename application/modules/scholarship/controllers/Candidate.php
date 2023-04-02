<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Candidate.php**********************************
 * @candidate name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : condidate
 * @description     : Manage Schlarship application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Candidate extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Candidate_Model', 'candidate', true);  
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('setting');
        }
    }

   
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "candidate List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);
              
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/subject/index');    
        }
        
        $this->data['class_id'] = $class_id;        
        
        $this->data['candidates'] = $this->candidate->get_candidate_list($class_id);               
        $this->data['classes'] = $this->candidate->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_candidate'). ' | ' . SMS);
        $this->layout->view('candidate/index', $this->data);
        
    }   

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Candidate" user interface                 
    *                    and process to store "Candidate" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_candidate_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_candidate_data();

                $insert_id = $this->candidate->insert('ss_candidates', $data);
                if ($insert_id) {
                    
                    create_log('Has been added candidate');                     
                    success($this->lang->line('insert_success'));
                    redirect('scholarship/candidate/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('scholarship/candidate/add');
                }
            } else {
                $this->data['post'] = $_POST;                
            }
        }
        
        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        
        $this->data['class_id'] = $class_id;        
        
        $this->data['candidates'] = $this->candidate->get_candidate_list($class_id);               
        $this->data['classes'] = $this->candidate->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['add'] = TRUE;        
        $this->layout->title($this->lang->line('add') .' | '. SMS);
        $this->layout->view('candidate/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Candidate" user interface                 
    *                    with populated "Candidate" value 
    *                    and process to update "Candidate" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('scholarship/candidate/index');
        }
       
        if ($_POST) {
            $this->_prepare_candidate_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_candidate_data();
                $updated = $this->candidate->update('ss_candidates', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated candidate');                    
                    success($this->lang->line('update_success'));
                    redirect('scholarship/candidate/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('scholarship/candidate/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['candidate'] = $this->candidate->get_single_candidate($this->input->post('id'));
            }
        }

        if ($id) {
            $this->data['candidate'] = $this->candidate->get_single_candidate($id);
            if (!$this->data['candidate']) {
            redirect('scholarship/candidate/index');
            }
        }
        
        $class_id = $this->data['candidate']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;

        $this->data['candidates'] = $this->candidate->get_candidate_list($class_id);               
        $this->data['classes'] = $this->candidate->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
               
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('scholarship/candidate/index', $this->data);
    }

       
     /*****************Function get_single_candidate**********************************
     * @type            : Function
     * @function name   : get_single_candidate
     * @description     : "Load single candidate information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_candidate(){
        
       $candidate_id = $this->input->post('id');   
       $this->data['candidate'] = $this->candidate->get_single_candidate($candidate_id);
       echo $this->load->view('candidate/get-single-candidate', $this->data);
    }

    
    /*****************Function _prepare_candidate_validation**********************************
    * @type            : Function
    * @function name   : _prepare_candidate_validation
    * @description     : Process "candidate user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_candidate_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|callback_student_id');
        $this->form_validation->set_rules('note', $this->lang->line('note'),'trim');
    }
    
    
    /*****************Function student_id**********************************
    * @type            : Function
    * @function name   : student_id
    * @description     : Unique check for "student name" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */  
   public function student_id()
   {             
      if($this->input->post('id') == '')
      {   
          $student = $this->candidate->duplicate_check($this->input->post('student_id')); 
          if($student){
                $this->form_validation->set_message('student_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $student = $this->candidate->duplicate_check($this->input->post('student_id'), $this->input->post('id')); 
          if($student){
                $this->form_validation->set_message('student_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }else{
          return TRUE;
      }      
   }
    
    
    /*****************Function _get_posted_candidate_data**********************************
    * @type            : Function
    * @function name   : _get_posted_candidate_data
    * @description     : Prepare "Candidate" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_candidate_data() {

        $items = array();
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'student_id';
        $items[] = 'note';

        $data = elements($items, $_POST);
        
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();            
            
        } else {
            
            $data['candidate_status'] = 1;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();   
            $data['academic_year_id'] = $this->academic_year_id;
            
        }
        return $data;
    }

        
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "candidate" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('scholarship/candidate/index');
        }        
        
        if ($this->candidate->delete('ss_candidates', array('id' => $id))) {
            success($this->lang->line('delete_success'));
            redirect('scholarship/candidate/index');
            
        } else {
            error($this->lang->line('delete_failed'));
        }    
        
        redirect('scholarship/candidate/index');
    }
    
    
}
