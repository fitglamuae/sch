<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title  no-print">
                <h3 class="head-title"><i class="fa fa-bars"></i><small> <?php echo $this->lang->line('manage_lesson_plan'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link  no-print">
                 <?php $this->load->view('quick-link'); ?>  
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs nav-tab-find bordered  no-print">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_topic_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                        
                        <li class="li-class-list">
                                                          
                            <?php $guardian_class_data = get_guardian_access_data('class'); ?>
                            <?php $teacher_access_data = get_teacher_access_data(); ?>  
                            
                            <?php echo form_open(site_url('lessonplan/index'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                <select  class="form-control col-md-7 col-xs-12 " name="class_id" id="class_id" onchange="get_subject_by_class(this.value, '');" style="width: auto;">
                                    <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php } ?>  
                                    <?php foreach($classes as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT ){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == GUARDIAN){ ?>                                            
                                                 <?php if (!in_array($obj->id, $guardian_class_data)) { continue; } ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                            <?php }elseif($this->session->userdata('role_id') == TEACHER){ ?>                                            
                                                 <?php if (!in_array($obj->id, $teacher_access_data)) { continue; } ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                            <?php } ?>                                           
                                    <?php } ?>                                            
                                </select> 
                                <select  class="form-control col-md-7 col-xs-12 " name="subject_id" id="subject_id" style="width: auto;">                                
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php foreach($subjects as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>                                       
                                            <option value="<?php $obj->id; ?>" <?php if(isset($subject_id) && $subject_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                                                                     
                                    <?php } ?>                                            
                                </select> 
                                <input type="submit" name="find" value="<?php echo $this->lang->line('find'); ?>"  class="btn btn-success btn-sm"/>
                            <?php echo form_close(); ?>
                        </li>    
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_topic_list" >
                            
                            <?php  if (isset($topics) && !empty($topics)) { ?>
                            <div class="x_content">             
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6  col-sm-offset-3 col-xs-offset-3 layout-box">                       
                                        <div><img   src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->logo; ?>" alt="" width="70" /></div>
                                        <h4><?php echo $this->gsms_setting->school_name; ?></h4>
                                        <p><?php echo $this->gsms_setting->address; ?></p>
                                        <h4><?php echo $this->lang->line('lesson_plan'); ?></h4>
                                        <b><?php echo $this->lang->line('academic_year'); ?>:</b> <?php echo $lesson_info->session_year; ?><br/>
                                        <b><?php echo $this->lang->line('class'); ?>:</b> <?php echo $lesson_info->class_name; ?>,
                                        <b><?php echo $this->lang->line('subject'); ?>:</b> <?php echo $lesson_info->subject; ?>
                                    </div>
                                </div>            
                            </div>
                            <?php } ?>
                            
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th  width="6%"><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                        <th  width="22%"><?php echo $this->lang->line('lesson'); ?></th>
                                        <th  width="11%"><?php echo $this->lang->line('start_date'); ?></th>
                                        <th  width="11%"><?php echo $this->lang->line('end_date'); ?> </th>
                                        <th  width="30%"><?php echo $this->lang->line('topic'); ?></th>
                                        <th  width="10%"><?php echo $this->lang->line('start_date'); ?></th>
                                        <th  width="10%"><?php echo $this->lang->line('end_date'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>   
                                          
                                    <?php $count = 1; if(isset($topics) && !empty($topics)){ ?>
                                        <?php foreach($topics as $obj){ ?>                                       
                                        <tr>
                                            <td ><?php echo $count++; ?></td>                                            
                                            <td><?php echo $obj->title; echo ' ('. $this->lang->line($obj->complete_status).')'; ?></td>                                            
                                            <td><?php echo $obj->start_date ? date('d-m-Y', strtotime($obj->start_date)) : ''; ?></td>
                                            <td><?php echo $obj->end_date ? date('d-m-Y', strtotime($obj->end_date)) : ''; ?></td>
                                            <td colspan="3" style="padding: 0;"> 
                                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                <?php $topic_list = get_topic_detail_by_topic_id($obj->id); ?>                                                
                                                <?php if(isset($topic_list) && !empty($topic_list)){ ?>
                                                <?php foreach($topic_list AS $td){ ?>
                                                    <tr>
                                                        <td width="60%"><?php echo $td->title; echo ' ('. $this->lang->line($td->complete_status).')'; ?>:</td>
                                                        <td width="20%"><?php echo $td->start_date ? date('d-m-Y', strtotime($td->start_date)) : ''; ?></td>
                                                        <td><?php echo $td->end_date ? date('d-m-Y', strtotime($td->end_date)) : ''; ?></td>
                                                    </tr>                                                    
                                                <?php } } ?>
                                                </table>
                                            </td>                                           
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            
                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <button class="btn btn-default text-right" onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
             
                </div>
            </div>
        </div>
    </div>
</div>

 <script type="text/javascript">  

    <?php if(isset($class_id) && $class_id != '' && isset($subject_id)){ ?>
        get_subject_by_class('<?php echo $class_id; ?>', '<?php echo $subject_id; ?>');
    <?php } ?>
        
    function get_subject_by_class(class_id, subject_id){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { class_id : class_id , subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                  
                   $('#subject_id').html(response);                  
               }
            }
        });
   }    
 
</script> 