<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Admission.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Admission
 * @description     : Manage admission admission imformation of the school.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Admission extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();      
        
        $this->load->model('Admission_Model', 'admission', true);            
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Admission Admission List" user interface                 
    *                    with class wise listing    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function index($class_id = null) {

        check_permission(VIEW);
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/admission/index');
        }
        
        // for super admin 
  
        if($_POST){
            $class_id  = $this->input->post('class_id');           
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['filter_class_id'] = $class_id;     
                
        $this->data['admissions'] = $this->admission->get_admission_list($class_id);
        $this->data['roles'] = $this->admission->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
        
        $condition = array();
        $condition['status'] = 1;  
        $this->data['classes'] = $this->admission->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['guardians'] = $this->admission->get_list('guardians', $condition, '','', '', 'id', 'ASC');
        $this->data['class_list'] = $this->admission->get_list('classes', $condition, '','', '', 'id', 'ASC');
        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_online_admission'). ' | ' . SMS);
        $this->layout->view('admission/index', $this->data);
    }

 
    /*****************Function approved**********************************
    * @type            : Function
    * @function name   : approved
    * @description     : Load Update "Admission admission" user interface                 
    *                    with populate "Admission" value 
    *                    and process to update "Admission" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function approve($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/admission/index');     
        }
        
        
        if ($_POST) {
            $this->_prepare_admission_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_admission_data();
                
                $insert_id = $this->admission->insert('students', $data);

                if ($insert_id) {          
                    
                    // insert enrollment data
                    $this->__insert_enrollment($insert_id);
                    // need to upodate admission status
                    $this->admission->update('admissions', array('status'=>3, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$this->input->post('id')));               
                    
                    success($this->lang->line('update_success'));
                    redirect('student/index/'.$this->input->post('class_id'));
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('admission/approved/' . $this->input->post('id'));
                }
            } else {
                $this->data['admission'] = $this->admission->get_single_admission($this->input->post('id'));
            }
        }

        
        
        if ($id) {            
            
            $this->data['admission'] = $this->admission->get_single('admissions', array('id'=>$id)); 

            if ((!$this->data['admission']) || ($this->data['admission']->status == 3)) {
                redirect('student/admission/index');
            }
        }
      
        $class_id = $this->data['admission']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        } 

        
        $this->data['class_id'] = $class_id;
        $this->data['admissions'] = $this->admission->get_admission_list($class_id);
        $this->data['roles'] = $this->admission->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        
    
        $condition = array();
        $condition['status'] = 1;         
        $this->data['discounts'] = $this->admission->get_list('discounts', $condition, '','', '', 'id', 'ASC');
        $this->data['classes'] = $this->admission->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['class_list'] = $this->admission->get_list('classes', $condition, '','', '', 'id', 'ASC');
        $this->data['types']      = $this->admission->get_list('student_types', $condition, '','', '', 'id', 'ASC'); 
        $this->data['groups']      = $this->admission->get_list('student_groups', $condition, '','', '', 'id', 'ASC'); 
        $this->data['academic_years'] = $this->admission->get_list('academic_years', $condition, '','', '', 'id', 'ASC');
    
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('admission/index', $this->data);
    }


    
     /*****************Function get_single_admission**********************************
     * @type            : Function
     * @function name   : get_single_admission
     * @description     : "Load single admission information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_admission(){
        
        $admission_id = $this->input->post('admission_id');        
        $this->data['admission'] = $this->admission->get_single_admission($admission_id);          
        echo $this->load->view('admission/get-single-admission', $this->data);
    }
    
    /*****************Function waiting**********************************
     * @type            : Function
     * @function name   : waiting
     * @description     : "update admission status" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function waiting($admission_id){
        
        if(!is_numeric($admission_id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/admission/index');     
        }
        
        $status = $this->admission->update('admissions', array('status'=>1, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$admission_id));               
        $admission = $this->admission->get_single('admissions', array('id' => $admission_id)); 
        
        if($status){
            success($this->lang->line('update_success'));
        }else{
            error($this->lang->line('update_failed'));
        }
         redirect('student/admission/index/'.$admission->class_id); 
    }
    
    /*****************Function decline**********************************
     * @type            : Function
     * @function name   : decline
     * @description     : "update admission status" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function decline($admission_id){
        
        if(!is_numeric($admission_id)){
            error($this->lang->line('unexpected_error'));
            redirect('student/admission/index');     
        }
        
        $admission = $this->admission->get_single('admissions', array('id' => $admission_id));
        $status = $this->admission->update('admissions', array('status'=>2, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$admission_id));               
        
        if($status){
            success($this->lang->line('update_success'));
        }else{
            error($this->lang->line('update_failed'));
        }
        redirect('student/admission/index/'.$admission->class_id);    
    }
    
    
        
    /*****************Function _prepare_admission_validation**********************************
    * @type            : Function
    * @function name   : _prepare_admission_validation
    * @description     : Process "Admission" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_admission_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('academic_year_id', $this->lang->line('academic_year'), 'trim|required');        
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|callback_email');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|min_length[6]|max_length[20]');
        
        $this->form_validation->set_rules('phone',  $this->lang->line('phone'), 'trim|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('roll_no', $this->lang->line('roll_no'), 'trim|required');         
        $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required');
        $this->form_validation->set_rules('admission_date', $this->lang->line('admission_date'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');
        
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('dob', $this->lang->line('birth_date'), 'trim|required');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required');
        $this->form_validation->set_rules('present_address', $this->lang->line('present_address'), 'trim');
        $this->form_validation->set_rules('permanent_address', $this->lang->line('permanent_address'), 'trim');
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'trim|callback_photo');
         $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('caste', $this->lang->line('caste'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('second_language', $this->lang->line('second_language'), 'trim|alpha_numeric_spaces');
                
        // guardian fields
        $this->form_validation->set_rules('gud_name', $this->lang->line('guardian_name'), 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('gud_phone', $this->lang->line('guardian_phone'), 'trim|required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('gud_email', $this->lang->line('email'), 'trim|valid_email|callback_gud_email');
        $this->form_validation->set_rules('gud_profession',  $this->lang->line('gud_profession'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('gud_religion',  $this->lang->line('gud_religion'), 'trim|alpha_numeric_spaces');
        
        
        $this->form_validation->set_rules('father_name', $this->lang->line('father_name'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_education', $this->lang->line('father_education'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_profession', $this->lang->line('father_profession'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('father_designation', $this->lang->line('father_designation'), 'trim|alpha_numeric_spaces');
        
        $this->form_validation->set_rules('mother_name', $this->lang->line('mother_name'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_education', $this->lang->line('mother_education'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_profession', $this->lang->line('mother_profession'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('mother_designation', $this->lang->line('mother_designation'), 'trim|alpha_numeric_spaces');
        
        $this->form_validation->set_rules('relation_with', $this->lang->line('relation_with'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('previous_school', $this->lang->line('previous_school'), 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('previous_class', $this->lang->line('previous_class'), 'trim|alpha_numeric_spaces');
        
    }
                        
    /*****************Function username**********************************
    * @type            : Function
    * @function name   : username
    * @description     : Unique check for "Admission username" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function email() {
        
        $email = $this->admission->duplicate_check($this->input->post('email'));
        if ($email) {
            $this->form_validation->set_message('email', $this->lang->line('already_exist'));
            return FALSE;
        } else {
            return TRUE;
        }       
    }
    
    /*****************Function username**********************************
    * @type            : Function
    * @function name   : username
    * @description     : Unique check for "Admission username" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function gud_email() {
        
        $email = $this->admission->duplicate_check($this->input->post('gud_email'));
        if ($email) {
            $this->form_validation->set_message('gud_email', $this->lang->line('already_exist'));
            return FALSE;
        } else {
            return TRUE;
        }       
    }
                
    /*****************Function photo**********************************
    * @type            : Function
    * @function name   : photo
    * @description     : validate admission profile photo                 
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
            $arr = explode('.', $name);
            $ext = end($arr);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                return TRUE;
            } else {
                $this->form_validation->set_message('photo', $this->lang->line('select_valid_file_format'));
                return FALSE;
            }
        }
    }

       
    /*****************Function _get_posted_admission_data**********************************
    * @type            : Function
    * @function name   : _get_posted_admission_data
    * @description     : Prepare "Admission" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_admission_data() {

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
        $items[] = 'second_language';
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        $items[] = 'previous_school';
        $items[] = 'previous_class';
        $items[] = 'health_condition';
        $items[] = 'relation_with';
        $items[] = 'other_info';
        
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

        $data = elements($items, $_POST);

        $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
        $data['age'] = floor((time() - strtotime($data['dob'])) / 31556926);

       
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['status'] = 1;
        
        // create guardian if not exist
        if($this->input->post('guardian_id')){
            $data['guardian_id'] = $this->input->post('guardian_id');
        }else{
            
            $info = array();
            $guardian = array();    
                        
            $info['role_id']  = GUARDIAN;
            $info['name']     =  $this->input->post('gud_name');
            $info['email'] = $this->input->post('gud_email');
            $info['password'] = '123456';
            $info['phone'] = $this->input->post('gud_phone');            
            // now creating guardian user
            $guardian['user_id'] = $this->admission->create_custom_user($info);     
            
            // create guardian....
            $guardian['national_id'] = $this->input->post('gud_national_id');
            $guardian['name']        = $this->input->post('gud_name');
            $guardian['phone']   = $this->input->post('gud_phone');
            $guardian['profession']   = $this->input->post('gud_profession');
            $guardian['present_address']   = $this->input->post('gud_present_address');
            $guardian['permanent_address']   = $this->input->post('gud_permanent_address');
            $guardian['religion']   = $this->input->post('gud_religion');
            $guardian['other_info']   = $this->input->post('gud_other_info');
            $guardian['created_at'] = date('Y-m-d H:i:s');
            $guardian['created_by'] = logged_in_user_id();
            $guardian['status'] = 1;
            $data['guardian_id'] = $this->admission->insert('guardians', $guardian);
            
        }
        
        // create student user 
        $data['user_id'] = $this->admission->create_user();       
        
        $data['photo'] = $this->_upload_photo();
       

        return $data;
    }

           
    /*****************Function _upload_photo**********************************
    * @type            : Function
    * @function name   : _upload_photo
    * @description     : process to upload admission profile photo in the server                  
    *                     and return photo file name  
    * @param           : null
    * @return          : $return_photo string value 
    * ********************************************************** */
    private function _upload_photo() {
              
        $return_photo = '';
        
        if($_FILES['photo']['name']){            
           
            $photo = $_FILES['photo']['name'];
            $photo_type = $_FILES['photo']['type'];
            if ($photo != "") {
                if ($photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                        $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                        $photo_type == 'image/x-png' || $photo_type == 'image/gif') {

                    $destination = 'assets/uploads/student-photo/';

                    $file_type = explode(".", $photo);
                    $extension = strtolower($file_type[count($file_type) - 1]);
                    $photo_path = 'photo-' . time() . '-sms.' . $extension;

                    move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);

                    $return_photo = $photo_path;
                }
            } 
            
        }else{
           
            $student_photo = $this->input->post('prev_photo');  
            if ($student_photo != "") {          

                $destination_1 = 'assets/uploads/admission-photo/';
                $destination_2 = 'assets/uploads/student-photo/';

                copy($destination_1.$student_photo, $destination_2 . $student_photo);
                $return_photo = $student_photo;
            } 
        }

        return $return_photo;
    }
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Admission" data from database                  
    *                     also delete all relational data
    *                     and unlink admission photo from server   
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
              redirect('student/admission/index');
        }
        
        $admission = $this->admission->get_single('admissions', array('id' => $id));
        if (!empty($admission)) {

            // delete admission
            $this->admission->delete('admissions', array('id' => $id));

            // delete admission resume and photo
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/admission/' . $admission->photo)) {
                @unlink($destination . '/admission/' . $admission->photo);
            }
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('student/admission/index');
    }

        
    /*****************Function __insert_enrollment**********************************
    * @type            : Function
    * @function name   : __insert_enrollment
    * @description     : save admission info to enrollment while create a new admission                  
    * @param           : $insert_id integer value
    * @return          : null 
    * ********************************************************** */
    private function __insert_enrollment($insert_id) {
        $data = array();        
        
        $data['student_id'] = $insert_id;
        $data['class_id'] = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['academic_year_id'] = $this->input->post('academic_year_id');
        $data['roll_no'] = $this->input->post('roll_no');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
        $data['status'] = 1;
        $this->admission->insert('enrollments', $data);
    }

}