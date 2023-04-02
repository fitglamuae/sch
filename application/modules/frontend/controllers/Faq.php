<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Faq.php**********************************
 * @faq title    : Global School Management System Pro
 * @type            : Class
 * @class name      : Faq
 * @description     : Manage school academic faq for student, guardian, teacer and employee.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Faq extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Faq_Model', 'faq', true);        
    }

    
    /*****************Function faq**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Faq List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['faqs'] = $this->faq->get_faq_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_faq') . ' | ' . SMS);
        $this->layout->view('faq/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Faqs" user interface                 
    *                    and process to store "Faqs" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_faq_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_faq_data();

                $insert_id = $this->faq->insert('faqs', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a faq : '.$data['title']);   
                    
                    success($this->lang->line('insert_success'));
                    redirect('frontend/faq/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('frontend/faq/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['faqs'] = $this->faq->get_faq_list();        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('faq/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Faqs" user interface                 
    *                    with populated "Faqs" value 
    *                    and process update "Faqs" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('frontend/faq/index');
        }
        
        if ($_POST) {
            $this->_prepare_faq_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_faq_data();
                $updated = $this->faq->update('faqs', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                     create_log('Has been updated a faq : '.$data['title']);   
                    
                    success($this->lang->line('update_success'));
                    redirect('frontend/faq/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('frontend/faq/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['faq'] = $this->faq->get_single('faqs', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['faq'] = $this->faq->get_single('faqs', array('id' => $id));

            if (!$this->data['faq']) {
                redirect('frontend/faq/index');
            }
        }

        $this->data['faqs'] = $this->faq->get_faq_list();      
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('faq/index', $this->data);
    }

      
           
     /*****************Function get_single_faq**********************************
     * @type            : Function
     * @function name   : get_single_faq
     * @description     : "Load single faq information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_faq(){
        
       $faq_id = $this->input->post('id');   
        $this->data['faq'] = $this->faq->get_single('faqs', array('id' => $faq_id));
       echo $this->load->view('faq/get-single-faq', $this->data);
    }

    
        
    /*****************Function _prepare_faq_validation**********************************
    * @type            : Function
    * @function name   : _prepare_faq_validation
    * @description     : Process "Faqs" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_faq_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('title',' ' . $this->lang->line('title'), 'trim|required|callback_title');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');
    }

    
    /*****************Function title**********************************
    * @type            : Function
    * @function name   : title
    * @description     : Unique check for "Faq title" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function title() {
        if ($this->input->post('id') == '') {
            $faq = $this->faq->duplicate_check($this->input->post('title'));
            if ($faq) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $faq = $this->faq->duplicate_check($this->input->post('title'), $this->input->post('id'));
            if ($faq) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function _get_posted_faq_data**********************************
    * @type            : Function
    * @function name   : _get_posted_notice_data
    * @description     : Prepare "Faqs" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_faq_data() {

        $items = array();
        $items[] = 'title';
        $items[] = 'description';
        
        $data = elements($items, $_POST);


        if ($this->input->post('id')) {
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
    * @description     : delete "Faqs" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('frontend/faq/index');
        }
        
        $faq = $this->faq->get_single('faqs', array('id' => $id));
        
        if ($this->faq->delete('faqs', array('id' => $id))) {
            
            create_log('Has been deleted a faq : '.$faq->title);   
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
       redirect('frontend/faq/index');
       
    }
}