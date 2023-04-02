<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Student.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Student
 * @description     : Manage students imformation of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Student extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();      
        
        $this->load->model('Student_Model', 'student', true);
        // check running session
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('setting');
        }
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Student List" user interface                 
    *                    with class wise listing    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/classes/index');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['students'] = $this->student->get_student_list($class_id);
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['discounts'] = $this->student->get_list('discounts', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['types'] = $this->student->get_list('student_types', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['groups'] = $this->student->get_list('student_groups', array('status'=> 1), '', '', '', 'id', 'ASC');  
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_student') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Student" user interface                 
    *                    and process to store "Student" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_student_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();

                $insert_id = $this->student->insert('students', $data);

                if ($insert_id) {
                    $this->__insert_enrollment($insert_id);
                    
                    create_log('Has been added a Student : '.$data['name']);
                    success($this->lang->line('insert_success'));
                    redirect('student/index/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('student/add/'.$this->input->post('class_id'));
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
        $this->data['students'] = $this->student->get_student_list($class_id);
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['discounts'] = $this->student->get_list('discounts', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['types'] = $this->student->get_list('student_types', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['groups'] = $this->student->get_list('student_groups', array('status'=> 1), '', '', '', 'id', 'ASC');  
        
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Student" user interface                 
    *                    with populate "Student" value 
    *                    and process to update "Student" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/index');     
        }
        
        if ($_POST) {
            $this->_prepare_student_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();
                $updated = $this->student->update('students', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    $this->__update_enrollment();
                    
                    create_log('Has been updated a Student : '.$data['name']);
                    success($this->lang->line('update_success'));
                    redirect('student/index/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('student/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['student'] = $this->student->get_single_student($this->input->post('id'));
            }
        }

        if ($id) {
            $this->data['student'] = $this->student->get_single_student($id);

            if (!$this->data['student']) {
                redirect('student/index');
            }
        }
        
        $class_id = $this->data['student']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 

        $this->data['class_id'] = $class_id;
        $this->data['students'] = $this->student->get_student_list($class_id);
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['discounts'] = $this->student->get_list('discounts', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['types'] = $this->student->get_list('student_types', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['groups'] = $this->student->get_list('student_groups', array('status'=> 1), '', '', '', 'id', 'ASC');  
        
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('student/index', $this->data);
    }

        
    
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific Student data                 
    *                       
    * @param           : $student_id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($student_id = null) {

        check_permission(VIEW);

        if(!is_numeric($student_id)){
             error($this->lang->line('unexpected_error'));
              redirect('student/index');
        }
        
        $this->load->helper('report');
        
        $this->data['student'] = $this->student->get_single_student($student_id);        
        $class_id = $this->data['student']->class_id;
        
        $this->data['guardian'] = $this->student->get_single_guardian($this->data['student']->guardian_id);
      
        $this->data['exams'] = $this->student->get_list('exams', array('status' => 1, 'academic_year_id' => $this->academic_year_id), '', '', '', 'id', 'ASC');
        
        $this->data['invoices'] = $this->student->get_invoice_list($student_id);  
        $this->data['activity'] = $this->student->get_activity_list($student_id);
        
        $this->data['days'] = 31;
        $this->data['academic_year_id'] = $this->data['student']->academic_year_id;
        $this->data['class_id'] = $this->data['student']->class_id;
        $this->data['section_id'] = $this->data['student']->section_id;
        $this->data['student_id'] = $this->data['student']->id;
        $this->data['class_id'] = $class_id;  
        
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') .' | ' . SMS);        
        
        
        $this->layout->view('student/index', $this->data);
    }
    
    
    
     /*****************Function get_single_student**********************************
     * @type            : Function
     * @function name   : get_single_student
     * @description     : "Load single student information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_student(){
        
        $this->load->helper('report');
        $student_id = $this->input->post('student_id');
        $adv = $this->input->post('adv');

        $this->data['student'] = $this->student->get_single_student($student_id, $adv);
        
        $this->data['guardian'] = $this->student->get_single_guardian($this->data['student']->guardian_id);
        $this->data['days'] = 31;
        $this->data['academic_year_id'] = $this->data['student']->academic_year_id;
        $this->data['class_id'] = $this->data['student']->class_id;
        $this->data['section_id'] = $this->data['student']->section_id;
        $this->data['student_id'] = $this->data['student']->id;
        
        $this->data['exams'] = $this->student->get_list('exams', array('status' => 1, 'academic_year_id' => $this->academic_year_id), '', '', '', 'id', 'ASC');
        
        $this->data['invoices'] = $this->student->get_invoice_list($student_id);  
        $this->data['activity'] = $this->student->get_activity_list($student_id);  
        
        echo $this->load->view('get-single-student', $this->data);
    }
    
        
    /*****************Function _prepare_student_validation**********************************
    * @type            : Function
    * @function name   : _prepare_student_validation
    * @description     : Process "Student" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_student_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        if (!$this->input->post('id')) {
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|valid_email|callback_email');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[6]|max_length[20]');  
            $this->form_validation->set_rules('is_guardian',  $this->lang->line('is_guardian'), 'trim|required');
        }

        $this->form_validation->set_rules('phone',  $this->lang->line('phone'), 'trim|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('roll_no', $this->lang->line('roll_no'), 'trim|required'); 
        $this->form_validation->set_rules('admission_date', $this->lang->line('admission_date'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');

        $this->form_validation->set_rules('registration_no', $this->lang->line('registration_no'), 'trim');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('present_address', $this->lang->line('present_address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent_address'), 'trim');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('caste', $this->lang->line('caste'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('second_language', $this->lang->line('second_language'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('other_info', $this->lang->line('other_info'), 'trim');
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'trim|callback_photo');
        
                
        
        if ($this->input->post('is_guardian') == 'exist_guardian') {
            $this->form_validation->set_rules('guardian_id',  $this->lang->line('guardian'), 'trim|required');
        }
        
        if ($this->input->post('is_guardian') != 'exist_guardian' && !$this->input->post('id')) {
            $this->form_validation->set_rules('gud_email',  $this->lang->line('email'), 'trim|required|valid_email|callback_gud_email');
            $this->form_validation->set_rules('gud_name',  $this->lang->line('guardian_name'), 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('gud_phone',  $this->lang->line('guardian_phone'), 'trim|required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('gud_profession',  $this->lang->line('gud_profession'), 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('gud_religion',  $this->lang->line('gud_religion'), 'trim|alpha_numeric_spaces');
        }  
        
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'trim|callback_photo');
        //$this->form_validation->set_rules('transfer_certificate', $this->lang->line('transfer_certificate'), 'trim|callback_transfer_certificate');
        
        $this->form_validation->set_rules('father_name', $this->lang->line('father_name'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_education', $this->lang->line('father_education'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_profession', $this->lang->line('father_profession'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_designation', $this->lang->line('father_designation'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_photo', $this->lang->line('father_photo'), 'trim|callback_father_photo');
        
        $this->form_validation->set_rules('mother_name', $this->lang->line('mother_name'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_education', $this->lang->line('mother_education'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_profession', $this->lang->line('mother_profession'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_designation', $this->lang->line('mother_designation'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_photo', $this->lang->line('mother_photo'), 'trim|callback_mother_photo');
        
        $this->form_validation->set_rules('relation_with', $this->lang->line('relation_with'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('previous_school', $this->lang->line('previous_school'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('previous_class', $this->lang->line('previous_class'), 'trim|alpha_numeric_spaces');
        
    }
                        
    /*****************Function email**********************************
    * @type            : Function
    * @function name   : email
    * @description     : Unique check for "Student Email" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function email() {
        if ($this->input->post('id') == '') {
            $email = $this->student->duplicate_check($this->input->post('email'));
            if ($email) {
                $this->form_validation->set_message('email', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $email = $this->student->duplicate_check($this->input->post('email'), $this->input->post('id'));
            if ($email) {
                $this->form_validation->set_message('email', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
                
    /*****************Function email**********************************
    * @type            : Function
    * @function name   : email
    * @description     : Unique check for "Student Email" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function gud_email() {
        if ($this->input->post('id') == '') {
            $email = $this->student->duplicate_check($this->input->post('gud_email'));
            if ($email) {
                $this->form_validation->set_message('gud_email', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $email = $this->student->duplicate_check($this->input->post('gud_email'), $this->input->post('id'));
            if ($email) {
                $this->form_validation->set_message('gud_email', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
                
    
    /*****************Function photo**********************************
    * @type            : Function
    * @function name   : photo
    * @description     : validate student profile photo                 
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function photo() {
        if ($_FILES['photo']['name']) {
            
            list($width, $height) = getimagesize($_FILES['photo']['tmp_name']);
            if((!empty($width)) && $width > 120 || $height > 130){
                $this->form_validation->set_message('photo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
            
            $name = $_FILES['photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function transfer_certificate**********************************
    * @type            : Function
    * @function name   : transfer_certificate
    * @description     : validate student transfer_certificate                
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function transfer_certificate() {
        if ($_FILES['transfer_certificate']['name']) {
            
            list($width, $height) = getimagesize($_FILES['transfer_certificate']['tmp_name']);
            if((!empty($width)) && $width > 1200 || $height > 600){
                $this->form_validation->set_message('transfer_certificate', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
            
            $name = $_FILES['transfer_certificate']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('transfer_certificate', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function father_photo**********************************
    * @type            : Function
    * @function name   : father_photo
    * @description     : validate student father_photo               
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function father_photo() {
        if ($_FILES['father_photo']['name']) {
            
            list($width, $height) = getimagesize($_FILES['father_photo']['tmp_name']);
            if((!empty($width)) && $width > 120 || $height > 130){
                $this->form_validation->set_message('father_photo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
            
            $name = $_FILES['father_photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('father_photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }
    
    /*****************Function mother_photo**********************************
    * @type            : Function
    * @function name   : mother_photo
    * @description     : validate student mother_photo               
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */
    public function mother_photo() {
        if ($_FILES['mother_photo']['name']) {
            
            list($width, $height) = getimagesize($_FILES['mother_photo']['tmp_name']);
            if((!empty($width)) && $width > 120 || $height > 130){
                $this->form_validation->set_message('mother_photo', $this->lang->line('please_check_image_dimension'));
                return FALSE;
            }
            
            $name = $_FILES['mother_photo']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('mother_photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

       

       
    /*****************Function _get_posted_student_data**********************************
    * @type            : Function
    * @function name   : _get_posted_student_data
    * @description     : Prepare "Student" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_student_data() {

        $items = array();

        $items[] = 'name';
        $items[] = 'admission_no';       
        $items[] = 'gender';
        $items[] = 'blood_group';
        $items[] = 'religion';
        $items[] = 'caste';
        $items[] = 'phone';
        $items[] = 'national_id';
        $items[] = 'type_id';
        $items[] = 'group_id';
        $items[] = 'registration_no';
        $items[] = 'discount_id';
        
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        
        $items[] = 'second_language';
        $items[] = 'previous_school';
        $items[] = 'previous_class';
        
        $items[] = 'father_name';
        $items[] = 'father_phone';
        $items[] = 'father_education';
        $items[] = 'father_profession';
        $items[] = 'father_designation';
        
        $items[] = 'mother_name';
        $items[] = 'mother_phone';
        $items[] = 'mother_education';
        $items[] = 'mother_profession';
        $items[] = 'mother_designation';
        
        $items[] = 'health_condition';
        $items[] = 'other_info';        
        
        $items[] = 'is_guardian';
        $items[] = 'relation_with';

        $data = elements($items, $_POST);

        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
        $data['age'] = floor((time() - strtotime($data['dob'])) / 31556926);

        if ($this->input->post('id')) {
            
            $data['guardian_id'] = $this->input->post('guardian_id');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $data['status_type'] = $this->input->post('status_type');
            
        } else {
            
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            $data['status'] = 1;
            $data['status_type'] = 'regular';
            
            // create guardian and guardian user if not exist
            if($this->input->post('is_guardian') == 'exist_guardian'){
                
                $data['guardian_id'] = $this->input->post('guardian_id');
                
            }else{

                $info = array();
                $guardian = array();    

                $info['role_id']  = GUARDIAN;
                $info['name']     =  $this->input->post('gud_name');
                $info['phone'] = $this->input->post('gud_phone'); 
                $info['email'] = $this->input->post('gud_email'); 
                $info['password'] = rand();
                
                // now creating guardian user
                $guardian['user_id'] = $this->student->create_custom_user($info);     

                // create guardian....                
                $guardian['name']    = $this->input->post('gud_name');
                $guardian['phone']   = $this->input->post('gud_phone');
                $guardian['profession']   = $this->input->post('gud_profession');
                $guardian['religion']   = $this->input->post('gud_religion');
                $guardian['national_id'] = $this->input->post('gud_national_id');
                $guardian['present_address']   = $this->input->post('gud_present_address');
                $guardian['permanent_address']   = $this->input->post('gud_permanent_address');
                $guardian['other_info']   = $this->input->post('gud_other_info');
                $guardian['created_at'] = date('Y-m-d H:i:s');
                $guardian['created_by'] = logged_in_user_id();
                $guardian['modified_at'] = date('Y-m-d H:i:s');
                $guardian['modified_by'] = logged_in_user_id();
                $guardian['status'] = 1;
                
                $data['guardian_id'] = $this->student->insert('guardians', $guardian);
            }
            
            // create student user 
            $data['user_id'] = $this->student->create_user();
        }

        // student  photo
        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
        }
        // student transfer certificate
        if ($_FILES['transfer_certificate']['name']) {
            $data['transfer_certificate'] = $this->_upload_transfer_certificate();
        }
        
        // father photo
        if ($_FILES['father_photo']['name']) {
            $data['father_photo'] = $this->_upload_father_photo();
        }
        
        // mother photo        
        if ($_FILES['mother_photo']['name']) {
            $data['mother_photo'] = $this->_upload_mother_photo();
        }

        return $data;
    }

           
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : process to upload student profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */
    private function _upload_photo() {

        $prev_photo = $this->input->post('prev_photo');
        $photo = $_FILES['photo']['name'];
        $photo_type = $_FILES['photo']['type'];
        $return_photo = '';
        if ($photo != "") {
            if ($photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                    $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                    $photo_type == 'image/x-png' || $photo_type == 'image/gif') {

                $destination = 'assets/uploads/student-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);

                // need to unlink previous photo
                if ($prev_photo != "") {
                    if (file_exists($destination . $prev_photo)) {
                        @unlink($destination . $prev_photo);
                    }
                }

                $return_photo = $photo_path;
            }
        } else {
            $return_photo = $prev_photo;
        }

        return $return_photo;
    }
    
    
    /*****************Function _upload_transfer_certificate**********************************
    * @type            : Function
    * @function name   : _upload_transfer_certificate
    * @description     : process to upload student transfer_certificate in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */
    private function _upload_transfer_certificate() {

        $prev_transfer_certificate = $this->input->post('prev_transfer_certificate');
        $transfer_certificate = $_FILES['transfer_certificate']['name'];
        $transfer_certificate_type = $_FILES['transfer_certificate']['type'];
        $return_transfer_certificate = '';
        if ($transfer_certificate != "") {            

                $destination = 'assets/uploads/transfer-certificate/';

                $file_type = explode(".", $transfer_certificate);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $transfer_certificate_path = 'tc-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['transfer_certificate']['tmp_name'], $destination . $transfer_certificate_path);

                // need to unlink previous transfer_certificate
                if ($prev_transfer_certificate != "") {
                    if (file_exists($destination . $prev_transfer_certificate)) {
                        @unlink($destination . $prev_transfer_certificate);
                    }
                }

                $return_transfer_certificate = $transfer_certificate_path;
           
        } else {
            $return_transfer_certificate = $prev_transfer_certificate;
        }

        return $return_transfer_certificate;
    }

    
               
    /*****************Function _upload_father_photo**********************************
    * @type            : Function
    * @function name   : _upload_father_photo
    * @description     : process to upload student profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_father_photo string value 
    * ********************************************************** */
    private function _upload_father_photo() {

        $prev_father_photo = $this->input->post('prev_father_photo');
        $father_photo = $_FILES['father_photo']['name'];
        $father_photo_type = $_FILES['father_photo']['type'];
        $return_father_photo = '';
        if ($father_photo != "") {
            if ($father_photo_type == 'image/jpeg' || $father_photo_type == 'image/pjpeg' ||
                    $father_photo_type == 'image/jpg' || $father_photo_type == 'image/png' ||
                    $father_photo_type == 'image/x-png' || $father_photo_type == 'image/gif') {

                $destination = 'assets/uploads/father-photo/';

                $file_type = explode(".", $father_photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $father_photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['father_photo']['tmp_name'], $destination . $father_photo_path);

                // need to unlink previous father_photo
                if ($prev_father_photo != "") {
                    if (file_exists($destination . $prev_father_photo)) {
                        @unlink($destination . $prev_father_photo);
                    }
                }

                $return_father_photo = $father_photo_path;
            }
        } else {
            $return_father_photo = $prev_father_photo;
        }

        return $return_father_photo;
    }
    
    
    
               
    /*****************Function _upload_mother_photo**********************************
    * @type            : Function
    * @function name   : _upload_mother_photo
    * @description     : process to upload mother profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_mother_photo string value 
    * ********************************************************** */
    private function _upload_mother_photo() {

        $prev_mother_photo = $this->input->post('prev_mother_photo');
        $mother_photo = $_FILES['mother_photo']['name'];
        $mother_photo_type = $_FILES['mother_photo']['type'];
        $return_mother_photo = '';
        if ($mother_photo != "") {
            if ($mother_photo_type == 'image/jpeg' || $mother_photo_type == 'image/pjpeg' ||
                    $mother_photo_type == 'image/jpg' || $mother_photo_type == 'image/png' ||
                    $mother_photo_type == 'image/x-png' || $mother_photo_type == 'image/gif') {

                $destination = 'assets/uploads/mother-photo/';

                $file_type = explode(".", $mother_photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $mother_photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['mother_photo']['tmp_name'], $destination . $mother_photo_path);

                // need to unlink previous mother_photo
                if ($prev_mother_photo != "") {
                    if (file_exists($destination . $prev_mother_photo)) {
                        @unlink($destination . $prev_mother_photo);
                    }
                }

                $return_mother_photo = $mother_photo_path;
            }
        } else {
            $return_mother_photo = $prev_mother_photo;
        }

        return $return_mother_photo;
    }
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Student" data from database                  
    *                     also delete all relational data
    *                     and unlink student photo from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
              redirect('student/index');
        }
        
        $student = $this->student->get_single('students', array('id' => $id));
        
        // get invoices
        $invoices = $this->student->get_invoice_list($id); 
        if(!empty($invoices)){
            error($this->lang->line('this_student_have_unpaid_invoice'));
            redirect('student/index');
        }  
        
        if (!empty($student)) {

            // delete student data
            $this->student->delete('students', array('id' => $id));

            // delete student login data
            $this->student->delete('users', array('id' => $student->user_id));

            // delete student enrollments
            $enroll = $this->student->get_single('enrollments', array('student_id' => $student->id));
            $this->student->delete('enrollments', array('student_id' => $student->id));

            // delete student hostel_members
            $this->student->delete('hostel_members', array('user_id' => $student->user_id));

            // delete student transport_members
            $this->student->delete('transport_members', array('user_id' => $student->user_id));

            // delete student library_members
            $this->student->delete('library_members', array('user_id' => $student->user_id));
            
            // delete student activity_logs
            $this->student->delete('activity_logs', array('user_id' => $student->user_id));
            
            // delete student exam_attendances
            $this->student->delete('exam_attendances', array('student_id' => $student->id));
            
            // delete student exam_results
            $this->student->delete('exam_results', array('student_id' => $student->id));
            
            // delete student final_results
            $this->student->delete('final_results', array('student_id' => $student->id));
            
            //delete student invoices
            $invoices = $this->student->get_list('invoices', array('user_id' => $student->user_id));
            if(!empty($invoices)){
                foreach ($invoices as $obj){
                    $this->student->delete('transactions', array('invoice_id' => $obj->id));
                    $this->student->delete('invoice_detail', array('invoice_id' => $obj->id));
                }
                $this->student->delete('invoices', array('user_id' => $student->user_id));
            }
            
           
            // delete student marks
            $this->student->delete('marks', array('student_id' => $student->id));            
                        
            // delete student messages data
            $this->student->delete('messages', array('created_by' => $student->user_id));
            
            $this->student->delete('message_relationships', array('sender_id' => $student->user_id));
            $this->student->delete('message_relationships', array('receiver_id' => $student->user_id));
            $this->student->delete('message_relationships', array('owner_id' => $student->user_id));
            
            $this->student->delete('replies', array('sender_id' => $student->user_id));
            $this->student->delete('replies', array('receiver_id' => $student->user_id));
            

            // delete student  photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/student-photo/' . $student->photo)) {
                @unlink($destination . '/student-photo/' . $student->photo);
            }
            if (file_exists($destination . '/transfer-certificate/' . $student->transfer_certificate)) {
                @unlink($destination . '/transfer-certificate/' . $student->transfer_certificate);
            }

            create_log('Has been deleted a Student : '.$student->name);
            
            success($this->lang->line('delete_success'));
            redirect('student/index/'.$enroll->class_id);
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('student/index');
    }

        
    /*****************Function __insert_enrollment**********************************
    * @type            : Function
    * @function name   : __insert_enrollment
    * @description     : save student info to enrollment while create a new student                  
    * @param           : $insert_id integer value
    * @return          : null 
    * ********************************************************** */
    private function __insert_enrollment($insert_id) {
        $data = array();
        $data['student_id'] = $insert_id;
        $data['class_id'] = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        
        if($this->input->post('advanced')){
            $data['academic_year_id'] = $this->input->post('academic_year_id');
        }else{            
            $data['academic_year_id'] = $this->academic_year_id;
        }
        
        $data['roll_no'] = $this->input->post('roll_no');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
        $data['status'] = 1;
        $this->db->insert('enrollments', $data);
    }

    /*****************Function __update_enrollment**********************************
    * @type            : Function
    * @function name   : __update_enrollment
    * @description     : update student info to enrollment while update a student                  
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function __update_enrollment() {

        $data = array();
        $data['class_id'] = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['roll_no'] = $this->input->post('roll_no');
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();

        $this->db->where('student_id', $this->input->post('id'));
        
        if($this->input->post('advanced')){
            $data['academic_year_id'] = $this->input->post('academic_year_id');
        }else{            
            $data['academic_year_id'] = $this->academic_year_id;
            $this->db->where('academic_year_id', $this->academic_year_id);
        }
        
        $this->db->update('enrollments', $data, array());
    }
    
    
    /*****************Function advanced**********************************
    * @type            : Function
    * @function name   : advanced
    * @description     : Load "Add new advanced Student" user interface                 
    *                    and process to store "Student" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function advanced() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_student_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();

                $insert_id = $this->student->insert('students', $data);

                if ($insert_id) {
                    $this->__insert_enrollment($insert_id);
                    success($this->lang->line('insert_success'));
                    redirect('student/advanced/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('student/advanced/'.$this->input->post('class_id'));
                }
            } else {

                $this->data['post'] = $_POST;
            }
        }
        
       
        
        $class_id = $this->uri->segment(3);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        
        $session = $this->student->get_single('academic_years', array('id'=>$this->academic_year_id));   
        $this->data['class_id'] = $class_id;      
        $this->data['students'] = $this->student->get_student_list($class_id, $session->end_year);
        $this->data['academic_years'] = $this->student->get_list('academic_years', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['discounts'] = $this->student->get_list('discounts', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['types'] = $this->student->get_list('student_types', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['groups'] = $this->student->get_list('student_groups', array('status'=> 1), '', '', '', 'id', 'ASC'); 
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('add') .' | ' . SMS);
        $this->layout->view('student/advanced', $this->data);
    }
    
            
    /*****************Function editadvanced**********************************
    * @type            : Function
    * @function name   : editadvanced
    * @description     : Load Update "Student" user interface                 
    *                    with populate "Student" value 
    *                    and process to update "Student" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function editadvanced($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/advanced');     
        }
        
        if ($_POST) {
            $this->_prepare_student_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_student_data();
                $updated = $this->student->update('students', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    $this->__update_enrollment();
                    success($this->lang->line('update_success'));
                    redirect('student/advanced/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('student/editadvanced/' . $this->input->post('id'));
                }
            } else {
                $this->data['student'] = $this->student->get_single_student($this->input->post('id'), true);
            }
        }

        if ($id) {
            $this->data['student'] = $this->student->get_single_student($id, true);

            if (!$this->data['student']) {
                redirect('student/advanced');
            }
        }
        
        $class_id = $this->data['student']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 

        $this->data['class_id'] = $class_id;
        $this->data['students'] = $this->student->get_student_list($class_id, true);
        $this->data['academic_years'] = $this->student->get_list('academic_years', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['roles'] = $this->student->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['guardians'] = $this->student->get_list('guardians', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['classes'] = $this->student->get_list('classes', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['discounts'] = $this->student->get_list('discounts', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['types'] = $this->student->get_list('student_types', array('status'=> 1), '', '', '', 'id', 'ASC');  
        $this->data['groups'] = $this->student->get_list('student_groups', array('status'=> 1), '', '', '', 'id', 'ASC'); 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('student/advanced', $this->data);
    }


}
