<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Category.php**********************************
 * @category title    : Global School Management System Pro
 * @type            : Class
 * @class name      : Category
 * @description     : Manage school inventory Category.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Category extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Category_Model', 'category', true);        
    }

    
    /*****************Function category**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "category List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['categories'] = $this->category->get_category_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_category') . ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new category" user interface                 
    *                    and process to store "category" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_category_data();

                $insert_id = $this->category->insert('item_categories', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a category : '.$data['name']); 
                    success($this->lang->line('insert_success'));
                    redirect('inventory/category/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('inventory/category/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['categories'] = $this->category->get_category_list();        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "category" user interface                 
    *                    with populated "category" value 
    *                    and process update "category" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('inventory/category/index');
        }
        
        if ($_POST) {
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_category_data();
                $updated = $this->category->update('item_categories', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    create_log('Has been updated a category : '.$data['name']);   
                    success($this->lang->line('update_success'));
                    redirect('inventory/category/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('inventory/category/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['category'] = $this->category->get_single('item_categories', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['category'] = $this->category->get_single('item_categories', array('id' => $id));

            if (!$this->data['category']) {
                redirect('inventory/category/index');
            }
        }

        $this->data['categories'] = $this->category->get_category_list();      
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }

    
        
    /*****************Function _prepare_category_validation**********************************
    * @type            : Function
    * @function name   : _prepare_category_validation
    * @description     : Process "category" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_category_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('name',' ' . $this->lang->line('name'), 'trim|required|callback_name');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

    
    /*****************Function name**********************************
    * @type            : Function
    * @function name   : name
    * @description     : Unique check for "category name" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        if ($this->input->post('id') == '') {
            $cat = $this->category->duplicate_check($this->input->post('name'));
            if ($cat) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $cat = $this->category->duplicate_check($this->input->post('name'), $this->input->post('id'));
            if ($cat) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function _get_posted_category_data**********************************
    * @type            : Function
    * @function name   : _get_posted_category_data
    * @description     : Prepare "category" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_category_data() {

        $items = array();
        $items[] = 'name';
        $items[] = 'note';
        
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
    * @description     : delete "category" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('inventory/category/index');
        }
        
        $category = $this->category->get_single('item_categories', array('id' => $id));
        
        if ($this->category->delete('item_categories', array('id' => $id))) {
            
            create_log('Has been deleted a item category : '.$category->name);   
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
       redirect('inventory/category/index');
    }

}
