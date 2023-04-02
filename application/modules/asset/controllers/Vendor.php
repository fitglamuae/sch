<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************vendor.php**********************************
 * @svendor title    : Global School Management System Pro
 * @type            : vendor
 * @class name      : vendor
 * @description     : Manage school academic vendor for student, guardian, teacer and employee.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Vendor extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Vendor_Model', 'vendor', true);        
    }

    
    /*****************Function vendor**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "vendor List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['vendors'] = $this->vendor->get_vendor_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_vendor') . ' | ' . SMS);
        $this->layout->view('vendor/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new vendor" user interface                 
    *                    and process to store "vendor" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_vendor_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_vendor_data();

                $insert_id = $this->vendor->insert('asset_vendors', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a vendor : '.$data['name']);
                    success($this->lang->line('insert_success'));
                    redirect('asset/vendor/index')
                    ;
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('asset/vendor/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['vendors'] = $this->vendor->get_vendor_list();
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('vendor/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "vendor" user interface                 
    *                    with populated "vendor" value 
    *                    and process update "vendor" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('asset/vendor/index');
        }
        
        if ($_POST) {
            $this->_prepare_vendor_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_vendor_data();
                $updated = $this->vendor->update('asset_vendors', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    create_log('Has been updated a vendor : '.$data['name']); 
                    success($this->lang->line('update_success'));
                    redirect('asset/vendor/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('asset/vendor/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['vendor'] = $this->vendor->get_single('asset_vendors', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['vendor'] = $this->vendor->get_single('asset_vendors', array('id' => $id));

            if (!$this->data['vendor']) {
                redirect('asset/vendor/index');
            }
        }

        $this->data['vendors'] = $this->vendor->get_vendor_list();
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('vendor/index', $this->data);
    }

      
           
     /*****************Function get_single_vendor**********************************
     * @type            : Function
     * @function name   : get_single_vendor
     * @description     : "Load single vendor information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_vendor(){
        
       $vendor_id = $this->input->post('id');   
       $this->data['vendor'] = $this->vendor->get_single('asset_vendors', array('id' => $vendor_id));
       echo $this->load->view('vendor/get-single-vendor', $this->data);
    }

    
        
    /*****************Function _prepare_vendor_validation**********************************
    * @type            : Function
    * @function name   : _prepare_vendor_validation
    * @description     : Process "vendor" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_vendor_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|callback_name');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('contact', $this->lang->line('contact_name'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

    
    /*****************Function name**********************************
    * @type            : Function
    * @function name   : name
    * @description     : Unique check for "vendor title" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        
        if ($this->input->post('id') == '') {
            $vendor = $this->vendor->duplicate_check($this->input->post('name'));
            if ($vendor) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            
            $vendor = $this->vendor->duplicate_check($this->input->post('name'), $this->input->post('id'));
            if ($vendor) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    
    /*****************Function _get_posted_vendor_data**********************************
    * @type            : Function
    * @function name   : _get_posted_vendor_data
    * @description     : Prepare "vendor" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_vendor_data() {

        $items = array();
        $items[] = 'name';
        $items[] = 'email';
        $items[] = 'phone';
        $items[] = 'contact';
        $items[] = 'address';
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
    * @description     : delete "contact" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('asset/vendor/index');
        }
        
        $vendor = $this->vendor->get_single('asset_vendors', array('id' => $id));
        
        if ($this->vendor->delete('asset_vendors', array('id' => $id))) {
            
            create_log('Has been deleted a vendor : '.$vendor->name);   
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
       redirect('asset/vendor/index');
    }

}
