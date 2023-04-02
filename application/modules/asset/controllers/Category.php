<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************category.php**********************************
 * @category title    : Global School Management System Pro
 * @type            : Catagory
 * @class name      : category
 * @description     : Manage school assets category.  
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

                $insert_id = $this->category->insert('asset_categories', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a category : '.$data['name']); 
                    success($this->lang->line('insert_success'));
                    redirect('asset/category/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('asset/category/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['categorys'] = $this->category->get_category_list(); 
        
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
            redirect('asset/category/index');
        }
        
        if ($_POST) {
            $this->_prepare_category_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_category_data();
                $updated = $this->category->update('asset_categories', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a category : '.$data['name']);  
                    success($this->lang->line('update_success'));
                    redirect('asset/category/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('asset/category/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['category'] = $this->category->get_single('asset_categories', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['category'] = $this->category->get_single('asset_categories', array('id' => $id));

            if (!$this->data['category']) {
                redirect('asset/category/index');
            }
        }

        $this->data['categorys'] = $this->category->get_category_list(); 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('category/index', $this->data);
    }

    
     /*****************Function _prepare_category_validation**********************************
    * @type            : Function
    * @function name   : _prepare_categoory_validation
    * @description     : Process "Category" user input data validation                 
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
    * @function name   : title
    * @description     : Unique check for "store name" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        if ($this->input->post('id') == '') {
            $store = $this->category->duplicate_check($this->input->post('name'));
            if ($store) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $store = $this->category->duplicate_check($this->input->post('name'), $this->input->post('id'));
            if ($store) {
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
            redirect('asset/category/index');
        }
        
        $category = $this->category->get_single('asset_categories', array('id' => $id));
        
        if ($this->category->delete('asset_categories', array('id' => $id))) {            
            create_log('Has been deleted a category : '.$category->name);   
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
       redirect('asset/category/index');
    }

}
