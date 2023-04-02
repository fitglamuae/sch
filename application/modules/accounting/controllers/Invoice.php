<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Invoice.php**********************************
 * @product name    : Global School Management System Pro
 * @type            : Class
 * @class name      : Invoice
 * @description     : Manage invoice for all type of student payment.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Invoice extends MY_Controller {

    public $data = array();    
    
    function __construct() {
        
        parent::__construct();
         $this->load->model('Invoice_Model', 'invoice', true);
         $this->load->model('Payment_Model', 'payment', true);
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Invoice List" user interface                 
    *                        
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index($invoice_id = null) {
        
        check_permission(VIEW);     
        
        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');
        $this->data['income_heads'] = $this->invoice->get_fee_type();         
        $this->data['invoices'] = $this->invoice->get_invoice_list();  
         
        $this->data['invoice_id'] = $invoice_id;
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/index', $this->data); 
    }
    
    
    
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific invoice data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {
        
        check_permission(VIEW);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('accounting/invoice/index');
        }
        
        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');        
        $this->data['income_heads'] = $this->invoice->get_fee_type(); 
        $this->data['invoices'] = $this->invoice->get_invoice_list();  
         
        $this->data['settings'] = $this->invoice->get_single('settings', array('status'=>1));
        $invoice                = $this->payment->get_invoice_amount($id);
        
        $this->data['paid_amount'] = $invoice->paid_amount;
        $this->data['invoice'] = $this->invoice->get_single_invoice($id);  
  
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('view').' | ' . SMS);
        $this->layout->view('invoice/view', $this->data);            
       
    }
    
    
           /*****************Function get_single_invoice**********************************
    * @type            : Function
    * @function name   : paid
    * @description     : Load "get_single_invoice" user interface                
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function get_single_invoice() {    
        
        $id = $this->input->post('invoice_id'); 
        
        $txn_amount                = $this->payment->get_invoice_amount($id);        
        $this->data['paid_amount'] = $txn_amount->paid_amount;
        $this->data['invoice'] = $this->invoice->get_single_invoice($id);
        $this->data['invoice_items'] = $this->invoice->get_invoice_item($id, $this->data['invoice']->invoice_type);
        $this->data['settings'] = $this->invoice->get_single('settings', array('status'=>1));
        
        if($this->data['invoice']->invoice_type == 'sale'){
            
            $this->data['sale'] = $this->data['invoice'];
            $this->data['sale_items'] = $this->data['invoice_items'];
            echo $this->load->view('inventory/sale/get-single-sale', $this->data);  
        }else{
            echo $this->load->view('invoice/get-single-invoice', $this->data);            
        }
    }
    

    
     /*****************Function due**********************************
    * @type            : Function
    * @function name   : due
    * @description     : Load "Due Invoice List" user interface                 
    *                        
    * @param           : null
    * @return          : null 
    * ***********************************************************/
    public function due() {    
        
        check_permission(VIEW);
              
        $this->data['invoices'] = $this->invoice->get_invoice_list(true);  
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('due_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/due', $this->data);            
       
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Create new Invoice" user interface                 
    *                    and store "Invoice" data into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $insert_id = $this->_get_posted_invoice_data();
                
                if ($insert_id) { 
                    
                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');     
        $this->data['income_heads'] = $this->invoice->get_fee_type(); 
        $this->data['invoices'] = $this->invoice->get_invoice_list();  
        
        $this->data['single'] = TRUE;
        $this->layout->title($this->lang->line('create_invoice').' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

        
    /*****************Function bulk**********************************
    * @type            : Function
    * @function name   : bulk
    * @description     : Load "Create new bulk Invoice" user interface                 
    *                    and store "Invoice" data into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function bulk() {

        check_permission(ADD);
        
        if ($_POST) {
           
            $this->_prepare_invoice_validation();           
            if ($this->form_validation->run() === TRUE) {
               
                 $status = $this->_get_create_bulk_invoice();
                if ($status) {                    
                    success($this->lang->line('insert_success'));
                    redirect('accounting/invoice/index');
                    
                } else {                  
                    error($this->lang->line('insert_failed'));
                    redirect('accounting/invoice/bulk');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');       
        $this->data['income_heads'] = $this->invoice->get_fee_type(); 
        $this->data['invoices'] = $this->invoice->get_invoice_list();  
        
        $this->data['bulk'] = TRUE;
        $this->layout->title($this->lang->line('create_invoice').' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Invoice" user interface                 
    *                    with populated "Invoice" value 
    *                    and update "Invoice" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
             redirect('accounting/invoice/index');
        }
        
        if ($_POST) {
            $this->_prepare_invoice_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_invoice_data();
                $updated = $this->invoice->update('invoices', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a invoice : '. $data['net_amount']);
                    
                    success($this->lang->line('update_success'));
                    redirect('accounting/invoice/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('accounting/invoice/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['invoice'] = $this->invoice->get_single('invoices', array('id' => $id));

            if (!$this->data['invoice']) {
                 redirect('accounting/invoice/index');
            }
        }
        
        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');       
        $this->data['income_heads'] = $this->invoice->get_fee_type();        
        $this->data['invoices'] = $this->invoice->get_invoice_list();  

        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit').' | ' . SMS);
        $this->layout->view('invoice/index', $this->data);
    }

    
    /*****************Function _prepare_invoice_validation**********************************
    * @type            : Function
    * @function name   : _prepare_invoice_validation
    * @description     : Process "Invoice" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_invoice_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required'); 
        $this->form_validation->set_rules('paid_status', $this->lang->line('paid_status').'trim|required'); 
        
        if($this->input->post('type')== 'single'){
            $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'trim|required'); 
            $this->form_validation->set_rules('amount', $this->lang->line('fee_amount'), 'trim|required');  
        }
        
        $this->form_validation->set_rules('is_applicable_discount', $this->lang->line('is_applicable_discount'), 'trim|required');   
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required');   
            
        if($this->input->post('paid_status')== 'paid'){
           $this->form_validation->set_rules('payment_method', $this->lang->line('payment_method'), 'trim|required');   
        }
    }


    
    /*****************Function _get_posted_invoice_data**********************************
     * @type            : Function
     * @function name   : _get_posted_invoice_data
     * @description     : Prepare "Invoice" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_invoice_data() {

        $discount = array();
        $items = array();
        $items[] = 'class_id';
        $items[] = 'student_id';
        $items[] = 'is_applicable_discount';  
        $items[] = 'month';        
        $items[] = 'paid_status';        
        $items[] = 'note';
        
        $data = elements($items, $_POST);          
                            
        $data['discount'] = 0.00;
        $data['gross_amount'] = $this->input->post('amount');
        $data['net_amount']   = $this->input->post('amount');
        $data['invoice_type'] = 'invoice';
        
        if(!isset($_POST['income_head_id'])){
            error($this->lang->line('select').' '.$this->lang->line('income_head'));
            redirect('accounting/invoice/add');
        }
        
        if($data['is_applicable_discount']){
            
            $discount = $this->invoice->get_student_discount($data['student_id']);
            if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                $data['discount']   = ($discount->amount/100)*$data['gross_amount'];
                $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                
            }elseif(!empty($discount) && $discount->discount_type == 'flat'){
                
                $data['discount']   = $discount->amount;
                $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                
            }
        }
        
        $data['date'] = date('Y-m-d');  
            
        $data['custom_invoice_id'] = $this->invoice->get_custom_id('invoices', 'INV');
        $data['role_id'] = STUDENT;
        $data['status'] = 1;

        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('accounting/invoice/index');
        }             

        $data['academic_year_id'] = $this->academic_year_id;

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = logged_in_user_id();   
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
                
         
        // save invoice data
        $student = $this->invoice->get_single('students', array('id' => $data['student_id']));
        $inv_data = $data;
        $inv_data['user_id'] =  $student->user_id;
        $inv_data['role_id'] =  STUDENT;
        unset($inv_data['student_id']);
         
         $invoice_id = $this->invoice->insert('invoices', $inv_data);
        
         
        // save invoice detail data
        foreach ($this->input->post('income_head_id') as $key=>$value){
            
            $inv_detail = array();
            $inv_detail['invoice_id'] = $invoice_id;
            $inv_detail['income_head_id'] = $key;            
            $inv_detail['invoice_type'] = $this->input->post('income_head_type_'.$key);
            
            $income_head = $this->invoice->get_single('income_heads', array('id' => $key));
                
            if($income_head->head_type == 'hostel' && $student->is_hostel_member == 0){
                continue;
            }elseif($income_head->head_type == 'transport' && $student->is_transport_member == 0){
                continue;
            }            
                        
            $amt = $this->__get_fee_amount($key, $data['student_id'], $data['class_id'], $income_head);
            $inv_detail['gross_amount'] = $amt;
            $inv_detail['discount'] = 0.00;
            $inv_detail['net_amount'] = $amt;
                
            if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                $inv_detail['discount']   = ($discount->amount/100)*$amt;
                $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
                               
            }elseif(!empty($discount) && $discount->discount_type == 'flat'){
                
                $inv_detail['discount']   = ($discount->amount/$data['gross_amount'])*$amt;
                $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
            }
                        
            
            $inv_detail['status'] = 1;
            $inv_detail['created_at'] = date('Y-m-d H:i:s');
            $inv_detail['created_by'] = logged_in_user_id();   
            $inv_detail['modified_at'] = date('Y-m-d H:i:s');
            $inv_detail['modified_by'] = logged_in_user_id();
            $this->invoice->insert('invoice_detail', $inv_detail);
            
        }
        
         // save transction table data
        $data['invoice_id'] = $invoice_id;
        $this->_save_transaction($data);
        
        create_log('Has been created a invoice : '. $data['net_amount']);
        return $invoice_id;
    }
    

        /*****************Function _get_create_bulk_invoice**********************************
     * @type            : Function
     * @function name   : _get_create_bulk_invoice
     * @description     : Prepare "Invoice" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_create_bulk_invoice() {
        
        $discount = array();
        $data = array();
       
        $items[] = 'class_id';       
        $items[] = 'is_applicable_discount';  
        $items[] = 'month'; 
        $items[] = 'paid_status';
        $items[] = 'note';
        
        $data = elements($items, $_POST);         
                
        $data['date'] = date('Y-m-d');            
        $data['discount'] = 0.00;
        $data['role_id'] = STUDENT;
        $data['status'] = 1;
        $data['invoice_type'] = 'invoice';
        
        
        if(!$this->academic_year_id){
            error($this->lang->line('set_academic_year_for_school'));
            redirect('accounting/invoice/bulk');
        } 
        
        $data['academic_year_id'] = $this->academic_year_id;
        
        if(!isset($_POST['income_head_id'])){
            error($this->lang->line('select').' '.$this->lang->line('income_head'));
            redirect('accounting/invoice/bulk');
        }
        
        if(!isset($_POST['students'])){
            error($this->lang->line('select_student'));
            redirect('accounting/invoice/bulk');
        }
      
         foreach ($this->input->post('students') as $student_id=>$value){
                 
            $data['student_id'] = $student_id;            
            $data['gross_amount'] = $value;
            $data['net_amount'] = $value;

            if($data['is_applicable_discount']){

                $discount = $this->invoice->get_student_discount($data['student_id']);
                if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                    $data['discount']   = ($discount->amount/100)*$data['gross_amount'];
                    $data['net_amount'] = $data['gross_amount'] - $data['discount'];

                }elseif(!empty($discount) && $discount->discount_type == 'flat'){

                    $data['discount']   = $discount->amount;
                    $data['net_amount'] = $data['gross_amount'] - $data['discount'];
                }
            }

            $data['custom_invoice_id'] = $this->invoice->get_custom_id('invoices', 'INV');
            
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
            $student = $this->invoice->get_single('students', array('id' => $student_id));            
            $inv_data = $data;
            $inv_data['user_id'] =  $student->user_id;
            $inv_data['role_id'] =  STUDENT;
            unset($inv_data['student_id']);
            $invoice_id = $this->invoice->insert('invoices', $inv_data);
            
             // save invoice detail data
            foreach ($this->input->post('income_head_id') as $key=>$value){

                $inv_detail = array();
                $inv_detail['invoice_id'] = $invoice_id;
                $inv_detail['income_head_id'] = $key;
                $inv_detail['invoice_type'] = $this->input->post('income_head_type_'.$key);
                
                $income_head = $this->invoice->get_single('income_heads', array('id' => $key));
                
                if($income_head->head_type == 'hostel' && $student->is_hostel_member == 0){
                    continue;
                }elseif($income_head->head_type == 'transport' && $student->is_transport_member == 0){
                    continue;
                } 
                
                $amt = $this->__get_fee_amount($key, $data['student_id'], $data['class_id'], $income_head);
                $inv_detail['gross_amount'] = $amt;
                $inv_detail['discount'] = 0.00;
                $inv_detail['net_amount'] = $amt;

                if(!empty($discount) && $discount->discount_type == 'percentage'){
                
                    $inv_detail['discount']   = ($discount->amount/100)*$amt;
                    $inv_detail['net_amount'] = $amt - $inv_detail['discount'];

                }elseif(!empty($discount) && $discount->discount_type == 'flat'){

                    $inv_detail['discount']   = ($discount->amount/$data['gross_amount'])*$amt;
                    $inv_detail['net_amount'] = $amt - $inv_detail['discount'];
                }

                $inv_detail['status'] = 1;
                $inv_detail['created_at'] = date('Y-m-d H:i:s');
                $inv_detail['created_by'] = logged_in_user_id();   
                $inv_detail['modified_at'] = date('Y-m-d H:i:s');
                $inv_detail['modified_by'] = logged_in_user_id();
                $this->invoice->insert('invoice_detail', $inv_detail);
            }
        
           
            // save transction table data
            $txn = array(); 
            $txn = $data;
            $txn['invoice_id'] = $invoice_id;
            $this->_save_transaction($txn);
           
            // reset some data
            $data['gross_amount'] = 0;
            $data['net_amount'] = 0;
            $data['discount'] = 0;
            $inv_detail['discount'] = 0;
        }
        
        $class = $this->invoice->get_single('classes', array('id' => $this->input->post('class_id')));
        create_log('Has been created for class '. $class->name);
        return TRUE; 
    }

    /***************** Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Invoice" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {
        
        check_permission(DELETE);
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
             redirect('accounting/invoice/index');
        } 
        
        $invoice = $this->invoice->get_single('invoices', array('id' => $id));
                
        if ($this->invoice->delete('invoices', array('id' => $id))) { 
            
            $this->invoice->delete('invoice_detail', array('invoice_id' => $id));
            $this->invoice->delete('transactions', array('invoice_id' => $id));
            
            create_log('Has been deleted a invoice : '. $invoice->net_amount);
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('accounting/invoice/index');
    }
    
    
    /*****************Function _save_transaction**********************************
     * @type            : Function
     * @function name   : _save_transaction
     * @description     : transaction data save/update into database 
     *                    while add/update income data into database                
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    private function _save_transaction($data){
        
        if($data['paid_status'] == 'paid'){
        
            $txn = array();
            $txn['amount'] = $data['net_amount'];  
            $txn['note'] = $data['note'];
            $txn['payment_date'] = $data['date'];
            $txn['payment_method'] = $this->input->post('payment_method');
            $txn['bank_name'] = $this->input->post('bank_name');
            $txn['cheque_no'] = $this->input->post('cheque_no');
            $txn['bank_receipt'] = $this->input->post('bank_receipt');

            if ($this->input->post('id')) {

                $txn['modified_at'] = date('Y-m-d H:i:s');
                $txn['modified_by'] = logged_in_user_id();
                $this->invoice->update('transactions', $txn, array('invoice_id'=>$this->input->post('id')));

            } else {            

                $txn['invoice_id'] = $data['invoice_id'];
                $txn['status'] = 1;
                $txn['academic_year_id'] = $data['academic_year_id'];            
                $txn['created_at'] = $data['created_at'];
                $txn['created_by'] = $data['created_by'];
                $txn['modified_at'] = date('Y-m-d H:i:s');
                $txn['modified_by'] = logged_in_user_id();
                $this->invoice->insert('transactions', $txn);
            }        
        }
    }
    
    
    
    /* AJAX*/
    
        // single
    public function get_single_fee_amount(){
        
        $class_id       = $this->input->post('class_id');       
        $income_head_id = $this->input->post('income_head_id');
        $student_id = $this->input->post('student_id');
        $amount = $this->input->post('amount');
        $check_status = $this->input->post('check_status');
        
        $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id));
        
        $amt = $this->__get_fee_amount($income_head_id, $student_id, $class_id, $income_head);
        
        if($check_status == 'true'){
           echo $amount+$amt;
        }else{
            echo $amount-$amt;
        } 
    }
    
        // bulk
    public function get_bulk_fee_amount(){
                
        $class_id       = $this->input->post('class_id');       
        $head_ids       = rtrim($this->input->post('head_ids'), ',');
        
        
        if(!$this->academic_year_id){  echo 'ay';   die();  } 
                    
        $students = $this->invoice->get_student_list($this->academic_year_id, $class_id, '', 'regular'); 
       
        $student_str = $this->lang->line('no_data_found');
        
        if(!empty($students) && $head_ids != ''){            
            
            $student_str = '';
            $head_ids_arr = explode(',', $head_ids);
             
            foreach($students as $obj){                
               
                $amount = 0.00;
                foreach($head_ids_arr as $income_head_id){
                    
                    $income_head = $this->invoice->get_single('income_heads', array('id' => $income_head_id));
                    $amount += $this->__get_fee_amount($income_head_id, $obj->id, $class_id, $income_head);                
                }                
                
                // making student string....
                $student_str .= '<div class="multi-check"><input type="checkbox" name="students['.$obj->id.']" value="'.$amount.'" /> '.$obj->name.' ['.$this->gsms_setting->currency_symbol.$amount.']</div>';
            }
        }
        
        echo $student_str;
    }

        // common
    private function __get_fee_amount($income_head_id, $student_id, $class_id, $income_head){
        
        $amt = 0.00;
                
        if($income_head->head_type == 'hostel'){
            
            $fee = $this->invoice->get_hostel_fee($student_id);            
            if(!empty($fee)){
                $amt += $fee->cost;
            }            
            
        }elseif($income_head->head_type == 'transport'){
            
            $fee = $this->invoice->get_transport_fee($student_id);            
            if(!empty($fee)){
                $amt += $fee->stop_fare;
            }
            
        }else{
            
            $fee = $this->invoice->get_single('fees_amount', array('class_id' => $class_id, 'income_head_id'=>$income_head_id));
            if(!empty($fee)){
                $amt += $fee->fee_amount;
            }
        }
        
        return $amt;
    }

        
    public function get_student_by_class() {

        $class_id = $this->input->post('class_id');
        $user_id = $this->input->post('user_id');
        $is_bulk = $this->input->post('is_bulk');
         
        $students = $this->invoice->get_student_list($this->academic_year_id, $class_id, $user_id, 'regular');

        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';
        if($is_bulk){
             $str .= '<option value="all">' . $this->lang->line('all') . '</option>';
        }
        
        $select = 'selected="selected"';
        if (!empty($students)) {
            foreach ($students as $obj) {
                $selected = $student_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name . ' [' . $obj->roll_no . ']</option>';
            }
        }

        echo $str;
    }

  
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific invoice data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function multi() {
        
        check_permission(VIEW);
        
        if($_POST){
            
             $month = $this->input->post('month');             
             $class_id = $this->input->post('class_id');
             $this->data['invoices'] = $this->invoice->get_invoice_by_type($class_id, $month);             
            $this->data['month'] = $month;
            $this->data['class_id'] = $class_id;
        }
        
        
        $this->data['classes'] = $this->invoice->get_list('classes', array('status'=> 1), '', '', '', 'id', 'ASC');
        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('print_multi_invoice'). ' | ' . SMS);
        $this->layout->view('invoice/multi', $this->data);            
       
    }    
}