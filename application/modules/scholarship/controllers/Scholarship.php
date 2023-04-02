<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Scholarship.php**********************************
 * @scholarship name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Scholarship
 * @description     : Manage application.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Scholarship extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Scholarship_Model', 'scholarship', true);  
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('setting');
        }
        
        $this->data['balance'] = $this->scholarship->get_single('ss_balance', array('status' => 1)); 
    }

   
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Scholarship List" user interface                 
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
        
        $this->data['scholarships'] = $this->scholarship->get_scholarship_list($class_id);  
        $this->data['candidates'] = $this->scholarship->get_candidate_list();
        $this->data['classes'] = $this->scholarship->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
                
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_scholarship'). ' | ' . SMS);
        $this->layout->view('index', $this->data);
        
    }   

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Scholarship" user interface                 
    *                    and process to store "Scholarship" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_scholarship_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_scholarship_data();

                $insert_id = $this->scholarship->insert('ss_scholarships', $data);
                if ($insert_id) {
                                        
                    create_log('Has been added scholarship');                     
                    success($this->lang->line('insert_success'));
                    redirect('scholarship/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('scholarship/add');
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
        
        $this->data['scholarships'] = $this->scholarship->get_scholarship_list($class_id);               
        $this->data['candidates'] = $this->scholarship->get_candidate_list();
        $this->data['classes'] = $this->scholarship->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['add'] = TRUE;        
        $this->layout->title($this->lang->line('add') .' | '. SMS);
        $this->layout->view('index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Scholarship" user interface                 
    *                    with populated "Scholarship" value 
    *                    and process to update "Scholarship" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('scholarship/index');
        }
       
        if ($_POST) {
            $this->_prepare_scholarship_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_scholarship_data();
                $updated = $this->scholarship->update('ss_scholarships', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated scholarship');                    
                    success($this->lang->line('update_success'));
                    redirect('scholarship/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('scholarship/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['scholarship'] = $this->scholarship->get_single_scholarship($this->input->post('id'));
            }
        }

        if ($id) {
            $this->data['scholarship'] = $this->scholarship->get_single_scholarship($id);
            if (!$this->data['scholarship']) {
                redirect('scholarship/index');
            }
        }
        
        $class_id = $this->data['scholarship']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 
        
        $this->data['class_id'] = $class_id;

        $this->data['scholarships'] = $this->scholarship->get_scholarship_list($class_id);               
        $this->data['candidates'] = $this->scholarship->get_candidate_list();
        $this->data['classes'] = $this->scholarship->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
               
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('index', $this->data);
    }

       
           
     /*****************Function get_single_scholarship**********************************
     * @type            : Function
     * @function name   : get_single_scholarship
     * @description     : "Load single scholarship information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_scholarship(){
        
       $scholarship_id = $this->input->post('id');   
       $this->data['scholarship'] = $this->scholarship->get_single_scholarship($scholarship_id);
       echo $this->load->view('get-single-scholarship', $this->data);
    }

    
    /*****************Function _prepare_scholarship_validation**********************************
    * @type            : Function
    * @function name   : _prepare_scholarship_validation
    * @description     : Process "scholarship user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_scholarship_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
       
        $this->form_validation->set_rules('candidate_id', $this->lang->line('candidate'), 'trim|required|callback_candidate_id');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required');
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
        
    }
    
    
    /*****************Function candidate_id**********************************
    * @type            : Function
    * @function name   : candidate_id
    * @description     : Unique check for "Candidate" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */  
   public function candidate_id()
   {             
      if($this->input->post('id') == '')
      {   
          $candidate = $this->scholarship->duplicate_check($this->input->post('candidate_id')); 
          if($candidate){
                $this->form_validation->set_message('candidate_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $candidate = $this->scholarship->duplicate_check($this->input->post('id'), $this->input->post('candidate_id')); 
          if($candidate){
                $this->form_validation->set_message('candidate_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }else{
          return TRUE;
      }      
   }
                        
   
    
    
    /*****************Function _get_posted__data**********************************
    * @type            : Function
    * @function name   : _get_posted_scholarship_data
    * @description     : Prepare "scholarship" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_scholarship_data() {

        $items = array();
        $items[] = 'candidate_id';
        $items[] = 'amount';
        $items[] = 'note';

        $data = elements($items, $_POST);
        
        $data['payment_date'] = date('Y-m-d', strtotime($this->input->post('payment_date')));
        
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
        
        $this->__update_balance();
        
        return $data;
    }

        
 
     
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Scholarship" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
            
            error($this->lang->line('unexpected_error'));
            redirect('scholarship/index');
        }
        
        $scholarship = $this->scholarship->get_single('ss_scholarships', array('id' => $id));
        
        if ($this->scholarship->delete('ss_scholarships', array('id' => $id))) { 
            
            // reduce donar amount from main balance
            $sql = "UPDATE ss_balance SET balance = balance+$scholarship->amount , modified_at = '".date('Y-m-d H:i:s')."' WHERE status=1";
            $this->db->query($sql);            
            success($this->lang->line('delete_success'));            
            
        } else {
            
            error($this->lang->line('delete_failed'));
        }
        
        redirect('scholarship/index');
    }
    
    
    
    private function __update_balance(){
     
        $balance = $this->scholarship->get_single('ss_balance', array('status' => 1));       
    
        $amount = $this->input->post('amount');
        
        if ($this->input->post('id')) {
            
            $old_amount = $this->input->post('old_amount');
            
            if(($balance->balance + $old_amount) < $amount){
               
                error($this->lang->line('insufficient_balance'));
                redirect('scholarship/edit/' . $this->input->post('id'));     
            }
            
            $sql = "UPDATE ss_balance SET balance = balance+$old_amount , modified_at = '".date('Y-m-d H:i:s')."' WHERE status=1";
            $this->db->query($sql);
            
            $sql = "UPDATE ss_balance SET balance = balance-$amount , modified_at = '".date('Y-m-d H:i:s')."' WHERE status=1";
            $this->db->query($sql);
            
        }else{
            
            if($balance->balance < $amount){
               
                error($this->lang->line('insufficient_balance'));
                redirect('scholarship/add');     
            }
            
            $sql = "UPDATE ss_balance SET balance = balance-$amount , modified_at = '".date('Y-m-d H:i:s')."' WHERE status=1";
            $this->db->query($sql);
        }        
    }        
        
}