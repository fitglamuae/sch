<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Exam Instruction.php**********************************
 * @exam instruction title    : Global School Management System Pro
 * @type            : Class
 * @class name      : Exam Instructions
 * @description     : Manage school academic exam instruction.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Instruction extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Instruction_Model', 'instruction', true);        
    }

    
    /*****************Function exam instruction**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Exam Instruction List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['instructions'] = $this->instruction->get_instruction_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_exam_instruction') . ' | ' . SMS);
        $this->layout->view('instruction/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Exam Instruction" user interface                 
    *                    and process to store "Exam Instructions" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_instruction_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_instruction_data();

                $insert_id = $this->instruction->insert('exam_instructions', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a exam instruction : '.$data['title']); 
                    success($this->lang->line('insert_success'));                    
                    redirect('onlineexam/instruction/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('onlineexam/instruction/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['instructions'] = $this->instruction->get_instruction_list();        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('instruction/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Exam Instruction" user interface                 
    *                    with populated "Exam Instruction" value 
    *                    and process update "Exam Instruction" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('onlineexam/instruction/index');
        }
        
        if ($_POST) {
            
            $this->_prepare_instruction_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_instruction_data();
                $updated = $this->instruction->update('exam_instructions', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a exam instruction : '.$data['title']);
                    success($this->lang->line('update_success'));
                    redirect('onlineexam/instruction/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('onlineexam/instruction/edit/' . $this->input->post('id'));
                }
            } else {
                
                $this->data['instruction'] = $this->instruction->get_single('exam_instructions', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            
            $this->data['instruction'] = $this->instruction->get_single('exam_instructions', array('id' => $id));

            if (!$this->data['instruction']) {
                redirect('onlineexam/instruction/index');
            }
        }

        $this->data['instructions'] = $this->instruction->get_instruction_list();      
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('instruction/index', $this->data);
    }

      
           
     /*****************Function get_single_instruction**********************************
     * @type            : Function
     * @function name   : get_single_instruction
     * @description     : "Load single exam instruction information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    
    public function get_single_instruction(){
        
       $instruction_id = $this->input->post('id');   
       $this->data['instruction'] = $this->instruction->get_single('exam_instructions', array('id' => $instruction_id));
       echo $this->load->view('instruction/get-single-instruction', $this->data);
    }

    
        
    /*****************Function _prepare_instruction_validation**********************************
    * @type            : Function
    * @function name   : _prepare_instruction_validation
    * @description     : Process "Exam Instructions" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_instruction_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('title',' ' . $this->lang->line('title'), 'trim|required|alpha_numeric_spaces|callback_title');
        $this->form_validation->set_rules('instruction', $this->lang->line('instruction'), 'trim|required');
    }

    
    /*****************Function title**********************************
    * @type            : Function
    * @function name   : title
    * @description     : Unique check for "Exam Instruction title" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function title() {
        if ($this->input->post('id') == '') {
            $instruction = $this->instruction->duplicate_check($this->input->post('title'));
            if ($instruction) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $instruction = $this->instruction->duplicate_check($this->input->post('title'), $this->input->post('id'));
            if ($instruction) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function _get_posted_instruction_data**********************************
    * @type            : Function
    * @function name   : _get_posted_instruction_data
    * @description     : Prepare "Exam Instructions" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_instruction_data() {

        $items = array();
        $items[] = 'title';
        $items[] = 'instruction';
        
        $data = elements($items, $_POST);


        if ($this->input->post('id')) {
            $data['status'] = $this->input->post('status');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
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
    * @description     : delete "Exam Instructions" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('onlineexam/instruction/index');
        }
        
        $instruction = $this->instruction->get_single('exam_instructions', array('id' => $id));
        
        if ($this->instruction->delete('exam_instructions', array('id' => $id))) {
            
            create_log('Has been deleted a exam instruction : '.$instruction->title);   
            success($this->lang->line('delete_sccess'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
       redirect('onlineexam/instruction/index');
    }

}
