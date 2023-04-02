<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Group.php**********************************
 * @product name    : Global Multi School Management System Express
 * @Group            : Group
 * @class name      : Group
 * @description     : Manage student group.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Group extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Group_Model', 'group', true);        
    }

    
    /*****************Function index**********************************
    * @Group            : Function
    * @function name   : index
    * @description     : Load "Group List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);        
        $this->data['groups'] = $this->group->get_group();        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_student_group').' | ' . SMS);
        $this->layout->view('group/index', $this->data);  
    }

    
    /*****************Function add**********************************
    * @Group            : Function
    * @function name   : add
    * @description     : Load "Add new student Group" user interface                 
    *                    and process to store "student Group" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

         check_permission(ADD);
        if ($_POST) {
            $this->_prepare_group_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_group_data();

                $insert_id = $this->group->insert('student_groups', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a student Group : '.$data['group']);                    
                    success($this->lang->line('insert_success'));
                    redirect('student/group/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('student/group/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['groups'] = $this->group->get_group(); 
               
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' | ' . SMS);
        $this->layout->view('group/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @Group            : Function
    * @function name   : edit
    * @description     : Load Update "student Group" user interface                 
    *                    with populate "student Group" value 
    *                    and process to update "student Group" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {       

         check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_group_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_group_data();
                $updated = $this->group->update('student_groups', $data, array('id' => $this->input->post('id')));

                if ($updated) {                    
                    create_log('Has been updated a student Group : '.$data['group']);                    
                    success($this->lang->line('update_success'));
                    redirect('student/group/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('student/group/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['group'] = $this->group->get_single('student_groups', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['group'] = $this->group->get_single('student_groups', array('id' => $id));

                if (!$this->data['group']) {
                     redirect('student/group/index');
                }
            }
        }
       
        $this->data['groups'] = $this->group->get_group();       
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit'). ' | ' . SMS);
        $this->layout->view('group/index', $this->data);
    }

        
    /*****************Function _prepare_group_validation**********************************
    * @Group            : Function
    * @function name   : _prepare_group_validation
    * @description     : Process "student Group" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_group_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('group', $this->lang->line('student_group'), 'trim|required|alpha_numeric_spaces|callback_group');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

                    
    /*****************Function group**********************************
    * @Group            : Function
    * @function name   : group
    * @description     : Unique check for "student Group " data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function group() {
        if ($this->input->post('id') == '') {
            $group = $this->group->duplicate_check($this->input->post('group'));
            if ($group) {
                $this->form_validation->set_message('group', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $group = $this->group->duplicate_check($this->input->post('group'), $this->input->post('id'));
            if ($group) {
                $this->form_validation->set_message('group', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
       
    /*****************Function _get_posted_group_data**********************************
    * @Group            : Function
    * @function name   : _get_posted_group_data
    * @description     : Prepare "Group" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_group_data() {

        $items = array();
        $items[] = 'group';
        $items[] = 'note';
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['status'] = $this->input->post('status');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $data['status'] = 1;
        }
        return $data;
    }    
        
    
    /*****************Function delete**********************************
    * @Group            : Function
    * @function name   : delete
    * @description     : delete "student Group" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
         
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('student/group/index');        
        }
        
        $group = $this->group->get_single('student_groups', array('id' => $id));        
        if ($this->group->delete('student_groups', array('id' => $id))) { 
            
            create_log('Has been deleted a student Group : '.$group->group);
            success($this->lang->line('delete_success'));
            redirect('student/group/index');
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('student/group/index');
    }
}
