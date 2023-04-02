<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-file-video-o"></i><small> <?php echo $this->lang->line('manage_class_lecture'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_lecture_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'teacher', 'lecture')){ ?>
                        
                            <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('teacher/lecture/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php }else{ ?>
                                 <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_lecture"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?></a> </li>                          
                             <?php } ?>
                        <?php } ?>
                                 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_lecture"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                        <?php } ?>                
                       
                            <li class="li-class-list">
                            
                            <?php $teacher_access_data = get_teacher_access_data(); ?> 
                            <?php $guardian_access_data = get_guardian_access_data('class'); ?>  
                                
                            <select  class="form-control col-md-7 col-xs-12 " onchange="get_lecture_by_class(this.value);">
                                <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                <option value="<?php echo site_url('teacher/lecture/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                 <?php } ?> 

                                <?php foreach($class_list as $obj ){ ?>
                                    <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                        <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?> 
                                        <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                    <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>
                                        <?php if (!in_array($obj->id, $guardian_access_data)) { continue; } ?>
                                        <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                    <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>
                                        <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                        <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo site_url('teacher/lecture/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                    <?php } ?>                                            
                                <?php } ?>                                            
                            </select> 
                        </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_lecture_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('teacher'); ?></th>
                                        <th><?php echo $this->lang->line('class_lecture'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>                                       
                                    <?php $count = 1; if(isset($lectures) && !empty($lectures)){ ?>
                                        <?php foreach($lectures as $obj){ ?>
                                        <?php 
                                            if($this->session->userdata('role_id') == GUARDIAN){
                                                if (!in_array($obj->class_id, $guardian_access_data)){ continue; }
                                            }elseif($this->session->userdata('role_id') == TEACHER){
                                                if (!in_array($obj->class_id, $teacher_access_data)){ continue; }
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $obj->title; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->section; ?></td>
                                            <td><?php echo $obj->subject; ?></td>
                                            <td><?php echo $obj->teacher; ?></td>
                                            <td>
                                                <?php if($obj->lecture_type == 'youtube'){ ?>
                                                    <img src="https://img.youtube.com/vi/<?php echo $obj->video_id; ?>/mqdefault.jpg" width="130"/>
                                                <?php }else if($obj->lecture_type == 'vimeo'){ ?>
                                                    
                                                <?php 

                                                    $vimeo = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/{$obj->video_id}.php"));
                                                    //echo $small = $vimeo[0]['thumbnail_small'];
                                                    //echo $medium = $vimeo[0]['thumbnail_medium'];
                                                    //echo $large = $vimeo[0]['thumbnail_large'];
                                                    if(isset($vimeo) && !empty($vimeo)){
                                                        echo '<img src="'. $vimeo[0]['thumbnail_small'].'" width="130"/>';
                                                    }
                                                ?>  
                                                    
                                                <?php }else if($obj->lecture_type == 'power_point' && $obj->lecture_ppt != ''){ ?>
                                                    <img src="<?php echo IMG_URL; ?>ppt-default-image.jpg" width="130"/>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $obj->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>                                           
                                            <td>
                                                <?php if(has_permission(EDIT, 'teacher', 'lecture')){ ?>
                                                    <a href="<?php echo site_url('teacher/lecture/edit/'.$obj->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php  } ?>
                                                <?php if(has_permission(VIEW, 'teacher', 'lecture')){ ?>
                                                    <a  onclick="get_lecture_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-lecture-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                    <?php if($obj->lecture_ppt){ ?>
                                                        <a target="_blank" href="<?php echo UPLOAD_PATH; ?>video-lecture/<?php echo $obj->lecture_ppt; ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?> </a>
                                                    <?php  } ?>
                                                <?php  } ?>
                                                <?php if(has_permission(DELETE, 'teacher', 'lecture')){ ?>
                                                    <a href="<?php echo site_url('teacher/lecture/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php  } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_lecture">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('teacher/lecture/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($post['title']) ?  $post['title'] : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>               
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="class_id"  id="add_class_id" required="required" onchange="get_section_by_class(this.value, '', false);get_subject_by_class(this.value, '', false);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php foreach($classes as $obj ){ ?>
                                                   <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                    ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($post['class_id']) && $post['class_id'] == $obj->id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="section_id"  id="add_section_id">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="subject_id"  id="add_subject_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_type"><?php echo $this->lang->line('lecture_type'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="lecture_type"  id="lecture_type" required="required" onchange="get_video_lecture_type(this.value, 'add');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_video_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($post['lecture_type']) && $post['lecture_type'] == $key ?  'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('lecture_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group fn_add_lecture_ppt <?php echo isset($post['lecture_type']) && $post['lecture_type'] == 'power_point' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_ppt"><?php echo $this->lang->line('lecture_ppt'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="lecture_ppt"  id="add_lecture_ppt" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_lecture'); ?></div>
                                        <div class="help-block"><?php echo form_error('lecture_ppt'); ?></div>
                                    </div>
                                </div>                                
                                <div class="item form-group fn_add_lecture_url <?php echo isset($post['lecture_type']) && $post['lecture_type'] != 'power_point' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="video_id"><?php echo $this->lang->line('video_id'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="video_id"  id="add_video_id" value="<?php echo isset($post['video_id']) ?  $post['video_id'] : ''; ?>" placeholder="<?php echo $this->lang->line('video_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('video_id'); ?></div>
                                    </div>
                                </div> 
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="add_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                               
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('teacher/lecture/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                               
                            </div>
                        </div>  

                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_lecture">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('teacher/lecture/edit/'.$lecture->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"><?php echo $this->lang->line('title'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="title"  id="title" value="<?php echo isset($lecture->title) ?  $lecture->title : ''; ?>" placeholder="<?php echo $this->lang->line('title'); ?>" required="required" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('title'); ?></div>
                                    </div>
                                </div>
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="class_id"  id="edit_class_id" required="required" onchange="get_section_by_class(this.value, '', true);get_subject_by_class(this.value, '', true);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                                <?php foreach($classes as $obj ){ ?>
                                                    <?php
                                                    if($this->session->userdata('role_id') == TEACHER){
                                                       if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                                    } 
                                                    ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($lecture->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>                                            
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="section_id"  id="edit_section_id" required="required">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
				                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject_id"><?php echo $this->lang->line('subject'); ?> <span class="required">*</span>  </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="subject_id"  id="edit_subject_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                      
                                        </select>
                                        <div class="help-block"><?php echo form_error('subject_id'); ?></div>
                                    </div>
                                </div>
                                                                                        
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_type"><?php echo $this->lang->line('lecture_type'); ?> <span class="required">*</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12 "  name="lecture_type"  id="lecture_type" required="required" onchange="get_video_lecture_type(this.value, 'edit');">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>  
                                            <?php $types = get_video_types(); ?>
                                            <?php foreach($types as $key=>$value){ ?>
                                                <option value="<?php echo $key; ?>" <?php if($lecture->lecture_type == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('lecture_type'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group fn_edit_lecture_ppt <?php echo isset($lecture->lecture_type) && $lecture->lecture_type == 'power_point' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lecture_ppt"><?php echo $this->lang->line('lecture_ppt'); ?> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                         <input type="hidden" name="prev_lecture_ppt" id="prev_lecture_ppt" value="<?php echo $lecture->lecture_ppt; ?>" />
                                        <?php if($lecture->lecture_ppt){ ?>
                                            <a  class="btn btn-success" target="_blank" href="<?php echo UPLOAD_PATH; ?>video-lecture/<?php echo $lecture->lecture_ppt; ?>"><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?></a> <br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="lecture_ppt"  id="add_lecture_ppt" type="file" >
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_lecture'); ?></div>
                                        <div class="help-block"><?php echo form_error('lecture_ppt'); ?></div>
                                    </div>
                                </div>                                
                                <div class="item form-group fn_edit_lecture_url <?php echo isset($lecture->lecture_type) && $lecture->lecture_type != 'power_point' ? '' : 'display'; ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="video_id"><?php echo $this->lang->line('video_id'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  class="form-control col-md-7 col-xs-12"  name="video_id"  id="edit_video_id" value="<?php echo isset($lecture->video_id) ?  $lecture->video_id : ''; ?>" placeholder="<?php echo $this->lang->line('video_id'); ?>" type="text" autocomplete="off">
                                        <div class="help-block"><?php echo form_error('video_id'); ?></div>
                                    </div>
                                </div> 
                                
                             
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="edit_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($lecture->note) ?  $lecture->note : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status"><?php echo $this->lang->line('status'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-12 col-xs-12 "  name="status"  id="status" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                    
                                            <option value="1" <?php if($lecture->status == 1){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('active'); ?></option>                                           
                                            <option value="0" <?php if($lecture->status == 0){ echo 'selected="selected"';} ?>><?php echo $this->lang->line('in_active'); ?></option>                                                                                       
                                        </select>
                                        <div class="help-block"><?php echo form_error('status'); ?></div>
                                    </div>
                                </div>  
                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($lecture) ? $lecture->id : ''; ?>" name="id" />
                                        <a  href="<?php echo site_url('teacher/lecture/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


<div class="modal fade bs-lecture-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_lecture_data">            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_lecture_modal(lecture_id){
         
        $('.fn_lecture_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('teacher/lecture/get_single_lecture'); ?>",
          data   : {lecture_id : lecture_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_lecture_data').html(response);
             }
          }
       });
    }
    
    $(".modal .close").click(function(){
          jQuery(".modal iframe").attr("src", jQuery(".modal iframe").attr("src"));
    })
</script>

<!-- Super admin js START  -->
 <script type="text/javascript">
     
     
    function get_video_lecture_type(leture_type, form_type){
        if(leture_type == 'power_point'){
            $('.fn_'+form_type+'_lecture_ppt').show();
            $('.fn_'+form_type+'_lecture_url').hide();
        }else{
            $('.fn_'+form_type+'_lecture_url').show();
            $('.fn_'+form_type+'_lecture_ppt').hide();
        }
    }
 
        
    <?php if(isset($post)){ ?>       
        get_subject_by_class('<?php echo $post['class_id']; ?>', '<?php echo $post['subject_id']; ?>');
        get_section_by_class('<?php echo $post['class_id']; ?>', '<?php echo $post['section_id']; ?>');
    <?php } ?> 
   
   
    <?php if(isset($lecture)){ ?>       
        get_section_by_class('<?php echo $lecture->class_id; ?>', '<?php echo $lecture->section_id; ?>', true);
    <?php } ?>    
    function get_section_by_class(class_id, section_id, is_edit){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : { class_id : class_id , section_id: section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                   
                  if(is_edit){
                       $('#edit_section_id').html(response);
                   }else{
                       $('#add_section_id').html(response);
                   }
               }
            }
        });     
        
   }
   

   <?php if(isset($lecture)){ ?>
        get_subject_by_class('<?php echo $lecture->class_id; ?>', '<?php echo $lecture->subject_id; ?>', true);
    <?php } ?>    
    function get_subject_by_class(class_id, subject_id, is_edit){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { class_id : class_id , subject_id: subject_id},               
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

  </script>
<!-- Super admin js end -->

 <script type="text/javascript">
   
   /* Menu Filter Start */
    function get_lecture_by_class(url){          
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
              search: true,              
              responsive: true
          });
        });
        
        
        
    $("#add").validate();     
    $("#edit").validate(); 
</script>