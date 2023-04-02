<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Receipt.php**********************************
 * @product name    : Global Multi School Management System Express
 * @type            : Class
 * @class name      : Receipt
 * @description     : Manage invoice Receipt for all type of student payment.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Receipt extends MY_Controller {

    public $data = array();    
    
    function __construct() {
        
        parent::__construct();
         $this->load->model('Receipt_Model', 'receipt', true);
         $this->load->model('Payment_Model', 'payment', true);
         $this->data['roles'] = $this->receipt->get_list('roles', array('status' => 1), '','', '', 'id', 'ASC');
         $this->data['classes'] = $this->receipt->get_list('classes', array('status'=>1), '','', '', 'id', 'ASC');
    }

    
    /*****************Function duereceipt**********************************
    * @type            : Function
    * @function name   : duereceipt
    * @description     : Load "DUE Receipt List" user interface                
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function duereceipt() {    
        
        check_permission(VIEW);
         
        $this->data['receipts'] = '';
        if ($_POST) {
             
            $role_id = $this->input->post('role_id');  
            $class_id = $this->input->post('class_id'); 
            $student_id = $this->input->post('student_id');
            
            $user_id = '';
            if($role_id == STUDENT){
                $student = $this->receipt->get_single('students', array('id' => $student_id)); 
                $user_id = $student->user_id;
            }else{
                $class_id = '';
                $student_id = '';
                $user_id = $this->input->post('user_id'); 
            }                     
                       
            $this->data['receipts'] = $this->receipt->get_due_receipt_list($role_id, $class_id, $user_id);   
            $this->data['role_id'] = $role_id;
            $this->data['class_id'] = $class_id;           
            $this->data['student_id'] = $student_id;
            $this->data['user_id'] = $user_id;           
            
         }    
         
           
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_due_receipt') . ' | ' . SMS);
        $this->layout->view('receipt/due-receipt', $this->data);            
       
    }
    
    
    /*****************Function get_single_due_receipt**********************************
    * @type            : Function
    * @function name   : due
    * @description     : Load "get_single_due_receipt" user interface                
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function get_single_due_receipt() {    
        
        check_permission(VIEW);
        
        $inv_id = $this->input->post('inv_id'); 
      
        $txn_amount                = $this->payment->get_invoice_amount($inv_id);        
        $this->data['paid_amount'] = $txn_amount->paid_amount;
        $this->data['receipt'] = $this->receipt->get_single_due_receipt($inv_id);
        $this->data['receipt_items'] = $this->receipt->get_invoice_item($inv_id, $this->data['receipt']->invoice_type);
        
        echo $this->load->view('receipt/get-single-due-receipt', $this->data);
    }    
   
    
    /*****************Function paidreceipt**********************************
    * @type            : Function
    * @function name   : paidreceipt
    * @description     : Load "PAID Receipt List" user interface                
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function paidreceipt() {    
        
        check_permission(VIEW);
         
        $this->data['receipts'] = '';
        if ($_POST) {             
            
            $role_id = $this->input->post('role_id');  
            $class_id = $this->input->post('class_id'); 
            $student_id = $this->input->post('student_id');
            
            $user_id = '';
            if($role_id == STUDENT){
                $student = $this->receipt->get_single('students', array('id' => $student_id)); 
                $user_id = $student->user_id;
            }else{
                $class_id = '';
                $student_id = '';
                $user_id = $this->input->post('user_id'); 
            }                   
                       
            $this->data['receipts'] = $this->receipt->get_paid_receipt_list($role_id, $class_id, $user_id);           
            $this->data['role_id'] = $role_id;
            $this->data['class_id'] = $class_id;           
            $this->data['student_id'] = $student_id;
            $this->data['user_id'] = $user_id; 
         }
         
                
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_paid_receipt') . ' | ' . SMS);
        $this->layout->view('receipt/paid-receipt', $this->data);            
       
    }
    
    
    /*****************Function get_single_paid_receipt**********************************
    * @type            : Function
    * @function name   : paid
    * @description     : Load "get_single_paid_receipt" user interface                
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function get_single_paid_receipt() {    
        
        check_permission(VIEW);
       
        
        $txn_id = $this->input->post('txn_id');         

        $txn_amount                = $this->payment->get_invoice_paid_amount($txn_id);        
        $this->data['paid_amount'] = $txn_amount->paid_amount;
        $this->data['receipt'] = $this->receipt->get_single_paid_receipt($txn_id);
        $this->data['receipt_items'] = $this->receipt->get_invoice_item($this->data['receipt']->inv_id, $this->data['receipt']->invoice_type);   
        
        echo $this->load->view('receipt/get-single-paid-receipt', $this->data);
    }    
   
}