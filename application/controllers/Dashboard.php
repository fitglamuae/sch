<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ***************Dashboard.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Dashboard
 * @description     : This class used to showing basic statistics of whole application 
 *                    for logged in user.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers    
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Dashboard extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Dashboard_Model', 'dashboard', true);
    }

    public $data = array();

    /*     * ***************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Default function, Load logged in user dashboard stattistics  
     * @param           : null 
     * @return          : null 
     * ********************************************************** */

    public function index() {
        
       
      $this->data['year_session'] = $this->dashboard->get_single('academic_years', array('is_running' => 1));

        $this->data['news'] = $this->dashboard->get_list('news', array('status' => 1), '', '5', '', 'id', 'ASC');
        $this->data['notices'] = $this->dashboard->get_list('notices', array('status' => 1), '', '5', '', 'id', 'ASC');
        $this->data['events'] = $this->dashboard->get_list('events', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['holidays'] = $this->dashboard->get_list('holidays', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['theme'] = $this->dashboard->get_single('themes', array('status' => 1, 'is_active' => 1));
        $this->data['users'] = $this->dashboard->get_user_by_role();
        $this->data['students'] = $this->dashboard->get_student_by_class();

        $this->data['total_student'] = $this->dashboard->get_total_student();
        $this->data['total_guardian'] = $this->dashboard->get_total_guardian();
        $this->data['total_teacher'] = $this->dashboard->get_total_teacher();
        $this->data['total_employee'] = $this->dashboard->get_total_employee();
        $this->data['total_expenditure'] = $this->dashboard->get_total_expenditure();
        $this->data['total_income'] = $this->dashboard->get_total_income();
       

        $this->data['sents'] = $this->dashboard->get_message_list($type = 'sent');
        $this->data['drafts'] = $this->dashboard->get_message_list($type = 'draft');
        $this->data['trashs'] = $this->dashboard->get_message_list($type = 'trash');
        $this->data['inboxs'] = $this->dashboard->get_message_list($type = 'inbox');
        $this->data['new'] = $this->dashboard->get_message_list($type = 'new');
        
        if($this->session->userdata('role_id') == STUDENT){
            $this->data['class_name'] = $this->db->get_where('classes', array('id'=>$this->session->userdata('class_id')))->row()->name;
        }
        
        $this->layout->title($this->lang->line('dashboard') . ' | ' . SMS);
        $this->layout->view('dashboard', $this->data);
    }
    
    
    
    public function get_search(){
        
        $keyword = $this->input->post('keyword');
        
        if(strlen($keyword) < 3){
          echo  $blank_str = '<div class="search-message-container">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <div class="search-message" style="padding: 6px;font-weight: bold;">'.$this->lang->line('type_atleast_3_characters').'</div>
                    </div>
                    <div class="clearfix"></div>
                </div>';
            die();
        }
        
        
        $blank_str = '<div class="search-message-container">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <div class="search-message" style="padding: 6px;font-weight: bold;">'.$this->lang->line('no_search_result_found').'</div>
                    </div>
                    <div class="clearfix"></div>
                </div>';
        
        
        $students = $this->dashboard->get_search_student($keyword);
        $guardians = $this->dashboard->get_search_guardian($keyword);
        $teachers = $this->dashboard->get_search_teacher($keyword);
        $employees = $this->dashboard->get_search_employee($keyword);
        
        $result_str = '';
            
        
        //===================   STUDENT ===========================================
        if(has_permission(VIEW, 'student', 'student')){
            
        if(!empty($students)){
            
           $result_str .= '<div class="col-md-12 col-sm-12 col-xs-12">
                                <div style="padding: 6px;font-weight: bold;background: #ecf7fb; margin-bottom: 5px;">'.$this->lang->line('student').'</div>
                            </div>
                            <div class="clearfix"></div>';
            
            foreach($students as $obj){
               $result_str .= '<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                            <div class="well profile_view">
                              <div class="col-sm-12">
                                 <div class="left col-xs-3" style="padding: 0;">';
               
                                if($obj->photo != ''){
                        $result_str .= '<img src="'.UPLOAD_PATH.'student-photo/'.$obj->photo.'" alt="" class="img-circle img-responsive">';             
                                }else{
                        $result_str .= '<img src="'.IMG_URL.'default-user.png" alt="" class="img-circle img-responsive">'; 
                                }
                                  
                                
                        $result_str .= '</div>
                                <div class="right col-xs-9">
                                  <h3>'. substr($obj->name, 0, 22).'</h3>
                                  <hr/>
                                  <p><strong>'.$obj->session_year.'</strong></p>
                                  <ul class="list-unstyled_" style="padding-left:12px;">
                                        <li>'.$this->lang->line('class').' : '.$obj->class_name.', '.$this->lang->line('section').' : '.$obj->section.'</li>
                                        <li>'.$this->lang->line('roll_no').' : '.$obj->roll_no.'</li>
                                        <li>'.$this->lang->line('birth_date').' : '.date('M j, Y', strtotime($obj->dob)).'</li>
                                    </ul>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 bottom text-center">
                                  <a href="'.site_url('student/view/'.$obj->id).'" type="button" class="btn btn-success btn-xs">
                                    <i class="fa fa-user"> </i> '.$this->lang->line('view_profile').'
                                  </a>                           
                              </div>
                            </div>
                          </div>'; 
                }
            }
                
        }
        //===================   GUARDIAN ===========================================
        if(has_permission(VIEW, 'guardian', 'guardian')){
            
        if(!empty($guardians)){
            
           $result_str .= '<div class="col-md-12 col-sm-12 col-xs-12">
                                <div style="padding: 6px;font-weight: bold;background: #ecf7fb; margin-bottom: 5px;">'.$this->lang->line('guardian').'</div>
                            </div>
                            <div class="clearfix"></div>';
            
            foreach($guardians as $obj){
               $result_str .= '<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                            <div class="well profile_view">
                              <div class="col-sm-12">
                                 <div class="left col-xs-3" style="padding: 0;">';
               
                                if($obj->photo != ''){
                        $result_str .= '<img src="'.UPLOAD_PATH.'guardian-photo/'.$obj->photo.'" alt="" class="img-circle img-responsive">';             
                                }else{
                        $result_str .= '<img src="'.IMG_URL.'default-user.png" alt="" class="img-circle img-responsive">'; 
                                }
                                  
                                
                        $result_str .= '</div>
                                <div class="right col-xs-9">
                                  <h3>'. substr($obj->name, 0, 22).'</h3>
                                  <hr/>
                                  <p><strong>'.$obj->profession.'</strong></p>
                                  <p><i class="fa fa-phone"></i> '.$obj->phone.'</p>                                  
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 bottom text-center">
                                  <a href="'.site_url('guardian/view/'.$obj->id).'" type="button" class="btn btn-success btn-xs">
                                    <i class="fa fa-user"> </i>  '.$this->lang->line('view_profile').'
                                  </a>                           
                              </div>
                            </div>
                          </div>'; 
                }
            }
        
        }        
        
        //===================   TEACHER ===========================================
        if(has_permission(VIEW, 'teacher', 'teacher')){
            
        if(!empty($teachers)){
            
           $result_str .= '<div class="col-md-12 col-sm-12 col-xs-12">
                                <div style="padding: 6px;font-weight: bold;background: #ecf7fb; margin-bottom: 5px;">'.$this->lang->line('teacher').'</div>
                            </div>
                            <div class="clearfix"></div>';
            
            foreach($teachers as $obj){
               $result_str .= '<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                            <div class="well profile_view">
                              <div class="col-sm-12">
                                <div class="left col-xs-3" style="padding: 0;">';
                                if($obj->photo != ''){
                        $result_str .= '<img src="'.UPLOAD_PATH.'teacher-photo/'.$obj->photo.'" alt="" class="img-circle img-responsive">';             
                                }else{
                        $result_str .= '<img src="'.IMG_URL.'default-user.png" alt="" class="img-circle img-responsive">'; 
                                }
                                  
                                
                    $result_str .= '</div>
                                <div class="right col-xs-9">
                                  <h3>'.$obj->name.'</h3>
                                  <hr/>
                                  <p><strong>'.$obj->department.'</strong></p>
                                  <p><i class="fa fa-phone"></i> '.$obj->phone.'</p>                                  
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 bottom text-center">
                                  <a href="'.site_url('teacher/view/'.$obj->id).'" type="button" class="btn btn-success btn-xs">
                                    <i class="fa fa-user"> </i> '.$this->lang->line('view_profile').'
                                  </a>                           
                              </div>
                            </div>
                          </div>'; 
                }
            }
        
        }        
        
        //===================   EMPLOYEE ===========================================
        if(has_permission(VIEW, 'hrm', 'employee')){
            
        if(!empty($employees)){
            
           $result_str .= '<div class="col-md-12 col-sm-12 col-xs-12">
                                <div style="padding: 6px;font-weight: bold;background: #ecf7fb; margin-bottom: 5px;">'.$this->lang->line('employee').'</div>
                            </div>
                            <div class="clearfix"></div>';
            
            foreach($employees as $obj){
               $result_str .= '<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                            <div class="well profile_view">
                              <div class="col-sm-12">
                                <div class="left col-xs-3" style="padding: 0;">';
                                if($obj->photo != ''){
                        $result_str .= '<img src="'.UPLOAD_PATH.'employee-photo/'.$obj->photo.'" alt="" class="img-circle img-responsive">';             
                                }else{
                        $result_str .= '<img src="'.IMG_URL.'default-user.png" alt="" class="img-circle img-responsive">'; 
                                }
                                  
                                
                    $result_str .= '</div>
                                <div class="right col-xs-9">
                                  <h3>'.$obj->name.'</h3>
                                  <hr/>
                                  <p><strong>'.$obj->designation.'</strong></p>
                                  <p><i class="fa fa-phone"></i> '.$obj->phone.'</p>                                  
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 bottom text-center">
                                  <a href="'.site_url('hrm/employee/view/'.$obj->id).'" type="button" class="btn btn-success btn-xs">
                                    <i class="fa fa-user"> </i>  '.$this->lang->line('view_profile').'
                                  </a>                           
                              </div>
                            </div>
                          </div>'; 
                }
            }        
        }       
        
        $count_str = '<div class="search-message-container">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <div class="search-message" style="padding: 6px;font-weight: bold;"> '.(count($students)+ count($guardians)+count($teachers)+count($employees)).' '.$this->lang->line('search_result_found').'.</div>
                    </div>
                    <div class="clearfix"></div>
                </div>';
        
        
        if($result_str){            
           echo $count_str.$result_str;
            
        }else{
           echo $blank_str;
        }
       
    }   

}