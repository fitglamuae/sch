<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-money"></i><small> <?php echo $this->lang->line('manage_purchase'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                <?php $this->load->view('quick-link'); ?>              
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_purchase_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'asset', 'purchase')){ ?>
                            <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_purchase"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                        <?php } ?>
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_purchase"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                        <?php } ?>                              
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_purchase_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                         <th><?php echo $this->lang->line('vendor'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('asset'); ?></th>
                                        <th><?php echo $this->lang->line('purchase_by'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('unit_price'); ?></th>
                                        <th><?php echo $this->lang->line('total_price'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($purchases) && !empty($purchases)){ ?>
                                        <?php foreach($purchases as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $obj->vendor_name; ?></td>
                                            <td><?php echo $obj->cat_name; ?></td>
                                            <td><?php echo $obj->item_name; ?></td>
                                            <td><?php echo $obj->employee_name; ?></td>
                                            <td><?php echo $obj->qty; ?></td>
                                            <td><?php echo $obj->unit_price; ?></td>
                                            <td><?php echo $obj->total_price; ?></td>
                                            <td>
                                                <?php if(has_permission(VIEW, 'asset', 'purchase')){ ?>
                                                    <a  onclick="get_purchase_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-purchase-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(EDIT, 'asset', 'purchase')){ ?>
                                                    <a href="<?php echo site_url('asset/purchase/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?> 
                                                <?php if(has_permission(DELETE, 'asset', 'purchase')){ ?>
                                                    <a href="<?php echo site_url('asset/purchase/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_purchase">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('asset/purchase/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_id"><?php echo $this->lang->line('vendor'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="vendor_id"  id="add_vendor_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($vendors) && !empty($vendors)){ ?>
                                                <?php foreach($vendors as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post) && $post['vendor_id'] == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('vendor_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="category_id"  id="add_category_id" required="required" onchange="get_item_by_category(this.value, '', 'add');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($categories) && !empty($categories)){ ?>
                                                <?php foreach($categories as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post) && $post['category_id'] == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('category_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_id"><?php echo $this->lang->line('asset'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="item_id"  id="add_item_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                             
                                        </select>
                                        <div class="help-block"><?php echo form_error('item_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty"><?php echo $this->lang->line('quantity'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12 add_amount"  name="qty"  id="qty" value="<?php echo isset($post['qty']) ?  $post['qty'] : ''; ?>" placeholder="<?php echo $this->lang->line('quantity'); ?>" required="required" type="number">
                                        <div class="help-block"><?php echo form_error('qty'); ?></div>
                                    </div>
                                </div>                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_type"><?php echo $this->lang->line('unit_type'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="unit_type"  id="unit_type">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php $types = get_unit_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                              <option value="<?php echo $key; ?>" <?php if(isset($post) && $post['unit_type'] == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                             <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('unit_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_price"><?php echo $this->lang->line('unit_price'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12 add_amount"  name="unit_price"  id="unit_price" value="<?php echo isset($post['unit_price']) ?  $post['unit_price'] : ''; ?>" placeholder="<?php echo $this->lang->line('unit_price'); ?>" required="required" type="number">
                                        <div class="help-block"><?php echo form_error('unit_price'); ?></div>
                                    </div>
                                </div>     
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purchase_date"><?php echo $this->lang->line('purchase_date'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="purchase_date"  id="add_purchase_date" value="<?php echo isset($post['purchase_date']) ?  $post['purchase_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('purchase_date'); ?>" required="required"  type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('purchase_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expire_date"><?php echo $this->lang->line('expire_date'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="expire_date"  id="add_expire_date" value="<?php echo isset($post['expire_date']) ?  $post['expire_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('expire_date'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('expire_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_id"><?php echo $this->lang->line('purchase_by'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="employee_id"  id="add_employee_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($employees) && !empty($employees)){ ?>
                                                <?php foreach($employees as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($post) && $post['employee_id'] == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('employee_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm- col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($note) ?  $note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>                                             
                                    </div>  
                                </div>
                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('asset/purchase/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_purchase">
                            <div class="x_content">
                               
                                 <?php echo form_open(site_url('asset/purchase/edit/'.$purchase->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_id"><?php echo $this->lang->line('vendor'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="vendor_id"  id="edit_vendor_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($vendors) && !empty($vendors)){ ?>
                                                <?php foreach($vendors as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($purchase->vendor_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('vendor_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id"><?php echo $this->lang->line('category'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="category_id"  id="edit_category_id" required="required" onchange="get_item_by_category(this.value, '', 'edit');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($categories) && !empty($categories)){ ?>
                                                <?php foreach($categories as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($purchase->category_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('category_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_id"><?php echo $this->lang->line('asset'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="item_id"  id="edit_item_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                             
                                        </select>
                                        <div class="help-block"><?php echo form_error('item_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty"> <?php echo $this->lang->line('quantity'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="qty"  id="edit_qty" value="<?php echo isset($purchase->qty) ?  $purchase->qty : ''; ?>" placeholder=" <?php echo $this->lang->line('quantity'); ?>" required="required" type="number">
                                        <div class="help-block"><?php echo form_error('qty'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unit_type"><?php echo $this->lang->line('unit_type'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="unit_type"  id="unit_type" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php $types = get_unit_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php if($purchase->unit_type == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('unit_type'); ?></div>
                                        </div>
                                    </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="unit_price"> <?php echo $this->lang->line('unit_price'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="unit_price"  id="edit_unit_price" value="<?php echo isset($purchase->unit_price) ?  $purchase->unit_price : ''; ?>" placeholder=" <?php echo $this->lang->line('unit_price'); ?>" required="required" type="number">
                                        <div class="help-block"><?php echo form_error('unit_price'); ?></div>
                                    </div>
                                </div>
               
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purchase_date"><?php echo $this->lang->line('purchase_date'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="purchase_date"  id="edit_purchase_date" value="<?php echo isset($purchase->purchase_date) ?  date('d-m-Y' , strtotime($purchase->purchase_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('purchase_date'); ?>" required="required" type="text" autocomplete="off">
                                         <div class="help-block"><?php echo form_error('purchase_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expire_date"><?php echo $this->lang->line('expire_date'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="expire_date"  id="edit_expire_date" value="<?php echo isset($purchase->expire_date) ?  date('d-m-Y', strtotime($purchase->expire_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('expire_date'); ?>" type="text" autocomplete="off">
                                         <div class="help-block"><?php echo form_error('expire_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_id"><?php echo $this->lang->line('purchase_by'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="employee_id"  id="edit_employee_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($employees) && !empty($employees)){ ?>
                                                <?php foreach($employees as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($purchase->employee_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('employee_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                             <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($purchase) ? $purchase->note : ''; ?></textarea>
                                             <div class="help-block"><?php echo form_error('note'); ?></div>                                            
                                       </div>
                                </div>
                                                                                            
                            <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($purchase) ? $purchase->qty : ''; ?>" id="old_qty" name="old_qty"/>
                                        <input type="hidden" value="<?php echo isset($purchase) ? $purchase->item_id : ''; ?>" id="old_item_id" name="old_item_id"/>
                                        <input type="hidden" value="<?php echo isset($purchase) ? $purchase->id : ''; ?>" id="id" name="id"/>
                                        <a href="<?php echo site_url('asset/purchase/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                        <?php } ?>
                        
                                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-purchase-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_purchase_data">            
        </div>       
      </div>
    </div>
</div>

  <!-- bootstrap-datetimepicker -->
  
<script type="text/javascript">

 function get_purchase_modal(purchase_id){

     $('.fn_purchase_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
     $.ajax({       
       type   : "POST",
       url    : "<?php echo site_url('asset/purchase/get_single_purchase'); ?>",
       data   : {purchase_id : purchase_id},  
       success: function(response){                                                   
          if(response)
          {
             $('.fn_purchase_data').html(response);
          }
       }
    });
 }
 
</script>
   
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
<script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 
<script type="text/javascript">


    <?php if(isset($edit)){ ?>
        get_item_by_category('<?php echo $purchase->category_id; ?>', '<?php echo $purchase->item_id; ?>', 'edit');
    <?php } ?>
        
    <?php if(isset($post)){ ?>
        get_item_by_category('<?php echo $post['category_id']; ?>', '<?php echo $post['item_id']; ?>', 'add');
    <?php } ?>   
        
    function get_item_by_category(category_id, item_id, form_mode){       
             
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('asset/item/get_item_by_category'); ?>",
            data   : {category_id : category_id, item_id : item_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#'+form_mode+'_item_id').html(response);
               }
            }
        });                  
        
   }

</script>

 <script type="text/javascript">
     $('#add_purchase_date').datepicker(); 
      $('#add_expire_date').datepicker();  
      $('#edit_purchase_date').datepicker(); 
      $('#edit_expire_date').datepicker(); 
    $(document).ready(function() {
      $('#datatable-responsive').DataTable( {
          dom: 'Bfrtip',
          iDisplayLength: 15,
          buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5',
              'pageLength'
          ],
          search: true
      });
    });
    
    $("#add").validate();     
    $("#edit").validate();  
    
</script>