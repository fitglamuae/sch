<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Web.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Web
 * @description     : Manage frontend website.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Web extends CI_Controller {

    public $data = array();
    public $setting = array();
    public $academic_year_id = '';
    const GSMS = 'Global - Single School Management System';
            
    function __construct() {
        parent::__construct();
        
        $this->load->model('Web_Model', 'web', true);        
        $this->data['settings'] = $this->web->get_single('settings', array('status' => 1));
        $this->data['theme'] = $this->web->get_single('themes', array('is_active' => 1));
        $this->data['opening_hour'] = $this->web->get_single('opening_hours', array('status' => 1));
        
        if($this->data['settings']){
            $this->setting = $this->data['settings'];
            $this->academic_year_id = $this->setting->academic_year_id;
            date_default_timezone_set($this->setting->default_time_zone);
            $this->lang->load($this->setting->language);
            $this->GSMS  = $this->setting->school_name;
        }
        
       
        
        
        if(isset($this->setting->enable_frontend) && empty($this->setting->enable_frontend)){
            redirect('login');
        }
        
        $this->data['footer_pages'] = $this->web->get_list('pages', array('status' => 1, 'page_location'=>'footer'));
        $this->data['header_pages'] = $this->web->get_list('pages', array('status' => 1, 'page_location'=>'header'));
        
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Frontend home page" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        $this->data['sliders'] = $this->web->get_list('sliders', array('status' => 1), '', '', '', 'id', 'ASC');
        
        $this->data['notices'] = $this->web->get_notice_list(3);
        $this->data['events'] = $this->web->get_event_list(3);
        $this->data['news'] = $this->web->get_news_list(3);
        $this->data['feedbacks'] = $this->web->get_feedback_list(10);
        
        $this->data['teacher'] = $this->web->get_total_teacher();
        $this->data['student'] = $this->web->get_total_student();
        $this->data['staff'] = $this->web->get_total_staff();
        $this->data['user'] = $this->web->get_total_user();     
        
        $this->data['is_home'] = TRUE;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('home') . ' | ' . $this->GSMS);
        $this->layout->view('index', $this->data);
    }
    
    
    /*****************Function news**********************************
    * @type            : Function
    * @function name   : news
    * @description     : Load "news listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function news() {

        $this->data['news'] = $this->web->get_news_list(100);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('news') . ' | ' . $this->GSMS);
        $this->layout->view('news', $this->data);
    }
    
    
    /*****************Function news**********************************
    * @type            : Function
    * @function name   : news
    * @description     : Load "news detail" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function news_detail($id) {

        $this->data['news'] = $this->web->get_single_news($id); 
        $this->data['latest_news'] = $this->web->get_news_list(6);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('news') . ' | ' . $this->GSMS);
        $this->layout->view('news_detail', $this->data);
    }
    
    
    
    /*****************Function notice**********************************
    * @type            : Function
    * @function name   : notice
    * @description     : Load "notice listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function notice() {

        $this->data['notices'] = $this->web->get_notice_list(50);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('notice') . ' | ' . $this->GSMS);
        $this->layout->view('notice', $this->data);
    }
    
    /*****************Function notice_detail**********************************
    * @type            : Function
    * @function name   : notice_detail
    * @description     : Load "notice_detail" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function notice_detail($id) {

        $this->data['notice'] = $this->web->get_single_notice($id);
        $this->data['notices'] = $this->web->get_notice_list(6);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('notice') . ' | ' . $this->GSMS);
        $this->layout->view('notice_detail', $this->data);
    }
    
    
    /*****************Function holiday**********************************
    * @type            : Function
    * @function name   : holiday
    * @description     : Load "holiday listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function holiday() {

        $this->data['holidays'] = $this->web->get_holiday_list(50);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('holiday') . ' | ' . $this->GSMS);
        $this->layout->view('holiday', $this->data);
    }
    
    /***************** Function holiday_detail **********************************
    * @type            : Function
    * @function name   : holiday_detail
    * @description     : Load "holiday_detail" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function holiday_detail($id) {

        $this->data['holiday']  = $this->web->get_single_holiday($id);
        $this->data['holidays'] = $this->web->get_holiday_list(15);
        $this->data['list']     = TRUE;
        $this->layout->title($this->lang->line('holiday') . ' | ' . $this->GSMS);
        $this->layout->view('holiday_detail', $this->data);
    }
    
    /*****************Function event**********************************
    * @type            : Function
    * @function name   : event
    * @description     : Load "event listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function events() {

        $this->data['events'] = $this->web->get_event_list(6);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('event') . ' | ' . $this->GSMS);
        $this->layout->view('event', $this->data);
    }
    
    /*****************Function event_detail**********************************
    * @type            : Function
    * @function name   : event_detail
    * @description     : Load "event_detail" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function event_detail($id){

        $this->data['event'] = $this->web->get_single_event($id);
        $this->data['events'] = $this->web->get_event_list(10);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('event') . ' | ' . $this->GSMS);
        $this->layout->view('event_detail', $this->data);
    }
    
    
    
    /*****************Function gallery**********************************
    * @type            : Function
    * @function name   : gallery
    * @description     : Load "gallery listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function galleries() {

        $this->data['galleries'] = $this->web->get_list('galleries', array('status'=>1, 'is_view_on_web'=>1), '', '', '', 'id', 'DESC');
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('media_gallery') . ' | ' . $this->GSMS);
        $this->layout->view('gallery', $this->data);
    }

    
    /*****************Function teacher**********************************
    * @type            : Function
    * @function name   : teacher
    * @description     : Load "teacher listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function teachers() {

        $this->data['teachers'] = $this->web->get_teacher_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('teacher') . ' | ' . $this->GSMS);
        $this->layout->view('teacher', $this->data);
    }
    
    
    /*****************Function staff**********************************
    * @type            : Function
    * @function name   : staff
    * @description     : Load "staff listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function staff() {

        $this->data['employees'] = $this->web->get_employee_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('staff') . ' | ' . $this->GSMS);
        $this->layout->view('staff', $this->data);
    }
    
    
    
    /*****************Function faq**********************************
    * @type            : Function
    * @function name   : faq
    * @description     : Load "faq listing" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function faq() {

        $this->data['faqs'] = $this->web->get_faq_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('faq') . ' | ' . $this->GSMS);
        $this->layout->view('faq', $this->data);
    }
    
    /*****************Function Page**********************************
    * @type            : Function
    * @function name   : Page
    * @description     : Load "Dynamic Pages" user interface                 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function page($slug = null) { 
        
     
        $this->data['page'] = $this->web->get_single('pages', array('status' => 1, 'page_slug'=>$slug));

        if(empty($this->data['page'])){
            redirect('/', 'refresh');
        }

        $this->layout->title($this->lang->line('page') . ' | ' . $this->GSMS);
        $this->layout->view('page', $this->data);            
         
    }
    
    
    /*****************Function About**********************************
    * @type            : Function
    * @function name   : About
    * @description     : Load "About Us" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function about() {
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('about') . ' ' . $this->lang->line('school'). ' | ' . $this->GSMS);
        $this->layout->view('about', $this->data);
    }
    
    /*****************Function admission**********************************
    * @type            : Function
    * @function name   : admission
    * @description     : Load "admission" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function admission_form() {
    
        if(isset($this->setting->enable_online_admission) && !empty($this->setting->enable_online_admission)){               

            $this->data['list'] = TRUE;
            $this->layout->title($this->lang->line('admission_form') . ' | ' . $this->GSMS);
            $this->layout->view('admission-form', $this->data);

        }else{               
           redirect('/');
       }
            
      
    }
    
    /*****************Function admission**********************************
    * @type            : Function
    * @function name   : admission
    * @description     : Load "online admission" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function admission_online() {    
           
           if(isset($this->setting->enable_online_admission) && !empty($this->setting->enable_online_admission)){
              
            if($_POST){

                $this->_prepare_admission_validation();
                if ($this->form_validation->run() === TRUE) {
                    $data = $this->_get_posted_admission_data();

                    $insert_id = $this->web->insert('admissions', $data);
                    if ($insert_id) {
                        $this->session->set_userdata('success', $this->lang->line('apply_successful'));                        
                    } else {
                        $this->session->set_userdata('error', $this->lang->line('apply_failed'));
                    }

                    redirect('web/admission_online');
                } else {
                    $this->data['post'] = $_POST;
                }                
            } 

            $this->data['classes'] = $this->web->get_list('classes', array('status'=>1), '', '', '', 'id', 'ASC');
            $this->data['types'] = $this->web->get_list('student_types', array('status'=>1), '', '', '', 'id', 'ASC');
            $this->data['groups'] = $this->web->get_list('student_groups', array('status'=>1), '', '', '', 'id', 'ASC');

            $this->data['list'] = TRUE;
            $this->layout->title($this->lang->line('online'). ' ' .$this->lang->line('admission') . ' | ' . $this->GSMS);
            $this->layout->view('admission-online', $this->data);
            
           }else{               
               redirect('/');
           }
    }
    
    
    /*****************Function get_guardian_info**********************************
     * @type            : Function
     * @function name   : get_guardian_info
     * @description     : Get guardian information                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_guardian_info(){
                      

        header('Content-Type: application/json');
        $phone = $this->input->post('phone');        
        $guardian = $this->web->get_single('guardians', array('phone' => $phone));
        echo json_encode($guardian);
        die();
        
    }

        
    /*****************Function _prepare_admission_validation**********************************
     * @type            : Function
     * @function name   : _prepare_admission_validation
     * @description     : Process "admission" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_admission_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');   
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required');   
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required'); 
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|valid_email'); 
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|min_length[6]|max_length[20]'); 
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('type_id', $this->lang->line('student').' '.$this->lang->line('type'), 'trim');   
        
        $this->form_validation->set_rules('gud_phone', $this->lang->line('guardian') .' '. $this->lang->line('guardian'), 'trim|required'); 
        $this->form_validation->set_rules('gud_name', $this->lang->line('guardian') .' '. $this->lang->line('name'), 'trim|required'); 
        $this->form_validation->set_rules('gud_email', $this->lang->line('guardian') .' '. $this->lang->line('email'), 'trim|required|valid_email'); 
        
    }
    

        /*****************Function _get_posted_admission_data**********************************
     * @type            : Function
     * @function name   : _get_posted_admission_data
     * @description     : Prepare "admission" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_admission_data() {

        $items = array();
        $items[] = 'name';
        $items[] = 'gender';
        $items[] = 'class_id';
        $items[] = 'type_id';
        $items[] = 'group_id';
        $items[] = 'second_language';
        $items[] = 'blood_group';
        $items[] = 'phone';
        $items[] = 'email';
        $items[] = 'religion';
        $items[] = 'caste';
        $items[] = 'health_condition';
        $items[] = 'national_id';
        $items[] = 'present_address';
        $items[] = 'permanent_address';
        
        $items[] = 'is_guardian';
        $items[] = 'guardian_id';
        $items[] = 'gud_relation';
        $items[] = 'gud_name';
        $items[] = 'gud_phone';
        $items[] = 'gud_email';
        $items[] = 'gud_national_id';
        $items[] = 'gud_present_address';
        $items[] = 'gud_permanent_address';
        $items[] = 'gud_profession';
        $items[] = 'gud_religion';
        $items[] = 'gud_other_info';
        
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
        
        $items[] = 'previous_school';
        $items[] = 'previous_class';
        
        $data = elements($items, $_POST);        
        
        $data['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));  
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        }
            
        if ($_FILES['photo']['name']) {
            $data['photo'] = $this->_upload_photo();
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

        $photo = $_FILES['photo']['name'];
        $photo_type = $_FILES['photo']['type'];
        $return_photo = '';
        if ($photo != "") {
            if ($photo_type == 'image/jpeg' || $photo_type == 'image/pjpeg' ||
                    $photo_type == 'image/jpg' || $photo_type == 'image/png' ||
                    $photo_type == 'image/x-png' || $photo_type == 'image/gif') {

                $destination = 'assets/uploads/admission-photo/';

                $file_type = explode(".", $photo);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $photo_path = 'photo-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $photo_path);

                $return_photo = $photo_path;
            }
        } 

        return $return_photo;
    }
    
    
    /*****************Function contact**********************************
    * @type            : Function
    * @function name   : contact
    * @description     : Load "contact" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function contact() {

        if($_POST){
            if($this->_send_email()){
                $this->session->set_flashdata('success', $this->lang->line('email_send_success'));
            }else{
                 $this->session->set_flashdata('error', $this->lang->line('email_send_failed'));
            }
            
            redirect(site_url('contact'));
        }
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('contact_us') . ' | ' . $this->GSMS);
        $this->layout->view('contact', $this->data);
    }
    
        /*     * ***************Function _send_email**********************************
     * @type            : Function
     * @function name   : _send_email
     * @description     : this function used to send recover forgot password email 
     * @param           : $data array(); 
     * @return          : null 
     * ********************************************************** */

    private function _send_email() {

        $from_email = FROM_EMAIL;
        $from_name  = FROM_NAME;  

        $email_setting = $this->web->get_single('email_settings', array('status' => 1));
        $setting = $this->web->get_single('settings', array('status' => 1));

        if(!empty($email_setting)){
            $from_email = $email_setting->from_address;
            $from_name  = $email_setting->from_name;  
        }elseif(!empty($setting)){
            $from_email = $setting->email;
            $from_name  = $setting->school_name;  
        }

        if(!empty($email_setting) && $email_setting->mail_protocol == 'smtp'){

            $config['protocol']     = 'smtp';
            $config['smtp_host']    = $email_setting->smtp_host;
            $config['smtp_port']    = $email_setting->smtp_port;
            $config['smtp_timeout'] = $email_setting->smtp_timeout ? $email_setting->smtp_timeout  : 5;
            $config['smtp_user']    = $email_setting->smtp_user;
            $config['smtp_pass']    = $email_setting->smtp_pass;
            $config['smtp_crypto']  = $email_setting->smtp_crypto ? $email_setting->smtp_crypto  : 'tls';
            $config['mailtype'] = isset($email_setting) && $email_setting->mail_type ? $email_setting->mail_type  : 'html';
            $config['charset']  = isset($email_setting) && $email_setting->char_set ? $email_setting->char_set  : 'iso-8859-1';
            $config['priority']  = isset($email_setting) && $email_setting->priority ? $email_setting->priority  : '3';

        }elseif(!empty($email_setting) && $email_setting->mail_protocol != 'smtp'){

            $config['protocol'] = $email_setting->mail_protocol;
            $config['mailpath'] = '/usr/sbin/'.$email_setting->mail_protocol; 
            $config['mailtype'] = isset($email_setting) && $email_setting->mail_type ? $email_setting->mail_type  : 'html';
            $config['charset']  = isset($email_setting) && $email_setting->char_set ? $email_setting->char_set  : 'iso-8859-1';
            $config['priority']  = isset($email_setting) && $email_setting->priority ? $email_setting->priority  : '3';

        }else{ // default    
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail'; 
        }   

        $config['wordwrap'] = TRUE;            
        $config['newline']  = "\r\n"; 

        $this->load->library('email');
        $this->email->initialize($config);

        

        $this->email->from($this->input->post('email'), $this->input->post('first_name'));
        $this->email->to($setting->email);
        //$this->email->to('yousuf361@gmail.com')
        $subject = 'Contact email from visitor of '. $setting->school_name;
        $this->email->subject($subject);       

        $message = 'Contact mail from ' . $setting->school_name . ' website.<br/>';          
        $message .= '<br/><br/>';
         $message .= '<br/><br/>';
        $message .= 'Name: ' . $this->input->post('name');
        $message .= '<br/><br/>';      
        $message .= 'Email: ' . $this->input->post('email');
        $message .= '<br/><br/>';
        $message .= 'Phone: ' . $this->input->post('phone');
        $message .= '<br/><br/>';
        $message .= 'Subject: ' . $this->input->post('subject');
        $message .= '<br/><br/>';
        $message .= 'Message: ' . $this->input->post('message');
        $message .= '<br/><br/>';
        $message .= 'Thank you<br/>';

        $this->email->message($message);
        if (!empty($email_setting) && $email_setting->mail_protocol == 'smtp') {
            $this->email->send();
            return TRUE;
        } else if (!empty($email_setting) && $email_setting->mail_protocol != 'smtp') {
            $this->email->send();
            return TRUE;
        }else{
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From:  ".$this->input->post('name')." < ".$this->input->post('email')." >\r\n";
            $headers .= "Reply-To:  ".$this->input->post('name')." < ".$this->input->post('email')." >\r\n"; 
            mail($setting->email, $subject, $message, $headers);
            return TRUE;
        }
        
        return FALSE;
    }

}
