<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-word-o"></i><small> <?php echo $this->lang->line('manage_assignment'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_assignment_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'academic', 'assignment')){ ?>
                            <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_assignment"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>                          
                        <?php } ?>
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_assignment"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a> </li>                          
                        <?php } ?>                
                        <?php if(isset($detail)){ ?>
                            <li  class="active"><a href="#tab_view_assignment"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a> </li>                          
                        <?php } ?> 
                        <li class="li-class-list">
                            
                            <?php $teacher_access_data = get_teacher_access_data(); ?> 
                            <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                            
                            <select  class="form-control col-md-7 col-xs-12  " onchange="get_assignment_by_class(this.value);">
                                <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                <option value="<?php echo site_url('academic/assignment/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                 <?php } ?>  
                                <?php foreach($classes as $obj ){ ?>
                                    <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?> 
                                            <option value="<?php echo site_url('academic/assignment/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                            <?php if (!in_array($obj->id, $guardian_class_data)) { continue; } ?>
                                            <option value="<?php echo site_url('academic/assignment/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                            <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                            <option value="<?php echo site_url('academic/assignment/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo site_url('academic/assignment/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                             
                                <?php } ?>                                            
                            </select>
                        </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_assignment_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('assignment_date'); ?></th>
                                        <th><?php echo $this->lang->line('submission_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($assignments) && !empty($assignments)){ ?>
                                        <?php foreach($assignments as $obj){ ?>
                                        <?php 
                                            if($this->session->userdata('role_id') == GUARDIAN){
                                                if (!in_array($obj->class_id, $guardian_class_data)){ continue; }
                                            }elseif($this->session->userdata('role_id') == TEACHER){
                                                if (!in_array($obj->subject_id, $teacher_access_data)){ continue; }
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $obj->title; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->section; ?></td>
                                            <td><?php echo $obj->subject; ?></td>
                                            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->assigment_date)); ?></td>
                                            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->submission_date)); ?></td>
                                            <td><?php echo $obj->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>  
                                            <td>                                                
                                                <?php if($this->session->userdata('role_id') == SUPER_ADMIN || $this->session->userdata('role_id') == ADMIN || $this->session->userdata('role_id') == TEACHER){ ?>
                                                    <a href="<?php echo site_url('academic/submission/evaluate/'.$obj->id); ?>" class="btn btn-success btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('evaluate'); ?></a>
                                                <?php  } ?>
                                                <?php if(has_permission(EDIT, 'academic', 'assignment')){ ?>
                                                    <a href="<?php echo site_url('academic/assignment/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php  } ?>
                                                <?php if(has_permission(VIEW, 'academic', 'assignment')){ ?>
                                                    <a  onclick="get_assignment_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-assignment-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php if($obj->assignment){ ?>
                                                    <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/assignment/<?php echo $obj->assignment; ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?> </a>
                                                    <?php  } ?>
                                                <?php  } ?>
                                                <?php if(has_permission(DELETE, 'academic', 'assignment')){ ?>
                                                    <a href="<?php echo site_url('academic/assignment/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php  } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_assignment">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('academic/assignment/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder=" <?php echo $this->lang->line('title'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>               
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="class_id"  id="class_id" required="required" onchange="get_subject_by_class(this.value, '', false); get_section_by_class(this.value, '');" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                            <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                            ?>
                                            <option value="<?php echo $obj->id; ?>" ><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="section_id"  id="add_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="subject_id"  id="add_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assignment_date"><?php echo $this->lang->line('assignment_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="assigment_date"  id="add_assigment_date" value="<?php echo isset($post['assigment_date']) ?  $post['assigment_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('assignment_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('assigment_date'); ?></div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="submission_date"><?php echo $this->lang->line('submission_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="submission_date"  id="add_submission_date" value="<?php echo isset($post['submission_date']) ?  $post['submission_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('submission_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('submission_date'); ?></div>
                                    </div>
                                </div>
                                
                               <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('assignment'); ?> 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="assignment"  id="assignment" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>
                                        <div class="help-block"><?php echo form_error('assignment'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12 chk-label" style="padding-top: 0px;" for="sms_notification"><?php echo $this->lang->line('sms_notification'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="notification" type="checkbox" value="1" name="sms_notification" id="add_sms_notification" />
                                        <div class="help-block"><?php echo form_error('sms_notification'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12 chk-label" style="padding-top: 0px;" for="email_notification"><?php echo $this->lang->line('email_notification'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="notification" type="checkbox" value="1" name="email_notification" id="add_email_notification" />
                                        <div class="help-block"><?php echo form_error('email_notification'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('academic/assignment/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="instructions"><strong><?php echo $this->lang->line('instruction'); ?>: </strong> <?php echo $this->lang->line('add_assignment_instruction'); ?></div>
                                </div>
                            </div>
                        </div>  

                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_assignment">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('academic/assignment/edit/'.$assignment->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($assignment->title) ?  $assignment->title : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?>" required="required" type="text">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="class_id"  id="class_id" required="required" onchange="get_subject_by_class(this.value, '', true); get_section_by_class(this.value, '');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                              <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if($assignment->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12  "  name="section_id"  id="edit_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>                                
                                                        
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigment_date"><?php echo $this->lang->line('assignment_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="assigment_date"  id="edit_assigment_date" value="<?php echo isset($assignment->assigment_date) ?  date($this->gsms_setting->sms_date_format, strtotime($assignment->assigment_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('assignment_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('assigment_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="submission_date"><?php echo $this->lang->line('submission_date'); ?> <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="submission_date"  id="edit_submission_date" value="<?php echo isset($assignment->submission_date) ?  date($this->gsms_setting->sms_date_format, strtotime($assignment->submission_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('submission_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('submission_date'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('assignment'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="prev_assignment" id="prev_assignment" value="<?php echo $assignment->assignment; ?>" />
                                        <?php if($assignment->assignment){ ?>
                                        <a target="_blank" href="<?php echo UPLOAD_PATH; ?>/assignment/<?php echo $assignment->assignment; ?>"><?php echo $assignment->assignment; ?></a> <br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="assignment"  id="assignment" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_doc'); ?></div>
                                        <div class="help-block"><?php echo form_error('assignment'); ?></div>
                                    </div>
                                </div>
                                
                                 <div class="item form-group">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12 chk-label" style="padding-top: 0px;" for="sms_notification"><?php echo $this->lang->line('sms_notification'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="notification" type="checkbox" value="1" name="sms_notification" id="edit_sms_notification" <?php echo isset($assignment->sms_notification) && $assignment->sms_notification == 1 ?  'checked="checked"' : ''; ?>/>
                                        <div class="help-block"><?php echo form_error('sms_notification'); ?></div>
                                    </div>
                                </div>
                             
                                 <div class="item form-group">
                                     <label class="control-label col-md-3 col-sm-3 col-xs-12 chk-label" style="padding-top: 0px;" for="email_notification"><?php echo $this->lang->line('email_notification'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="notification" type="checkbox" value="1" name="email_notification" id="edit_email_notification" <?php echo isset($assignment->email_notification) && $assignment->email_notification == 1 ?  'checked="checked"' : ''; ?>/>
                                        <div class="help-block"><?php echo form_error('email_notification'); ?></div>
                                    </div>
                                </div>
                             
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($assignment->note) ?  $assignment->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status"><?php echo $this->lang->line('status'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control col-md-12 col-xs-12 "  name="status"  id="status" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                            <option value="1" <?php if($assignment->status == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('active'); ?></option>                                           
                                            <option value="0" <?php if($assignment->status == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('in_active'); ?></option>                                                                                       
                                        </select>
                                        <div class="help-block"><?php echo form_error('status'); ?></div>
                                    </div>
                                </div>  
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($assignment) ? $assignment->id : ''; ?>" name="id" />
                                        <a  href="<?php echo site_url('academic/assignment/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-assignment-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_assignment_data">
            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_assignment_modal(assignment_id){
         
        $('.fn_assignment_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('academic/assignment/get_single_assignment'); ?>",
          data   : {assignment_id : assignment_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_assignment_data').html(response);
             }
          }
       });
    }
</script>

<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 <script type="text/javascript">
  
   var edit = false;
   
  $('#add_assigment_date').datepicker();
  $('#edit_assigment_date').datepicker();
  $('#add_submission_date').datepicker();
  $('#edit_submission_date').datepicker();

    
    <?php if(isset($edit)){ ?>
        
          edit = true;
        get_section_by_class('<?php echo $assignment->class_id; ?>', '<?php echo $assignment->section_id; ?>');
        get_subject_by_class('<?php echo $assignment->class_id; ?>', '<?php echo $assignment->subject_id; ?>', true);
    <?php } ?>
        
    <?php if(isset($class_id)){ ?>
        get_subject_by_class('<?php echo $class_id; ?>', '', false);
    <?php } ?>
        
    function get_section_by_class(class_id, section_id){       
            
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : { class_id : class_id , section_id : section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   if(edit){
                        $('#edit_section_id').html(response);
                   }else{
                        $('#add_section_id').html(response);
                   }
               }
            }
        }); 
    }
            
    
    function get_subject_by_class(class_id, subject_id, is_edit){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { class_id : class_id , subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   if(is_edit){
                        $('#edit_subject_id').html(response);
                   }else{
                        $('#add_subject_id').html(response);
                   }
               }
            }
        });                  
        
   }
    function get_assignment_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    }
 </script>
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
              search: true
          });
        });
    $("#add").validate();     
    $("#edit").validate(); 
</script>