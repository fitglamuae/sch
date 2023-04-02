<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-gift"></i><small> <?php echo $this->lang->line('manage_candidate'); ?></small></h3>
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
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_candidate_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        <?php if(has_permission(ADD, 'scholarship', 'candidate')){ ?>
                             <?php if(isset($edit)){ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="<?php echo site_url('scholarship/candidate/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>                          
                             <?php }else{ ?>
                                <li  class="<?php if(isset($add)){ echo 'active'; }?>"><a href="#tab_add_candidate"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add'); ?> </a> </li>                          
                             <?php } ?>
                        <?php } ?> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_candidate"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?></a> </li>                          
                        <?php } ?>
                         
                        <li class="li-class-list">
                            <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                            <?php $teacher_access_data = get_teacher_access_data(); ?>  

                            <select  class="form-control col-md-7 col-xs-12_" onchange="get_candidate_by_class(this.value);">
                                <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                <option value="<?php echo site_url('scholarship/candidate/index'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                <?php } ?>  
                                <?php foreach($classes as $obj ){ ?>
                                    <?php if($this->session->userdata('role_id') == STUDENT ){ ?>
                                        <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                            <option value="<?php echo site_url('scholarship/candidate/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>                                            
                                             <?php if (!in_array($obj->id, $guardian_class_data)) { continue; } ?>
                                            <option value="<?php echo site_url('scholarship/candidate/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>                                            
                                             <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                            <option value="<?php echo site_url('scholarship/candidate/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo site_url('scholarship/candidate/index/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                           
                                <?php } ?>                                            
                            </select>
                        </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_candidate_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                        <th><?php echo $this->lang->line('academic_year'); ?></th>
                                        <th> <?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?> </th>
                                        <th><?php echo $this->lang->line('student'); ?> </th>
                                         <th><?php echo $this->lang->line('note'); ?> </th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                          
                                    <?php $count = 1; if(isset($candidates) && !empty($candidates)){ ?>
                                        <?php foreach($candidates as $obj){ ?>                                       
                                        <tr>
                                            <td><?php echo $count++; ?></td>                                            
                                            <td><?php echo $obj->session_year; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->section_name; ?></td>
                                            <td><?php echo $obj->student_name; ?></td>
                                            <td><?php echo $obj->note; ?></td>
                                            <td>                                                      
                                                <?php if(has_permission(EDIT, 'scholarship', 'candidate')){ ?>
                                                    <a href="<?php echo site_url('scholarship/candidate/edit/'.$obj->id); ?>" title="<?php echo $this->lang->line('edit'); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('edit'); ?> </a>
                                                <?php } ?> 
                                                <?php if(has_permission(DELETE, 'scholarship', 'candidate')){ ?>    
                                                    <a href="<?php echo site_url('scholarship/candidate/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div  class="tab-pane fade in <?php if(isset($add)){ echo 'active'; }?>" id="tab_add_candidate">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('scholarship/candidate/add'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="add_class_id" required="required"  onchange="get_section_by_class(this.value, '', false);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($classes) && !empty($classes)){ ?>
                                                <?php foreach($classes as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>"><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                    </div>
                                </div>
                                 
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12_"  name="section_id"  id="add_section_id" required="required" onchange="get_student_by_section(this.value, '', false);">
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                             
                                        </select>
                                        <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_id"><?php echo $this->lang->line('student'); ?> <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-12 col-xs-12"  name="student_id"  id="add_student_id" required="required" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                        </select>
                                        <div class="help-block"><?php echo form_error('student_id'); ?></div>
                                    </div>
                                </div>
                                        
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control" name="note" id="add_note" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo isset($post['note']) ?  $post['note'] : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('note'); ?></div>
                                    </div>
                                </div> 
                                                                                           
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="<?php echo site_url('scholarship/candidate/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                                </div>
                                
                                <?php echo form_close(); ?>
                            </div>
                        </div>  

                        <?php if(isset($edit)){ ?>
                            <div  class="tab-pane fade in <?php if(isset($edit)){ echo 'active'; }?>" id="tab_edit_candidate">
                            <div class="x_content"> 
                               <?php echo form_open(site_url('scholarship/candidate/edit/'.$candidate->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                
                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select  class="form-control col-md-7 col-xs-12"  name="class_id"  id="edit_class_id" required="required"  onchange="get_section_by_class(this.value, '', true);" >
                                            <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                            <?php if(isset($classes) && !empty($classes)){ ?>
                                                <?php foreach($classes as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($candidate->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">  
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  class="form-control col-md-12 col-xs-12_"  name="section_id"  id="edit_section_id" required="required" onchange="get_student_by_section(this.value, '', true);" >
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                            </select>
                                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                        </div>
                                    </div>
                                
                                    <div class="item form-group">  
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="student_id"><?php echo $this->lang->line('student'); ?> <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select  class="form-control col-md-12 col-xs-12"  name="student_id"  id="edit_student_id" required="required" >
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                            </select>
                                            <div class="help-block"><?php echo form_error('student_id'); ?></div>
                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"><?php echo $this->lang->line('note'); ?> </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea  class="form-control col-md-7 col-xs-12"  name="note"  id="comment"  placeholder="<?php echo $this->lang->line('note'); ?>"> <?php echo isset($candidate->note) ?  $candidate->note : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('note'); ?></div>
                                        </div>
                                    </div> 

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input type="hidden" value="<?php echo isset($candidate) ? $candidate->id : $id; ?>" id="id" name="id" />
                                            <a  href="<?php echo site_url('scholarship/candidate/index'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
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


 <script type="text/javascript">  

    <?php if(isset($candidate)){ ?>       
        get_section_by_class('<?php echo $candidate->class_id; ?>', '<?php echo $candidate->section_id; ?>', true);
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
   
  
    <?php if(isset($candidate)){ ?>
        get_student_by_section('<?php echo $candidate->section_id; ?>', '<?php echo $candidate->student_id; ?>', true);
    <?php } ?>    
    function get_student_by_section(section_id, student_id, is_edit){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_by_section'); ?>",
            data   : { section_id : section_id , student_id: student_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                   
                  if(is_edit){
                       $('#edit_student_id').html(response);
                   }else{
                       $('#add_student_id').html(response);
                   }
               }
            }
        });          
   }
   
  
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
      
    function get_candidate_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    }         
      
    $("#add").validate();   
    $("#edit").validate();   

</script> 
