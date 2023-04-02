<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Todo.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Todo
 * @description     : Manage application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Todo extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Todo_Model', 'todo', true);  
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('setting');
        }
    }

   
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Todo List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);
                         
        $this->data['todos'] = $this->todo->get_todo_list();               
        $this->data['classes'] = $this->todo->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['roles'] = $this->todo->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
                
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_todo'). ' | ' . SMS);
        $this->layout->view('todo/index', $this->data);
        
    }   

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Todo" user interface                 
    *                    and process to store "Todo" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_todo_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_todo_data();

                $insert_id = $this->todo->insert('todos', $data);
                if ($insert_id) {
                    
                    create_log('Has been added todo');                     
                    success($this->lang->line('insert_success'));
                    redirect('miscellaneous/todo/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('miscellaneous/todo/add');
                }
            } else {
                $this->data['post'] = $_POST;                
            }
        }
        
        $this->data['todos'] = $this->todo->get_todo_list();      
        $this->data['classes'] = $this->todo->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['roles'] = $this->todo->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        
        $this->layout->title($this->lang->line('add') .' | '. SMS);
        $this->layout->view('todo/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Todo" user interface                 
    *                    with populated "Todo" value 
    *                    and process to update "Todo" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('miscellaneous/todo/index');
        }
       
        if ($_POST) {
            $this->_prepare_todo_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_todo_data();
                $updated = $this->todo->update('todos', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated todo');                    
                    success($this->lang->line('update_success'));
                    redirect('miscellaneous/todo/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('miscellaneous/todo/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['todo'] = $this->todo->get_single_todo($this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['todo'] = $this->todo->get_single_todo($id);
            if (!$this->data['todo']) {
                redirect('miscellaneous/todo/index');
            }
        }

        $this->data['todos'] = $this->todo->get_todo_list();   
        $this->data['classes'] = $this->todo->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');     
        $this->data['roles'] = $this->todo->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
               
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('miscellaneous/todo/index', $this->data);
    }

       
           
     /*****************Function get_single_todo**********************************
     * @type            : Function
     * @function name   : get_single_todo
     * @description     : "Load single todo information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_todo(){
        
       $todo_id = $this->input->post('id');   
       $this->data['todo'] = $this->todo->get_single_todo($todo_id);
       echo $this->load->view('todo/get-single-todo', $this->data);
    }

    
    /*****************Function _prepare_todo_validation**********************************
    * @type            : Function
    * @function name   : _prepare_todo_validation
    * @description     : Process "todo user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_todo_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('role_id', $this->lang->line('user_type'), 'trim|required');
        
        if($this->input->post('role_id') == STUDENT){
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        }
        
        $this->form_validation->set_rules('user_id', $this->lang->line('todo'), 'trim|required');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim');
        $this->form_validation->set_rules('comment', $this->lang->line('note'),'trim');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required');
        $this->form_validation->set_rules('work', $this->lang->line('work_status'), 'trim|required');
        
    }
    
    
                        
   
    
    
    /*****************Function _get_posted_todo_data**********************************
    * @type            : Function
    * @function name   : _get_posted_todo_data
    * @description     : Prepare "Todo" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_todo_data() {

        $items = array();
        $items[] = 'role_id';
        $items[] = 'user_id';
        $items[] = 'class_id';
        $items[] = 'title';
        $items[] = 'description';
        $items[] = 'comment';
        $items[] = 'work';

        $data = elements($items, $_POST);
        
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();            
            
        } else {
            
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();   
            $data['academic_year_id'] = $this->academic_year_id;
            
        }
       // print_r($data);die();
        return $data;
    }

        
 
     
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Todo" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('miscellaneous/todo/index');
        }
        
        $todo = $this->todo->get_single_todo($id);
        if ($this->todo->delete('todos', array('id' => $id))) {
            create_log('Has been deleted a todo : '.$todo->title);
            success($this->lang->line('delete_success'));
            redirect('miscellaneous/todo/index');
            
        } else {
            error($this->lang->line('delete_failed'));
        }       
        redirect('miscellaneous/todo/index');
    }
        
}
