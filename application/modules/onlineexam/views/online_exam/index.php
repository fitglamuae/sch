<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-mouse-pointer"></i><small> <?php echo $this->lang->line('manage_online_exam'); ?> </small></h3>
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
                    
                    <ul  class="nav nav-tabs nav-tab-find bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_exam_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'onlineexam', 'onlineexam')){ ?>
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('onlineexam/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_exam"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>                
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_exam"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                        <?php } ?>                
                                                    
                           
                            <li class="li-class-list">
                                
                             <?php $guardian_access_data = get_guardian_access_data('class');  ?>
                             <?php $teacher_access_data = get_teacher_access_data(); ?>    
                                
                            <?php echo form_open(site_url('onlineexam/index'), array('name' => 'filter', 'id' => 'filter', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                <select  class="form-control col-md-7 col-xs-12" name="class_id" id="filter_class_id" onchange="get_subject_by_class_filter(this.value, '');" style="width: auto;">
                                    <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                       <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php } ?>        
                                    <?php foreach($classes as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_class_id) && $filter_class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?> 
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_class_id) && $filter_class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                                                                     
                                    <?php } ?>                                            
                                </select> 
                                <select  class="form-control col-md-7 col-xs-12" name="subject_id" id="filter_subject_id" style="width: auto;">                                
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php foreach($subjects as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>                                       
                                            <option value="<?php $obj->id; ?>" <?php if(isset($filter_class_id) && $filter_class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                                                                     
                                    <?php } ?>                                            
                                </select> 
                                <input type="submit" name="find" value="<?php echo $this->lang->line('find'); ?>"  class="btn btn-info btn-sm"/>
                            <?php echo form_close(); ?>
                        </li>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_exam_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>   
                                        <th><?php echo $this->lang->line('exam_title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('total_question'); ?></th>
                                        <th><?php echo $this->lang->line('is_publish'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>  
                                    
                                    <?php $count = 1; if(isset($online_exams) && !empty($online_exams)){ ?>
                                        <?php foreach($online_exams as $obj){ ?>
                                        <?php 
                                            if($this->session->userdata('role_id') == GUARDIAN){
                                                if (!in_array($obj->class_id, $guardian_access_data)) { continue; }
                                            }elseif($this->session->userdata('role_id') == STUDENT){
                                                if ($obj->class_id != $this->session->userdata('class_id')){ continue; }                                          
                                            }elseif($this->session->userdata('role_id') == TEACHER){
                                                if (!in_array($obj->class_id, $teacher_access_data)) { continue; }
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>                                           
                                            <td><?php echo $obj->title; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->subject; ?></td>
                                            <td><?php echo count(get_exam_to_question($obj->id)); ?></td>
                                            <td><?php echo $obj->is_publish ? $this->lang->line('yes') : $this->lang->line('no'); ?></td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'onlineexam', 'onlineexam')){ ?>
                                                    <a href="<?php echo site_url('onlineexam/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(VIEW, 'onlineexam', 'onlineexam')){ ?>                                                    
                                                    <a  onclick="get_online_exam_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-online-exam-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(EDIT, 'onlineexam', 'onlineexam')){ ?>
                                                    <a href="<?php echo site_url('onlineexam/addquestion/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('add_question'); ?> </a>
                                                <?php } ?>    
                                                <?php if(has_permission(DELETE, 'onlineexam', 'onlineexam')){ ?>
                                                    <a href="<?php echo site_url('onlineexam/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        
                                        <?php } ?>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_exam">
                            <div class="x_content"> 
                                
                               <?php echo form_open_multipart(site_url('onlineexam/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('exam_title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder="<?php echo $this->lang->line('exam_title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12_"  name="class_id"  id="add_class_id" required="required" onchange="get_section_by_class(this.value, '', 'add');get_subject_by_class(this.value, '', 'add');" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                                <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post['class_id']) && $post['class_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12_"  name="section_id"  id="add_section_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="add_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="instruction_id"><?php echo $this->lang->line('instruction'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="instruction_id"  id="add_instruction_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php foreach($instructions as $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($post['instruction_id']) && $post['instruction_id'] == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->title; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('instruction_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="duration"><?php echo $this->lang->line('duration'); ?> (Minute)<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="duration"  id="add_duration" value="<?php echo isset($post['duration']) ?  $post['duration'] : ''; ?>" placeholder="<?php echo $this->lang->line('duration'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('duration'); ?></div>
                                    </div>
                                </div>  
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="start_date"  id="add_start_date" value="<?php echo isset($post['start_date']) ?  $post['start_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                    </div>
                                </div> 
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="end_date"  id="add_end_date" value="<?php echo isset($post['end_date']) ?  $post['end_date'] : ''; ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_date'); ?></div>
                                    </div>
                                </div> 
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mark_type"><?php echo $this->lang->line('mark_type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="mark_type"  id="add_mark_type" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_mark_type(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($post['mark_type']) && $post['mark_type'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('mark_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pass_mark"><?php echo $this->lang->line('pass_mark'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="pass_mark"  id="add_pass_mark" value="<?php echo isset($post['pass_mark']) ?  $post['pass_mark'] : ''; ?>" placeholder="<?php echo $this->lang->line('pass_mark'); ?>" min="1" required="required" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pass_mark'); ?></div>
                                    </div>
                                </div> 
                                                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_publish"><?php echo $this->lang->line('is_publish'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="is_publish"  id="add_is_publish">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <option value="1" <?php echo isset($post['is_publish']) && $post['is_publish'] == 1 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php echo isset($post['is_publish']) && $post['is_publish'] == 0 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_publish'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_limit"><?php echo $this->lang->line('exam_limit_per_student'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_limit"  id="add_exam_limit" value="<?php echo isset($post['exam_limit']) ?  $post['exam_limit'] : ''; ?>" placeholder="<?php echo $this->lang->line('exam_limit_per_student'); ?>" min="1" required="required" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_limit'); ?></div>
                                    </div>
                                </div>  
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                             <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note"  placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                             <div class="help-block"><?php echo form_error('note'); ?></div>
                                       </div>
                                 </div>                                
                                                     
                                                              
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('onlineexam/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>                                
                            </div>
                        </div>  

                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_exam">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('onlineexam/edit/'.$online_exam->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('exam_title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($online_exam->title) ?  $online_exam->title : ''; ?>" placeholder="<?php echo $this->lang->line('exam_title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="edit_class_id" required="required"  onchange="get_section_by_class(this.value, '', 'edit');get_subject_by_class(this.value, '', 'edit');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php foreach($classes as $obj ){ ?>
                                               <?php
                                                if($this->session->userdata('role_id') == TEACHER){
                                                   if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                } 
                                                ?> 
                                               <option value="<?php echo $obj->id; ?>" <?php if($online_exam->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                            <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="section_id"  id="edit_section_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                        
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="instruction_id"><?php echo $this->lang->line('instruction'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="instruction_id"  id="edit_instruction_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php foreach($instructions as $obj){ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php echo isset($online_exam->instruction_id) && $online_exam->instruction_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->title; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('instruction_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="duration"><?php echo $this->lang->line('duration'); ?> (Minute)<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="duration"  id="edit_duration" value="<?php echo isset($online_exam->duration) ?  $online_exam->duration : ''; ?>" placeholder="<?php echo $this->lang->line('duration'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('duration'); ?></div>
                                    </div>
                                </div>  
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date"><?php echo $this->lang->line('start_date'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="start_date"  id="edit_start_date" value="<?php echo isset($online_exam->start_date) ?  date('d-m-Y', strtotime($online_exam->start_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('start_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('start_date'); ?></div>
                                    </div>
                                </div> 
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date"><?php echo $this->lang->line('end_date'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="end_date"  id="edit_end_date" value="<?php echo isset($online_exam->end_date) ?  date('d-m-Y', strtotime($online_exam->end_date)) : ''; ?>" placeholder="<?php echo $this->lang->line('end_date'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('end_date'); ?></div>
                                    </div>
                                </div> 
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mark_type"><?php echo $this->lang->line('mark_type'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="mark_type"  id="edit_mark_type" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_mark_type(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($online_exam->mark_type) && $online_exam->mark_type == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('mark_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pass_mark"><?php echo $this->lang->line('pass_mark'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="pass_mark"  id="edit_pass_mark" value="<?php echo isset($online_exam->pass_mark) ?  $online_exam->pass_mark : ''; ?>" placeholder="<?php echo $this->lang->line('pass_mark'); ?>" min="1" required="required" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('pass_mark'); ?></div>
                                    </div>
                                </div> 
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_publish"><?php echo $this->lang->line('is_publish'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="is_publish"  id="edit_is_publish">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <option value="1" <?php echo isset($online_exam->is_publish) && $online_exam->is_publish == 1 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php echo isset($online_exam->is_publish) && $online_exam->is_publish == 0 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_publish'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_limit"><?php echo $this->lang->line('exam_limit_per_student'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="exam_limit"  id="edit_exam_limit" value="<?php echo isset($online_exam->exam_limit) ?  $online_exam->exam_limit : ''; ?>" placeholder="<?php echo $this->lang->line('exam_limit_per_student'); ?>" min="1" required="required" type="number" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('exam_limit'); ?></div>
                                    </div>
                                </div>  
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> <?php echo $this->lang->line('note'); ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                             <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="note"  placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($online_exam->note) ?  $online_exam->note : ''; ?></textarea>
                                             <div class="help-block"><?php echo form_error('note'); ?></div>
                                       </div>
                                 </div>
                                
                                 <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status"><?php echo $this->lang->line('is_active'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="status"  id="edit_status">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <option value="1" <?php echo isset($online_exam->status) && $online_exam->status == 1 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('yes'); ?></option>
                                            <option value="0" <?php echo isset($online_exam->status) && $online_exam->status == 0 ?  'selected="selected"' : ''; ?>><?php echo $this->lang->line('no'); ?></option>
                                        </select>
                                        <div class="help-block"><?php echo form_error('is_publish'); ?></div>
                                    </div>
                                </div>
                                                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($online_exam) ? $online_exam->id : ''; ?>" name="id" />
                                        <a href="<?php echo site_url('onlineexam/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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



<div class="modal fade bs-online-exam-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_online_exam_data"></div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_online_exam_modal(online_exam_id){
         
        $('.fn_question_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('onlineexam/get_single_online_exam'); ?>",
          data   : {online_exam_id : online_exam_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_online_exam_data').html(response);
             }
          }
       });
    }
</script>


<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>

 <script type="text/javascript">
     
    $('#add_start_date').datepicker();  
    $('#edit_start_date').datepicker();  
    
    $('#add_end_date').datepicker();  
    $('#edit_end_date').datepicker(); 
    
    
    <?php if(isset($filter_class_id) && $filter_class_id != '' && isset($filter_subject_id)){ ?>
        get_subject_by_class_filter('<?php echo $filter_class_id; ?>', '<?php echo $filter_subject_id; ?>');
    <?php } ?>
        
    function get_subject_by_class_filter(class_id, subject_id){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { class_id : class_id , subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                  
                   $('#filter_subject_id').html(response);                  
               }
            }
        });                  
        
   }

    
    var edit = false;
    <?php if(isset($online_exam)){ ?>
       edit = true;
    <?php } ?>
         
    <?php if(isset($online_exam)){ ?>      
        get_section_by_class('<?php echo $online_exam->class_id; ?>', '<?php echo $online_exam->section_id; ?>','edit');
    <?php } ?>    
    function get_section_by_class(class_id, section_id, form){       
             
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : {class_id : class_id, section_id : section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   $('#'+form+'_section_id').html(response);
               }
            }
        });                  
        
   }


    <?php if(isset($online_exam)){ ?>        
        get_subject_by_class('<?php echo $online_exam->class_id; ?>', '<?php echo $online_exam->subject_id; ?>', 'edit');
    <?php } ?>
    function get_subject_by_class(class_id, subject_id, form){ 
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : {class_id : class_id, subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   $('#'+form+'_subject_id').html(response);                  
               }
            }
        });                  
        
    }

 </script>

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

    function get_exam_list_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    }
    
    $("#add").validate();     
    $("#edit").validate();  
    
</script>
 