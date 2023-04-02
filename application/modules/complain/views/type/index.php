<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-commenting-o"></i><small> <?php echo $this->lang->line('manage_complain_type'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_type_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'complain', 'type')){ ?>
                             <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('complain/type/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_type"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_type"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?>  </a> </li>                          
                        <?php } ?>                         
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_type_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                        <th> <?php echo $this->lang->line('complain_type'); ?></th>
                                        <th><?php echo $this->lang->line('created'); ?></th>                                            
                                        <th><?php echo $this->lang->line('status'); ?></th>                                            
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php  $count = 1; if(isset($types) && !empty($types)){ ?>
                                        <?php foreach($types as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>                                           
                                            <td><?php echo $obj->type; ?></td>
                                            <td><?php echo date( $this->gsms_setting->sms_date_format, strtotime($obj->created_at)); ?></td>
                                            <td><?php echo $obj->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'complain', 'type')){ ?>
                                                    <a href="<?php echo site_url('complain/type/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'complain', 'type')){ ?>
                                                    <a href="<?php echo site_url('complain/type/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_type">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('complain/type/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                               
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type"><?php echo $this->lang->line('complain_type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="type"  id="type" value="<?php echo isset($type) ?  $type : ''; ?>" placeholder="<?php echo $this->lang->line('complain_type'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('type'); ?></div>
                                    </div>
                                </div>                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a  href="<?php echo site_url('complain/type/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_type">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('complain/type/edit/'.$type_data->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type"><?php echo $this->lang->line('complain_type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">                                        
                                        <input  class="form-control col-md-7 col-xs-12"  name="type" value="<?php echo isset($type_data) ? $type_data->type : ''; ?>"  placeholder="<?php echo $this->lang->line('complain_type'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('type'); ?></div>
                                    </div>
                                </div>    
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status"><?php echo $this->lang->line('status'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-12 col-xs-12 "  name="status"  id="status" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                            <option value="1" <?php if($type_data->status == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('active'); ?></option>                                           
                                            <option value="0" <?php if($type_data->status == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('in_active'); ?></option>                                                                                       
                                        </select>
                                        <div class="help-block"><?php echo form_error('status'); ?></div>
                                    </div>
                                </div> 
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($type_data) ? $type_data->id : ''; ?>" name="id" />
                                        <a href="<?php echo site_url('complain/type/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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

<!-- datatable with buttons -->
 <script type="text/javascript">
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
              search: true,              
              responsive: true
          });
        });
        
    $("#add").validate();     
    $("#edit").validate(); 
  
</script>