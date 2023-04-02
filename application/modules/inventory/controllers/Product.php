<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Product.php**********************************
 * @product title    : Global School Management System Pro
 * @type            : Class
 * @class name      : product
 * @description     : Manage school Inventory product.  
 * @author          : Codetroopers Team 	
 * @url             : https://themeforest.net/user/codetroopers      
 * @support         : yousuf361@gmail.com	
 * @copyright       : Codetroopers Team	 	
 * ********************************************************** */

class Product extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Product_Model', 'product', true);        
    }

    
    /*****************Function product**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Product List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);

        $this->data['products'] = $this->product->get_product_list();
        $this->data['catagories'] = $this->product->get_list('item_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['warehouses'] = $this->product->get_list('item_warehouses', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_product') . ' | ' . SMS);
        $this->layout->view('product/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Product" user interface                 
    *                    and process to store "Product" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_product_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_product_data();

                $insert_id = $this->product->insert('item_products', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a product : '.$data['name']);
                    success($this->lang->line('insert_success'));
                    redirect('inventory/product/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('inventory/product/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['products'] = $this->product->get_product_list(); 
        $this->data['catagories'] = $this->product->get_list('item_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['warehouses'] = $this->product->get_list('item_warehouses', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' | ' . SMS);
        $this->layout->view('product/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Product" user interface                 
    *                    with populated "Product" value 
    *                    and process update "Product" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('inventory/product/index');
        }
        
        if ($_POST) {
            $this->_prepare_product_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_product_data();
                $updated = $this->product->update('item_products', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a product : '.$data['name']); 
                    success($this->lang->line('update_success'));
                    redirect('inventory/product/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('inventory/product/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['product'] = $this->product->get_single('item_products', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['product'] = $this->product->get_single('item_products', array('id' => $id));

            if (!$this->data['product']) {
                redirect('inventory/product/index');
            }
        }

        $this->data['products'] = $this->product->get_product_list(); 
        $this->data['catagories'] = $this->product->get_list('item_categories', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['warehouses'] = $this->product->get_list('item_warehouses', array('status' => 1), '','', '', 'id', 'ASC');
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' | ' . SMS);
        $this->layout->view('product/index', $this->data);
    }

      
           
     /*****************Function get_single_product**********************************
     * @type            : Function
     * @function name   : get_single_product
     * @description     : "Load single productinformation" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_product(){
        
       $product_id = $this->input->post('product_id');   
       $this->data['product'] = $this->product->get_single_product($product_id);       
       echo $this->load->view('product/get-single-product', $this->data);
    }

        
    /*****************Function _prepare_product_validation**********************************
    * @type            : Function
    * @function name   : _prepare_product_validation
    * @description     : Process "product" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_product_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('category_id',' ' . $this->lang->line('catagory'), 'trim|required');
        $this->form_validation->set_rules('warehouse_id',' ' . $this->lang->line('warehouse'), 'trim|required');
        $this->form_validation->set_rules('name',' ' . $this->lang->line('name'), 'trim|required|callback_name');
        $this->form_validation->set_rules('code',' ' . $this->lang->line('code'), 'trim'); 
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }
    
    
        
    /*****************Function name**********************************
    * @type            : Function
    * @function name   : name
    * @description     : Unique check for "product name" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function name() {
        if ($this->input->post('id') == '') {
            $cat = $this->product->duplicate_check($this->input->post('name'), $this->input->post('category_id'));
            if ($cat) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $cat = $this->product->duplicate_check($this->input->post('name'), $this->input->post('category_id'), $this->input->post('id'));
            if ($cat) {
                $this->form_validation->set_message('name', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
    
    
    /*****************Function _get_posted_product_data**********************************
    * @type            : Function
    * @function name   : _get_posted_product_data
    * @description     : Prepare "product" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_product_data() {

        $items = array();
        $items[] = 'category_id';
        $items[] = 'warehouse_id';
        $items[] = 'code';
        $items[] = 'name';
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
    * @description     : delete "product" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('inventory/product/index');
        }
        
        $product = $this->product->get_single('item_products', array('id' => $id));
        
        if ($this->product->delete('item_products', array('id' => $id))) {
            
            create_log('Has been deleted a product : '.$product->name);   
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
       redirect('inventory/product/index');
    }
    
    
          /*****************Function get_product_by_category**********************************
     * @type            : Function
     * @function name   : get_product_by_category
     * @description     : "Load single Product information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_product_by_category(){
        
       $product_id = $this->input->post('product_id');  
       $category_id = $this->input->post('category_id');
       $products = $this->product->get_list('item_products', array('category_id' => $category_id, 'status'=>1));
       
        $str = '<option value="">--' . $this->lang->line('select') . '--</option>';            
        $select = 'selected="selected"';
        
        if (!empty($products)) {
            foreach ($products as $obj) {
                $selected = $product_id == $obj->id ? $select : '';
                $str .= '<option value="' . $obj->id . '" ' . $selected . '>' . $obj->name .' </option>';
            }
        }

        echo $str;
    
    }
}
