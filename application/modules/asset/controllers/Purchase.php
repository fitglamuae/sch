<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Purchase.php**********************************
 * @purchase title    : Global School Management System Pro
 * @type            : Purchase
 * @class name      : purchase
 * @description     : Manage school Asset purchase.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Purchase extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Purchase_Model', 'purchase', true);        
    }

    
    /*****************Function purchases**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "purchase List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['purchases'] = $this->purchase->get_purchase_list();
        $this->data['vendors'] = $this->purchase->get_list('asset_vendors', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['categories'] = $this->purchase->get_list('asset_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['employees'] = $this->purchase->get_list('employees', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_purchase') . ' | ' . SMS);
        $this->layout->view('purchase/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new purchase" user interface                 
    *                    and process to store "purchase" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_purchase_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_purchase_data();
                $insert_id = $this->purchase->insert('asset_purchases', $data);
                
                if ($insert_id) {  
                    
                    $this->__update_stock();
                     
                    success($this->lang->line('insert_success'));
                    redirect('asset/purchase/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('asset/purchase/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['purchases'] = $this->purchase->get_purchase_list();
        $this->data['vendors'] = $this->purchase->get_list('asset_vendors', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['categories'] = $this->purchase->get_list('asset_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['employees'] = $this->purchase->get_list('employees', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('purchase/index', $this->data);
    }

    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "purchase" user interface                 
    *                    with populated "purchase" value 
    *                    and process update "purchase" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('asset/purchase/index');
        }
        
        if ($_POST) {
            $this->_prepare_purchase_validation();
            if ($this->form_validation->run() === TRUE) {
                
                $data = $this->_get_posted_purchase_data();
                $updated = $this->purchase->update('asset_purchases', $data, array('id' => $this->input->post('id')));

                if ($updated) {

                    $this->__update_stock();
                    
                    success($this->lang->line('update_success'));
                    redirect('asset/purchase/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('asset/purchase/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['purchase'] = $this->purchase->get_single('asset_purchases', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['purchase'] = $this->purchase->get_single('asset_purchases', array('id' => $id));

            if (!$this->data['purchase']) {
                redirect('asset/purchase/index');
            }
        }

        $this->data['purchases'] = $this->purchase->get_purchase_list();
        $this->data['vendors'] = $this->purchase->get_list('asset_vendors', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['categories'] = $this->purchase->get_list('asset_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['employees'] = $this->purchase->get_list('employees', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('purchase/index', $this->data);
    }

      
     /*****************Function get_single_purchase**********************************
     * @type            : Function
     * @function name   : get_single_purchase
     * @description     : "Load single purchase information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_purchase(){
        
       $purchase_id = $this->input->post('purchase_id');   
       $this->data['purchase'] = $this->purchase->get_single_purchase($purchase_id);
       echo $this->load->view('purchase/get-single-purchase', $this->data);
    }

        
    /*****************Function _prepare_purchase_validation**********************************
    * @type            : Function
    * @function name   : _prepare_purchase_validation
    * @description     : Process "purchase" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_purchase_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('vendor_id',' ' . $this->lang->line('vendor'), 'trim|required');
        $this->form_validation->set_rules('employee_id',' ' . $this->lang->line('purchase_by'), 'trim|required');
        $this->form_validation->set_rules('category_id',' ' . $this->lang->line('category'), 'trim|required');
        $this->form_validation->set_rules('item_id',' ' . $this->lang->line('item'), 'trim|required');
        $this->form_validation->set_rules('qty',' ' . $this->lang->line('quantity'), 'trim|required'); 
        $this->form_validation->set_rules('unit_type',' ' . $this->lang->line('unit_type'), 'trim'); 
        $this->form_validation->set_rules('unit_price',' ' . $this->lang->line('unit_price'), 'trim|required');
        $this->form_validation->set_rules('purchase_date',' ' . $this->lang->line('purchase_date'), 'trim|required');
        $this->form_validation->set_rules('expire_date',' ' . $this->lang->line('expire_date'), 'trim');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }


    
    /*****************Function _get_posted_purchase_data**********************************
    * @type            : Function
    * @function name   : _get_posted_purchase_data
    * @description     : Prepare "purchase" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_purchase_data() {

        $items = array();
        $items[] = 'vendor_id';
        $items[] = 'employee_id';
        $items[] = 'category_id';
        $items[] = 'item_id';
        $items[] = 'qty';
        $items[] = 'unit_type';
        $items[] = 'unit_price';
        $items[] = 'note';
        
        $data = elements($items, $_POST);
        
        $data['purchase_date'] = date('Y-m-d', strtotime($this->input->post('purchase_date')));
        $data['expire_date'] = date('Y-m-d', strtotime($this->input->post('expire_date')));
        $data['total_price'] = $data['qty'] * $data['unit_price'];

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
    * @description     : delete "purchase" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('asset/purchase/index');
        }
        
        $purchase = $this->purchase->get_single('asset_purchases', array('id' => $id));
        $stock = $this->purchase->get_single('asset_stocks', array('item_id' => $purchase->item_id));
        
        if(!empty($stock) && $purchase->qty > $stock->total_qty){
            error($this->lang->line('purchase_qty_already_used'));
            redirect('asset/purchase/index');   
        }
        
        if ($this->purchase->delete('asset_purchases', array('id' => $id))) {
            
             // reduce item qty from main qty
            $sql = "UPDATE asset_stocks SET total_qty = total_qty-$purchase->qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id = $purchase->item_id";
            $this->db->query($sql);
            
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        
       redirect('asset/purchase/index');
    }
    
    
    private function __update_stock(){
        
        $data = array();
        $item_id = $this->input->post('item_id');
        $old_item_id = $this->input->post('old_item_id');
        
        $stock = $this->purchase->get_single('asset_stocks', array('status' => 1, 'item_id'=>$item_id));        
        if (empty($stock)) {
            
            $data['item_id'] = $item_id;
            $data['total_qty'] = 0;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id(); 
            $this->purchase->insert('asset_stocks', $data);
        }
        
        
        $qty = $this->input->post('qty');
        
        if ($this->input->post('id') && $item_id == $old_item_id) {
            
            $old_qty = $this->input->post('old_qty');
            
            $sql = "UPDATE asset_stocks SET total_qty = total_qty-$old_qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id = $item_id";
            $this->db->query($sql);
            
            $sql = "UPDATE asset_stocks SET total_qty = total_qty+$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id = $item_id";
            $this->db->query($sql);
            
        }else if ($this->input->post('id') && $item_id != $old_item_id) {
            
            $old_qty = $this->input->post('old_qty');
            
            // for old item
            $sql = "UPDATE asset_stocks SET total_qty = total_qty-$old_qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id=$old_item_id";
            $this->db->query($sql);
            
            // for new item 
            $sql = "UPDATE asset_stocks SET total_qty = total_qty+$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id=$item_id";
            $this->db->query($sql);  
                        
        }else{
            
            $sql = "UPDATE asset_stocks SET total_qty = total_qty+$qty , modified_at = '".date('Y-m-d H:i:s')."' WHERE item_id=$item_id";
            $this->db->query($sql);
        }        
    }
}
